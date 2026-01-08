<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation commune
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:client,vendeur'],
        ]);

        // Validation supplémentaire pour vendeur
        if ($request->role === 'vendeur') {
            $request->validate([
                'shop_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:500'],
                'id_document' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            ], [
                'shop_name.required' => 'Le nom de la boutique est obligatoire',
                'phone.required' => 'Le téléphone est obligatoire',
                'address.required' => 'L\'adresse est obligatoire',
                'id_document.file' => 'Le fichier doit être valide',
                'id_document.mimes' => 'Le document doit être une image (JPEG, PNG) ou un PDF',
                'id_document.max' => 'Le fichier ne doit pas dépasser 5MB',
            ]);
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Sauvegarder les informations vendeur si applicable
        if ($request->role === 'vendeur') {
            // Sauvegarder le document d'identité si fourni
            if ($request->hasFile('id_document')) {
                $path = $request->file('id_document')->store('vendors/id-documents', 'public');
                // Vous pouvez stocker le chemin dans une table séparée ou dans une colonne JSON
            }

            // Vous pouvez créer une table séparée pour les vendeurs ou ajouter les colonnes à users
            // Pour maintenant, on peut utiliser une colonne JSON ou créer une table Vendor
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirection basée sur le rôle
        if ($user->role === 'vendeur') {
            return redirect(route('vendeur.dashboard', absolute: false));
        }

        return redirect(route('accueil', absolute: false));
    }
}
