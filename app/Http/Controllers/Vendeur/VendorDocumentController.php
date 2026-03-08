<?php

namespace App\Http\Controllers\Vendeur;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class VendorDocumentController extends Controller
{
    /**
     * Afficher le formulaire de soumission des documents d'identité
     */
    public function submit(): View
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est un vendeur
        if ($user->role !== 'vendor') {
            return redirect()->route('accueil');
        }

        return view('auth.vendor-submit-documents', [
            'user' => $user,
        ]);
    }

    /**
     * Traiter la soumission des documents d'identité
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est un vendeur
        if ($user->role !== 'vendor') {
            return redirect()->route('accueil');
        }

        // Valider les documents
        $validated = $request->validate([
            'id_type' => ['required', 'in:cni,cmu,passport'],
            'id_front' => ['required', 'file', 'mimes:jpeg,png,jpg', 'max:5120'],
            'id_back' => ['required', 'file', 'mimes:jpeg,png,jpg', 'max:5120'],
            'id_number' => ['required', 'string', 'max:50'],
        ], [
            'id_type.required' => 'Veuillez sélectionner le type de document',
            'id_front.required' => 'La photo du recto est obligatoire',
            'id_front.mimes' => 'Le recto doit être une image (JPEG ou PNG)',
            'id_front.max' => 'Le recto ne doit pas dépasser 5 Mo',
            'id_back.required' => 'La photo du verso est obligatoire',
            'id_back.mimes' => 'Le verso doit être une image (JPEG ou PNG)',
            'id_back.max' => 'Le verso ne doit pas dépasser 5 Mo',
            'id_number.required' => 'Le numéro du document est obligatoire',
        ]);

        try {
            // Créer ou mettre à jour les documents d'identité
            $frontPath = $request->file('id_front')->store('vendors/documents/front', 'public');
            $backPath = $request->file('id_back')->store('vendors/documents/back', 'public');

            // Supprimer les anciens documents s'ils existent
            UserDocument::where('user_id', $user->id)
                ->where('document_type', $validated['id_type'])
                ->delete();

            // Créer le document d'identité (recto)
            UserDocument::create([
                'user_id' => $user->id,
                'document_type' => $validated['id_type'],
                'document_side' => 'front',
                'document_path' => $frontPath,
                'document_number' => $validated['id_number'],
                'status' => 'pending',
            ]);

            // Créer le document d'identité (verso)
            UserDocument::create([
                'user_id' => $user->id,
                'document_type' => $validated['id_type'],
                'document_side' => 'back',
                'document_path' => $backPath,
                'document_number' => $validated['id_number'],
                'status' => 'pending',
            ]);

            // Mettre à jour le statut du vendeur
            $user->update(['vendor_status' => 'pending_validation']);

            // Créer des notifications pour les admins
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'vendor_documents_submitted',
                    'titre' => '📄 Nouveaux documents d\'identité à vérifier',
                    'message' => $user->shop_name . ' a soumis ses documents d\'identité. À vérifier et approuver.',
                    'lu' => false,
                ]);
            }

            Log::info('Documents d\'identité soumis', [
                'vendor_id' => $user->id,
                'document_type' => $validated['id_type'],
            ]);

            // Rediriger vers la page de confirmation
            return redirect()->route('vendor.documents.confirmation');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la soumission des documents', [
                'vendor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['file' => 'Une erreur est survenue lors de la soumission. Veuillez réessayer.']);
        }
    }

    /**
     * Afficher la page de confirmation après soumission des documents
     */
    public function confirmation(): View
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est un vendeur en attente de validation
        if ($user->role !== 'vendor' || $user->vendor_status !== 'pending_validation') {
            return redirect()->route('accueil');
        }

        return view('auth.vendor-documents-submitted', [
            'user' => $user,
        ]);
    }
}
