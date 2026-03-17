<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\VendorMessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    /**
     * Page d'accueil du support (pour clients et vendeurs)
     */
    public function index()
    {
        $user = auth()->user();
        $tickets = SupportTicket::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    /**
     * Créer un nouveau ticket de support
     */
    public function create()
    {
        return view('support.create');
    }

    /**
     * Stocker un nouveau ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'support_type' => 'required|in:produit,commande,paiement,livraison,compte,autre',
            'subject' => 'required|string|max:200',
            'description' => 'required|string|min:10|max:2000',
            'contact_method' => 'required|in:plateforme,whatsapp',
            'whatsapp_number' => 'nullable|required_if:contact_method,whatsapp|regex:/[0-9]{10,15}/',
            'priority' => 'nullable|in:basse,normale,haute,urgente',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'support_type' => $validated['support_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'contact_method' => $validated['contact_method'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'priority' => $validated['priority'] ?? 'normale',
            'status' => 'ouvert',
        ]);

        Log::info("Ticket support créé", ['ticket_id' => $ticket->id, 'user_id' => auth()->id()]);

        // Si requête AJAX, retourner JSON
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'ticket_id' => $ticket->id,
                'ticket_url' => route('support.show', $ticket->id)
            ]);
        }

        return redirect()->route('support.show', $ticket->id)
            ->with('success', '✓ Ticket créé avec succès!');
    }

    /**
     * Afficher un ticket de support
     */
    public function show(SupportTicket $ticket)
    {
        // Vérifier que l'utilisateur est propriétaire du ticket ou admin
        if ($ticket->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $messages = $ticket->messages()->latest('created_at')->paginate(20);
        $templates = null;

        // Si vendeur, charger les templates
        if (auth()->user()->role === 'vendeur') {
            $templates = VendorMessageTemplate::where('user_id', auth()->id())
                ->where('is_active', true)
                ->get();
        }

        return view('support.show', compact('ticket', 'messages', 'templates'));
    }

    /**
     * Ajouter un message au ticket
     */
    public function addMessage(Request $request, SupportTicket $ticket)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:1|max:2000',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_from_support' => false,
        ]);

        Log::info("Message support ajouté", ['ticket_id' => $ticket->id]);

        return back()->with('success', '✓ Message envoyé!');
    }

    /**
     * Utiliser un template de message (vendeur)
     */
    public function useTemplate(Request $request, SupportTicket $ticket)
    {
        if (auth()->user()->role !== 'vendeur' || $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'template_id' => 'required|exists:vendor_message_templates,id',
        ]);

        $template = VendorMessageTemplate::findOrFail($validated['template_id']);

        // Vérifier que le template appartient au vendeur
        if ($template->user_id !== auth()->id()) {
            abort(403);
        }

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $template->content,
            'is_from_support' => false,
        ]);

        return back()->with('success', '✓ Message modèle envoyé!');
    }

    /**
     * Fermer un ticket
     */
    public function close(SupportTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->update([
            'status' => 'fermé',
            'resolved_at' => now(),
        ]);

        return back()->with('success', '✓ Ticket fermé');
    }

    /**
     * Réouvrir un ticket
     */
    public function reopen(SupportTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->update([
            'status' => 'ouvert',
            'resolved_at' => null,
        ]);

        return back()->with('success', '✓ Ticket réouvert');
    }
}
