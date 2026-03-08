<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use App\Mail\NewVendorRegistrationNotification;
use App\Models\Notification;
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
        Log::info('Inscription reçue', ['role' => $request->role, 'name' => $request->name]);
        Log::info('Données du formulaire', [
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
            // Les documents seront collectés APRÈS vérification de l'email
        }

        Log::info('Règles de validation', ['rules' => array_keys($validationRules)]);

        try {
            // Valider tous les champs à la fois
            $validated = $request->validate($validationRules, [
                'shop_name.required' => 'Le nom de la boutique est obligatoire',
                'phone.required' => 'Le téléphone est obligatoire',
                'address.required' => 'L\'adresse est obligatoire',
            ]);
            Log::info('Validation réussie');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur validation', ['errors' => $e->errors()]);
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
            $userData['vendor_status'] = 'pending'; // En attente de vérification des documents d'identité
        }

        // Créer l'utilisateur
        $user = User::create($userData);
        Log::info('Utilisateur créé', ['user_id' => $user->id, 'role' => $user->role]);

        // Si c'est un vendeur, notifier l'admin
        if ($request->role === 'vendor') {
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                // Créer une notification dans le dashboard
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'new_vendor_registration',
                    'titre' => '🏪 Nouvelle demande vendeur',
                    'message' => $user->shop_name . ' (' . $user->email . ') a demandé à devenir vendeur. À vérifier.',
                    'lu' => false,
                ]);
            }
            
            // Envoyer l'email au premier admin (éviter les limites Mailtrap en dev)
            $firstAdmin = $admins->first();
            if ($firstAdmin) {
                Mail::to($firstAdmin->email)->queue(new NewVendorRegistrationNotification($user));
                Log::info('Email de notification vendeur envoyé', ['vendor_id' => $user->id, 'admin_id' => $firstAdmin->id]);
            }
        }

        // Générer un code de vérification (6 chiffres)
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Sauvegarder le code de vérification
        $user->update([
            'email_verification_code' => $verificationCode,
            'email_verification_code_sent_at' => now(),
        ]);

        // Envoyer l'email avec le code (en queue aussi)
        Mail::to($user->email)->queue(new EmailVerificationCodeMail($user, $verificationCode));

        // Sauvegarder l'email en session pour la vérification
        session([
            'registration_email' => $user->email,
        ]);

        Log::info('Code de vérification envoyé', ['email' => $user->email]);

        // Tous les utilisateurs (clients et vendeurs) vont d'abord vérifier leur email
        return redirect()->route('verification.code.show');
    }
}
