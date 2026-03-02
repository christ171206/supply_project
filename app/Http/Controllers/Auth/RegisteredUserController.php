<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
        \Log::info('Inscription reçue', ['role' => $request->role, 'name' => $request->name]);
        \Log::info('Données du formulaire', [
            'shop_name' => $request->shop_name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Validation commune
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:2'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:client,vendor'],
            'terms' => ['required'],
        ];

        // Ajouter les validations conditionnelles pour vendeur
        if ($request->role === 'vendor') {
            $validationRules['shop_name'] = ['required', 'string', 'max:255'];
            $validationRules['phone'] = ['required', 'string', 'max:20'];
            $validationRules['address'] = ['required', 'string', 'max:500'];
            // id_document est complètement optionnel, pas de validation si absent
            if ($request->hasFile('id_document')) {
                $validationRules['id_document'] = ['file', 'mimes:jpeg,png,jpg', 'max:5120'];
            }
        }

        \Log::info('Règles de validation', ['rules' => array_keys($validationRules)]);

        try {
            // Valider tous les champs à la fois
            $validated = $request->validate($validationRules, [
                'shop_name.required' => 'Le nom de la boutique est obligatoire',
                'phone.required' => 'Le téléphone est obligatoire',
                'address.required' => 'L\'adresse est obligatoire',
                'id_document.mimes' => 'Le document doit être une image (JPEG, PNG)',
                'id_document.max' => 'Le fichier ne doit pas dépasser 5MB',
            ]);
            \Log::info('Validation réussie');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur validation', ['errors' => $e->errors()]);
            throw $e;
        }

        // Préparer les données utilisateur
        $userData = [
            'name' => $request->name,
            'country' => $request->country,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        // Ajouter les informations vendeur si applicable
        if ($request->role === 'vendor') {
            $userData['shop_name'] = $request->shop_name;
            $userData['phone'] = $request->phone;
            $userData['address'] = $request->address;
            $userData['vendor_status'] = 'pending'; // En attente de vérification

            // Sauvegarder le document d'identité si fourni
            if ($request->hasFile('id_document')) {
                $path = $request->file('id_document')->store('vendors/id-documents', 'public');
                $userData['id_document'] = $path;
            }
        }

        // Créer l'utilisateur
        $user = User::create($userData);
        \Log::info('Utilisateur créé', ['user_id' => $user->id, 'role' => $user->role]);

        // Générer un code de vérification (6 chiffres)
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Sauvegarder le code de vérification
        $user->update([
            'email_verification_code' => $verificationCode,
            'email_verification_code_sent_at' => now(),
        ]);

        // Envoyer l'email avec le code
        Mail::send(new EmailVerificationCodeMail($user, $verificationCode));

        // Sauvegarder l'email et le code en session pour la vérification
        session([
            'registration_email' => $user->email,
            'verification_code_debug' => config('app.env') === 'local' ? $verificationCode : null,
        ]);

        \Log::info('Code de vérification envoyé', ['email' => $user->email]);

        // Rediriger vers la page de vérification du code
        return redirect()->route('verification.code.show');
    }
}
