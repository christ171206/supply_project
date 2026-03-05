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
    public function index($userId = null)
    {
        $userId = Auth::id();
        $selectedUserId = request()->route('userId');

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

        // Récupérer les messages de cette conversation avec les produits
        $messages = Message::where(function ($query) use ($currentUser, $userId) {
            $query->where('from_user_id', $currentUser->id)->where('to_user_id', $userId)
                ->orWhere('from_user_id', $userId)->where('to_user_id', $currentUser->id);
        })
            ->with('produit')
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
        // Les administrateurs ne peuvent pas envoyer de messages directs
        if (Auth::user()->is_admin) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Les administrateurs n\'ont pas le droit d\'envoyer des messages directs.'
                ], 403);
            }
            return back()->with('error', 'Les administrateurs n\'ont pas le droit d\'envoyer des messages directs.');
        }

        try {
            // Validation basique
            $request->validate([
                'destinataire_id' => 'required|integer',
                'contenu' => 'required|string|min:1|max:5000',
                'sujet' => 'nullable|string|max:255',
                'produit_id' => 'nullable|integer',
            ]);

            $destinataireId = (int) $request->input('destinataire_id');
            $contenu = $request->input('contenu');

            // Validation du destinataire_id
            if (!$destinataireId || $destinataireId <= 0) {
                if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => 'ID destinataire invalide: ' . $destinataireId
                    ], 422);
                }
                return back()->withErrors(['destinataire_id' => 'ID destinataire invalide']);
            }

            // Vérifier que le destinataire existe
            $destinataire = \App\Models\User::find($destinataireId);
            if (!$destinataire) {
                if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le destinataire avec l\'ID ' . $destinataireId . ' n\'existe pas'
                    ], 422);
                }
                return back()->withErrors(['destinataire_id' => 'Le destinataire n\'existe pas']);
            }

            // Vérifier que l'utilisateur ne s'envoie pas un message à lui-même
            if ($destinataireId == Auth::id()) {
                if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous ne pouvez pas vous envoyer un message à vous-même'
                    ], 422);
                }
                return back()->withErrors(['destinataire_id' => 'Vous ne pouvez pas vous envoyer un message à vous-même']);
            }

            // Créer le message
            $message = Message::create([
                'from_user_id' => Auth::id(),
                'to_user_id' => $destinataireId,
                'produit_id' => $request->input('produit_id'),
                'contenu' => $contenu,
                'lu' => false,
            ]);

            // Si c'est une requête AJAX, retourner JSON
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => '✓ Message envoyé avec succès!',
                    'messageId' => $message->id,
                    'redirectUrl' => route('messages.show', $destinataireId)
                ], 201);
            }

            // Sinon, rediriger vers la conversation
            return redirect()->route('messages.show', $destinataireId)
                ->with('success', '✓ Message envoyé avec succès!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erreur de validation: ' . json_encode($e->errors())
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur serveur: ' . $e->getMessage(),
                    'exception' => get_class($e)
                ], 500);
            }
            return back()->with('error', 'Erreur: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Envoyer un message dans une conversation existante
     */
    public function reply(Request $request, $userId)
    {
        $validated = $request->validate([
            'contenu' => 'required|string|min:1|max:5000',
        ]);

        $message = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $userId,
            'contenu' => $validated['contenu'],
            'lu' => false,
        ]);

        // Si c'est une requête AJAX, retourner JSON
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès',
                'data' => $message
            ], 201);
        }

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

    /**
     * API endpoint for WebSocket server to store messages
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'from_user_id' => 'required|integer|exists:users,id',
                'to_user_id' => 'required|integer|exists:users,id|different:from_user_id',
                'contenu' => 'required|string|min:1|max:5000',
            ]);

            // Create the message
            $message = Message::create([
                'from_user_id' => $validated['from_user_id'],
                'to_user_id' => $validated['to_user_id'],
                'contenu' => $validated['contenu'],
                'lu' => false,
            ]);

            return response()->json([
                'success' => true,
                'id' => $message->id,
                'message' => 'Message saved successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
