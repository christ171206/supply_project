<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmailVerificationCodeController extends Controller
{
    /**
     * Show the email verification code form.
     */
    public function show(Request $request): View|RedirectResponse
    {
        // Récupérer l'utilisateur de la session
        if (!session()->has('registration_email')) {
            return redirect()->route('register');
        }

        return view('auth.verify-email-code', [
            'email' => session('registration_email'),
        ]);
    }

    /**
     * Verify the email verification code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Le code est requis',
            'code.size' => 'Le code doit contenir 6 caractères',
        ]);

        $email = session('registration_email');

        if (!$email) {
            return redirect()->route('register');
        }

        // Chercher l'utilisateur par email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Utilisateur non trouvé.']);
        }

        // Vérifier le code
        if ($user->email_verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Le code est incorrect.']);
        }

        // Vérifier que le code n'a pas expiré (10 minutes)
        if ($user->email_verification_code_sent_at && now()->diffInMinutes($user->email_verification_code_sent_at) > 10) {
            return back()->withErrors(['code' => 'Le code a expiré. Veuillez demander un nouveau code.']);
        }

        // Marquer l'email comme vérifié
        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_code_sent_at' => null,
        ]);

        // Authentifier l'utilisateur
        Auth::login($user);

        // Nettoyer la session
        session()->forget('registration_email');

        // Redirection basée sur le rôle
        if ($user->role === 'vendor') {
            \Illuminate\Support\Facades\Log::info('✅ Vendeur authentifié, redirection vers soumission de documents', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
            ]);
            // Rediriger vers la page de soumission des documents d'identité
            return redirect()->route('vendor.documents.submit')->with('success', 'Email vérifié ! Veuillez maintenant soumettre vos documents d\'identité.');
        }

        return redirect()->route('accueil');
    }

    /**
     * Resend the verification code.
     */
    public function resend(Request $request): RedirectResponse
    {
        $email = session('registration_email');

        if (!$email) {
            return redirect()->route('register');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Utilisateur non trouvé.']);
        }

        // Générer un nouveau code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'email_verification_code' => $verificationCode,
            'email_verification_code_sent_at' => now(),
        ]);

        // Envoyer l'email
        \Illuminate\Support\Facades\Mail::send(new \App\Mail\EmailVerificationCodeMail($user, $verificationCode));

        return back()->with('message', 'Un nouveau code de vérification a été envoyé à votre adresse email.');
    }
}
