<?php

namespace App\Http\Controllers\Admin;

use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminAuditController extends Controller
{
    /**
     * Afficher tous les logs d'audit
     */
    public function index(Request $request): View
    {
        $query = SecurityLog::with('user');

        // Filtrer par utilisateur
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filtrer par type d'événement
        if ($request->has('event_type') && $request->event_type) {
            $query->where('event_type', $request->event_type);
        }

        // Filtrer par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtrer par date
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filtrer par pays
        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }

        $logs = $query->orderBy('created_at', 'DESC')->paginate(25);

        // Récupérer les utilisateurs pour le filtre
        $users = User::whereHas('securityLogs')->pluck('name', 'id');

        // Types d'événements disponibles
        $eventTypes = SecurityLog::distinct()->pluck('event_type');

        // Statuts disponibles
        $statuses = ['success', 'failed', 'warning'];

        // Pays disponibles
        $countries = SecurityLog::distinct()->pluck('country')->filter();

        // Statistiques
        $stats = $this->getStatistics();

        return view('admin.audit.index', [
            'logs' => $logs,
            'users' => $users,
            'eventTypes' => $eventTypes,
            'statuses' => $statuses,
            'countries' => $countries,
            'stats' => $stats,
        ]);
    }

    /**
     * Afficher les détails d'un log d'audit
     */
    public function show(SecurityLog $log): View
    {
        return view('admin.audit.show', [
            'log' => $log,
        ]);
    }

    /**
     * Filtrer les logs par admin/utilisateur
     */
    public function byAdmin(Request $request, User $user): View
    {
        $logs = $user->securityLogs()->orderBy('created_at', 'DESC')->paginate(25);

        return view('admin.audit.user', [
            'user' => $user,
            'logs' => $logs,
        ]);
    }

    /**
     * Exporter les logs d'audit
     */
    public function export(Request $request)
    {
        $query = SecurityLog::query();

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->orderBy('created_at', 'DESC')->get();

        $csv = "Date,Utilisateur,Type d'Événement,Statut,IP,Ville,Pays,Appareil,Navigateur\n";

        foreach ($logs as $log) {
            $csv .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $log->created_at->format('d/m/Y H:i'),
                $log->user?->name ?? 'Unknown',
                $log->event_type,
                $log->status,
                $log->ip_address,
                $log->city,
                $log->country,
                $log->device_type,
                $log->browser
            );
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit-logs-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Obtenir les statistiques d'audit
     */
    public function stats(Request $request): View
    {
        $fromDate = now()->subDays(30);
        $toDate = now();

        if ($request->has('from_date') && $request->from_date) {
            $fromDate = \Carbon\Carbon::parse($request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $toDate = \Carbon\Carbon::parse($request->to_date);
        }

        $query = SecurityLog::whereBetween('created_at', [$fromDate, $toDate]);

        // Statistiques globales
        $totalEvents = $query->count();
        $successCount = (clone $query)->where('status', 'success')->count();
        $failedCount = (clone $query)->where('status', 'failed')->count();
        $warningCount = (clone $query)->where('status', 'warning')->count();

        // Événements par type
        $eventsByType = (clone $query)
            ->selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->orderByDesc('count')
            ->get();

        // Utilisateurs les plus actifs
        $activeUsers = (clone $query)
            ->selectRaw('user_id, COUNT(*) as count')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Les tentatives les plus échouées (IP)
        $failedAttempts = (clone $query)
            ->where('status', 'failed')
            ->selectRaw('ip_address, COUNT(*) as count')
            ->groupBy('ip_address')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Pays les plus actifs
        $activeCountries = (clone $query)
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Événements par jour
        $eventsByDay = (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.audit.stats', [
            'totalEvents' => $totalEvents,
            'successCount' => $successCount,
            'failedCount' => $failedCount,
            'warningCount' => $warningCount,
            'eventsByType' => $eventsByType,
            'activeUsers' => $activeUsers,
            'failedAttempts' => $failedAttempts,
            'activeCountries' => $activeCountries,
            'eventsByDay' => $eventsByDay,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    /**
     * Obtenir statistiques rapides
     */
    private function getStatistics()
    {
        $last24h = now()->subHours(24);
        $last7d = now()->subDays(7);

        return [
            'events_24h' => SecurityLog::where('created_at', '>=', $last24h)->count(),
            'events_7d' => SecurityLog::where('created_at', '>=', $last7d)->count(),
            'failed_24h' => SecurityLog::where('created_at', '>=', $last24h)
                ->where('status', 'failed')->count(),
            'unique_users_24h' => SecurityLog::where('created_at', '>=', $last24h)
                ->distinct('user_id')->count('user_id'),
        ];
    }
}
