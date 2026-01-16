<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Afficher les conversations de l'utilisateur
     */
    public function index()
    {
        $userId = Auth::id();

        // Récupérer toutes les conversations (derniers messages avec chaque utilisateur)
        $conversations = Message::where(function ($query) use ($userId) {
            $query->where('from_user_id', $userId)
                  ->orWhere('to_user_id', $userId);
        })
        ->latest()
        ->get()
        ->unique(function ($message) {
            // Créer une clé unique pour chaque conversation (peu importe la direction)
            return $message->from_user_id === Auth::id() 
                ? min(Auth::id(), $message->to_user_id) . '-' . max(Auth::id(), $message->to_user_id)
                : min($message->from_user_id, Auth::id()) . '-' . max($message->from_user_id, Auth::id());
        })
        ->values();

        return view('messages.inbox', ['conversations' => $conversations]);
    }

    /**
     * Afficher la conversation avec un utilisateur
     */
    public function show($userId)
    {
        $currentUser = Auth::user();
        $otherUser = User::findOrFail($userId);

        // Récupérer les messages de cette conversation
        $messages = Message::where(function ($query) use ($currentUser, $userId) {
            $query->where('from_user_id', $currentUser->id)->where('to_user_id', $userId)
                  ->orWhere('from_user_id', $userId)->where('to_user_id', $currentUser->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Marquer les messages reçus comme lus
        Message::where('from_user_id', $userId)
               ->where('to_user_id', $currentUser->id)
               ->where('lu', false)
               ->update(['lu' => true]);

        return view('messages.conversation', [
            'otherUser' => $otherUser,
            'messages' => $messages,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Enregistrer un nouveau message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destinataire_id' => 'required|exists:users,id|different:from_user_id',
            'contenu' => 'required|string|min:1|max:5000',
            'sujet' => 'nullable|string|max:255',
            'produit_id' => 'nullable|exists:produits,id',
        ]);

        // Créer le message
        $message = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $validated['destinataire_id'],
            'contenu' => $validated['contenu'],
            'lu' => false,
        ]);

        // Si on vient d'une page produit, rediriger vers la conversation
        if ($request->has('produit_id')) {
            return redirect()->route('messages.show', $validated['destinataire_id'])
                ->with('success', '✓ Message envoyé au vendeur !');
        }

        return redirect()->route('messages.show', $validated['destinataire_id'])
            ->with('success', '✓ Message envoyé !');
    }

    /**
     * Envoyer un message dans une conversation existante
     */
    public function reply(Request $request, $userId)
    {
        $validated = $request->validate([
            'contenu' => 'required|string|min:1|max:5000',
        ]);

        Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $userId,
            'contenu' => $validated['contenu'],
            'lu' => false,
        ]);

        return redirect()->route('messages.show', $userId)
            ->with('success', '✓ Message envoyé !');
    }

    /**
     * Supprimer un message
     */
    public function destroy($messageId)
    {
        $message = Message::findOrFail($messageId);

        // Vérifier que l'utilisateur est l'auteur du message
        if ($message->from_user_id !== Auth::id()) {
            abort(403);
        }

        $userId = $message->to_user_id;
        $message->delete();

        return redirect()->route('messages.show', $userId)
            ->with('success', '✓ Message supprimé');
    }

    /**
     * Compter les messages non lus
     */
    public function unreadCount()
    {
        $count = Message::where('to_user_id', Auth::id())
                       ->where('lu', false)
                       ->count();

        return response()->json(['count' => $count]);
    }
}
