<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    /**
     * Valider si un email existe déjà
     * Utiole pour la validation temps réel lors de l'enregistrement
     */
    public function validateEmail(Request $request): JsonResponse
    {
        $email = $request->input('email');

        if (!$email) {
            return response()->json([
                'valid' => false,
                'message' => 'Email requis'
            ]);
        }

        // Vérifier si l'e-mail existe
        $exists = User::where('email', $email)->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? '❌ Cet email est déjà utilisé' : '✅ Email disponible'
        ]);
    }

    /**
     * Valider si un username existe déjà
     */
    public function validateUsername(Request $request): JsonResponse
    {
        $username = $request->input('username');

        if (!$username) {
            return response()->json([
                'valid' => false,
                'message' => 'Nom d\'utilisateur requis'
            ]);
        }

        // Vérifier si le nom d'utilisateur existe (via name field)
        $exists = User::where('name', $username)->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? '❌ Ce nom d\'utilisateur existe déjà' : '✅ Nom disponible'
        ]);
    }

    /**
     * Valider un mot de passe
     */
    public function validatePassword(Request $request): JsonResponse
    {
        $password = $request->input('password');

        if (!$password) {
            return response()->json([
                'valid' => false,
                'strength' => 'weak',
                'message' => 'Mot de passe requis'
            ]);
        }

        $strength = $this->getPasswordStrength($password);

        return response()->json([
            'valid' => strlen($password) >= 8,
            'strength' => $strength,
            'message' => $this->getPasswordMessage($password, $strength)
        ]);
    }

    /**
     * Get password strength level
     */
    private function getPasswordStrength(string $password): string
    {
        $score = 0;

        // Length
        if (strlen($password) >= 8) $score++;
        if (strlen($password) >= 12) $score++;

        // Numbers
        if (preg_match('/[0-9]/', $password)) $score++;

        // Lowercase
        if (preg_match('/[a-z]/', $password)) $score++;

        // Uppercase
        if (preg_match('/[A-Z]/', $password)) $score++;

        // Special characters
        if (preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) $score++;

        return match(true) {
            $score <= 2 => 'weak',
            $score <= 4 => 'medium',
            default => 'strong'
        };
    }

    /**
     * Get password validation message
     */
    private function getPasswordMessage(string $password, string $strength): string
    {
        if (strlen($password) < 8) {
            return '❌ Le mot de passe doit contenir au moins 8 caractères';
        }

        $messages = [
            'weak' => '⚠️ Mot de passe faible - Ajoutez des majuscules, chiffres ou symboles',
            'medium' => '✓ Mot de passe acceptable - Mais vous pouvez faire mieux',
            'strong' => '✅ Mot de passe fort'
        ];

        return $messages[$strength] ?? '✓ Mot de passe valide';
    }
}
