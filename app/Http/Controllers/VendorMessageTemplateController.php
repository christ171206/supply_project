<?php

namespace App\Http\Controllers;

use App\Models\VendorMessageTemplate;
use Illuminate\Http\Request;

class VendorMessageTemplateController extends Controller
{
    /**
     * Afficher la liste des templates
     */
    public function index()
    {
        $templates = VendorMessageTemplate::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('vendor.message-templates.index', compact('templates'));
    }

    /**
     * Créer un nouveau template
     */
    public function create()
    {
        $defaultTemplates = [
            [
                'category' => 'Promotion',
                'title' => 'Nouvelle promotion',
                'content' => 'Découvrez notre nouvelle promotion! Jusqu\'à -50% sur nos produits sélectionnés. Cette offre est limitée, ne manquez pas cette opportunité!'
            ],
            [
                'category' => 'Promotion',
                'title' => 'Vente flash',
                'content' => 'VENTE FLASH! 24h seulement. Produits limitées à prix réduits. Quantités limitées, premiers arrivés premiers servis!'
            ],
            [
                'category' => 'Service',
                'title' => 'Assistance produit',
                'content' => 'Bonjour! Je suis là pour vous aider avec votre produit. Comment puis-je vous assister aujourd\'hui?'
            ],
            [
                'category' => 'Service',
                'title' => 'Retard de livraison',
                'content' => 'Nous vous présentons nos excuses pour le retard de votre livraison. Nous travaillons pour vous envoyer votre commande dans les meilleurs délais.'
            ],
            [
                'category' => 'Service',
                'title' => 'Remerciement client',
                'content' => 'Merci pour votre achat! Nous apprécions vraiment votre confiance. N\'hésitez pas à nous contacter pour toute question.'
            ]
        ];

        return view('vendor.message-templates.create', compact('defaultTemplates'));
    }

    /**
     * Stocker un nouveau template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|min:10|max:2000',
            'category' => 'required|in:Promotion,Service,Autre',
        ]);

        VendorMessageTemplate::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_default' => false,
            'is_active' => true,
        ]);

        return redirect()->route('vendor.message-templates.index')
            ->with('success', '✓ Template créé!');
    }

    /**
     * Éditer un template
     */
    public function edit(VendorMessageTemplate $template)
    {
        if ($template->user_id !== auth()->id()) {
            abort(403);
        }

        return view('vendor.message-templates.edit', compact('template'));
    }

    /**
     * Mettre à jour un template
     */
    public function update(Request $request, VendorMessageTemplate $template)
    {
        if ($template->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|min:10|max:2000',
            'category' => 'required|in:Promotion,Service,Autre',
        ]);

        $template->update($validated);

        return redirect()->route('vendor.message-templates.index')
            ->with('success', '✓ Template modifié!');
    }

    /**
     * Supprimer un template
     */
    public function destroy(VendorMessageTemplate $template)
    {
        if ($template->user_id !== auth()->id()) {
            abort(403);
        }

        $template->delete();

        return back()->with('success', '✓ Template supprimé');
    }

    /**
     * Toggle activation/désactivation
     */
    public function toggle(VendorMessageTemplate $template)
    {
        if ($template->user_id !== auth()->id()) {
            abort(403);
        }

        $template->update([
            'is_active' => !$template->is_active
        ]);

        return back()->with('success', '✓ Template mise à jour');
    }
}
