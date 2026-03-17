<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoAbuseRule;
use App\Models\PromoAbuseLog;
use Illuminate\Http\Request;

class AdminAbuseRuleController extends Controller
{
    /**
     * Display all abuse rules
     */
    public function index()
    {
        $rules = PromoAbuseRule::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_rules' => PromoAbuseRule::count(),
            'enabled_rules' => PromoAbuseRule::where('is_enabled', true)->count(),
            'total_violations' => PromoAbuseLog::count(),
            'recent_violations' => PromoAbuseLog::recent(7)->count(),
            'blocked_violations' => PromoAbuseLog::where('action_taken', 'blocked')->count(),
            'total_potential_loss' => PromoAbuseLog::sum('potential_loss'),
        ];

        return view('admin.abuse-rules.index', compact('rules', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $ruleTypes = [
            'limit_per_user' => 'Limite par utilisateur',
            'limit_per_day' => 'Limite par jour (global)',
            'limit_per_week' => 'Limite par semaine (global)',
            'limit_per_month' => 'Limite par mois (global)',
            'min_account_age' => 'Âge minimum du compte',
            'min_cart_value' => 'Valeur panier minimum',
            'max_discount_per_day' => 'Réduction max par jour',
            'forbidden_combination' => 'Combinaisons interdites',
            'excluded_categories' => 'Catégories exclues',
            'excluded_vendors' => 'Vendeurs exclus',
            'max_quantity_per_order' => 'Quantité max à l\'ordre',
        ];

        return view('admin.abuse-rules.create', compact('ruleTypes'));
    }

    /**
     * Store new rule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'rule_type' => 'required|string',
            'config' => 'required|json',
            'applies_to' => 'required|in:all,specific_promo,specific_coupon,global_offers',
            'applies_to_id' => 'nullable|integer',
            'severity' => 'required|in:1,2,3',
        ]);

        try {
            $rule = PromoAbuseRule::create([
                ...$validated,
                'config' => json_decode($request->config, true),
                'created_by' => auth()->id(),
                'is_enabled' => true,
            ]);

            return redirect()->route('admin.abuse-rules.show', $rule)
                ->with('success', 'Règle créée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Show rule details
     */
    public function show(PromoAbuseRule $rule)
    {
        $rule->load('creator', 'logs');

        $stats = [
            'violations_count' => $rule->logs()->count(),
            'blocked_count' => $rule->logs()->where('action_taken', 'blocked')->count(),
            'flagged_count' => $rule->logs()->where('violation_type', 'flagged')->count(),
            'potential_loss' => $rule->logs()->sum('potential_loss'),
        ];

        $recentViolations = $rule->logs()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.abuse-rules.show', compact('rule', 'stats', 'recentViolations'));
    }

    /**
     * Show edit form
     */
    public function edit(PromoAbuseRule $rule)
    {
        $ruleTypes = [
            'limit_per_user' => 'Limite par utilisateur',
            'limit_per_day' => 'Limite par jour (global)',
            'limit_per_week' => 'Limite par semaine (global)',
            'limit_per_month' => 'Limite par mois (global)',
            'min_account_age' => 'Âge minimum du compte',
            'min_cart_value' => 'Valeur panier minimum',
            'max_discount_per_day' => 'Réduction max par jour',
            'forbidden_combination' => 'Combinaisons interdites',
            'excluded_categories' => 'Catégories exclues',
            'excluded_vendors' => 'Vendeurs exclus',
            'max_quantity_per_order' => 'Quantité max à l\'ordre',
        ];

        return view('admin.abuse-rules.edit', compact('rule', 'ruleTypes'));
    }

    /**
     * Update rule
     */
    public function update(Request $request, PromoAbuseRule $rule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'rule_type' => 'required|string',
            'config' => 'required|json',
            'applies_to' => 'required|in:all,specific_promo,specific_coupon,global_offers',
            'applies_to_id' => 'nullable|integer',
            'severity' => 'required|in:1,2,3',
            'is_enabled' => 'sometimes|boolean',
        ]);

        try {
            $rule->update([
                ...$validated,
                'config' => json_decode($request->config, true),
            ]);

            return redirect()->route('admin.abuse-rules.show', $rule)
                ->with('success', 'Règle mise à jour');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Toggle rule enabled status
     */
    public function toggle(PromoAbuseRule $rule)
    {
        $rule->update(['is_enabled' => !$rule->is_enabled]);

        return response()->json([
            'success' => true,
            'message' => $rule->is_enabled ? 'Règle activée' : 'Règle désactivée',
            'is_enabled' => $rule->is_enabled,
        ]);
    }

    /**
     * Delete rule
     */
    public function destroy(PromoAbuseRule $rule)
    {
        try {
            $rule->delete();
            return redirect()->route('admin.abuse-rules.index')
                ->with('success', 'Règle supprimée');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Show abuse violations log
     */
    public function violations()
    {
        $violations = PromoAbuseLog::with(['user', 'rule', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => PromoAbuseLog::count(),
            'attempted' => PromoAbuseLog::where('violation_type', 'attempted')->count(),
            'blocked' => PromoAbuseLog::where('violation_type', 'blocked')->count(),
            'flagged' => PromoAbuseLog::where('violation_type', 'flagged')->count(),
            'total_loss' => PromoAbuseLog::sum('potential_loss'),
        ];

        return view('admin.abuse-rules.violations', compact('violations', 'stats'));
    }

    /**
     * Handle a violation
     */
    public function handleViolation(Request $request, PromoAbuseLog $violation)
    {
        $validated = $request->validate([
            'action_taken' => 'required|in:none,warning,blocked,manual_review',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        try {
            $violation->update($validated);

            // If blocking user, could add to ban list
            if ($validated['action_taken'] === 'blocked') {
                // TODO: Add to user ban if severity high enough
            }

            return response()->json([
                'success' => true,
                'message' => 'Violation traitée'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export violations
     */
    public function exportViolations(Request $request)
    {
        $query = PromoAbuseLog::with(['user', 'rule']);

        if ($request->has('type')) {
            $query->where('violation_type', $request->type);
        }

        if ($request->has('action')) {
            $query->where('action_taken', $request->action);
        }

        $violations = $query->get();

        // Create CSV
        $filename = 'abuse-violations-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Header
        fputcsv($handle, [
            'Date',
            'Utilisateur',
            'Email',
            'Règle',
            'Type Violation',
            'Perte Potentielle',
            'Action',
            'Notes Admin',
        ]);

        // Data
        foreach ($violations as $violation) {
            fputcsv($handle, [
                $violation->created_at->format('d/m/Y H:i'),
                $violation->user?->nom ?? 'Unknown',
                $violation->user?->email ?? 'Unknown',
                $violation->rule?->name ?? 'Unknown',
                $violation->getTypeLabel(),
                $violation->potential_loss . ' FCFA',
                $violation->getActionLabel(),
                $violation->admin_notes,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
