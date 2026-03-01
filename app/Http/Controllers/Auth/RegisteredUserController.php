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
            'country' => ['required', 'string', 'max:2'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:client,vendor'],
            'terms' => ['required'],
        ]);

        // Validation supplémentaire pour vendeur
        if ($request->role === 'vendor') {
            $request->validate([
                'shop_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:500'],
                'id_document' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:5120'],
            ], [
                'shop_name.required' => 'Le nom de la boutique est obligatoire',
                'phone.required' => 'Le téléphone est obligatoire',
                'address.required' => 'L\'adresse est obligatoire',
                'id_document.mimes' => 'Le document doit être une image (JPEG, PNG)',
                'id_document.max' => 'Le fichier ne doit pas dépasser 5MB',
            ]);
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

        // Rediriger vers la page de vérification du code
        return redirect()->route('verification.code.show');
    }
}
