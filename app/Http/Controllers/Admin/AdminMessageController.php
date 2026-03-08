<?php

namespace App\Http\Controllers\Admin;

use App\Models\Message;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class AdminMessageController extends Controller
{
    /**
     * Afficher tous les messages
     */
    public function index(Request $request): View
    {
        $query = Message::with(['fromUser', 'toUser', 'commande', 'produit']);

        // Filtrer par statut (signalé/supprimé)
        if ($request->has('status')) {
            if ($request->status == 'flagged') {
                $query->where('is_flagged', true);
            } elseif ($request->status == 'deleted') {
                $query->whereNotNull('deleted_at');
            }
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $query->where('contenu', 'like', '%' . $request->search . '%')
                ->orWhereHas('fromUser', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'DESC');
        $query->orderBy($sortBy, $sortOrder);

        $messages = $query->paginate(20);

        return view('admin.messages.index', [
            'messages' => $messages,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Afficher un message en détail
     */
    public function show(Message $message): View
    {
        return view('admin.messages.show', [
            'message' => $message->load(['fromUser', 'toUser', 'commande', 'produit', 'flaggedByUser', 'deletedByAdmin']),
        ]);
    }

    /**
     * Marquer un message comme signalé (par utilisateur)
     */
    public function flag(Request $request, Message $message)
    {
        if ($message->is_flagged) {
            return back()->with('warning', 'Ce message est déjà signalé.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $message->flag(auth()->user(), $validated['reason']);

        AuditService::logCreate(
            'MessageFlag',
            $message->id,
            "Message signalé par {$message->fromUser->name}",
            ['reason' => $validated['reason']]
        );

        return back()->with('success', 'Message signalé pour révision administrative.');
    }

    /**
     * Afficher messages signalés
     */
    public function flagged(Request $request): View
    {
        $query = Message::where('is_flagged', true)->with(['fromUser', 'toUser', 'commande', 'produit', 'flaggedByUser']);

        if ($request->has('search') && $request->search) {
            $query->where('contenu', 'like', '%' . $request->search . '%')
                ->orWhereHas('fromUser', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $messages = $query->orderBy('updated_at', 'DESC')->paginate(20);

        return view('admin.messages.flagged', [
            'messages' => $messages,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Supprimer un message (par admin)
     */
    public function delete(Request $request, Message $message)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $oldValues = $message->only(['deleted_by_admin', 'deleted_at', 'delete_reason']);

        $message->deleteByAdmin(auth()->user(), $validated['reason']);

        // Log l'action
        AuditService::logUpdate(
            'Message',
            $message->id,
            "Message de {$message->fromUser->name}",
            $oldValues,
            $message->only(['deleted_by_admin', 'deleted_at', 'delete_reason'])
        );

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé avec succès !');
    }

    /**
     * Restaurer un message supprimé
     */
    public function restore(Message $message)
    {
        if (!$message->deleted_at) {
            return back()->with('error', 'Ce message n\'a pas été supprimé.');
        }

        $oldValues = $message->only(['deleted_by_admin', 'deleted_at', 'delete_reason']);

        $message->restore();

        // Log l'action
        AuditService::logUpdate(
            'Message',
            $message->id,
            "Message de {$message->fromUser->name}",
            $oldValues,
            $message->only(['deleted_by_admin', 'deleted_at', 'delete_reason'])
        );

        return back()->with('success', 'Message restauré avec succès !');
    }

    /**
     * Envoyer message aux messages signalés (rejeter signalement)
     */
    public function dismissFlag(Message $message)
    {
        if (!$message->is_flagged) {
            return back()->with('error', 'Ce message n\'est pas signalé.');
        }

        $message->update([
            'is_flagged' => false,
            'flag_reason' => null,
            'flagged_by_user' => null,
        ]);

        AuditService::logUpdate(
            'Message',
            $message->id,
            "Message de {$message->fromUser->name}",
            ['is_flagged' => true],
            ['is_flagged' => false]
        );

        return back()->with('success', 'Signalement rejeté.');
    }
}
