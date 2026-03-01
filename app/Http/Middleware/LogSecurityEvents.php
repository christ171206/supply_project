<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\SecurityLog;
use App\Services\DeviceParserService;

class LogSecurityEvents
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log login events only after successful response
        if (Auth::check() && $request->path() === 'login' && $request->isMethod('post')) {
            $this->logLogin($request, 'success');
        }

        // Log failed login attempts
        if ($request->path() === 'login' && $request->isMethod('post') && !Auth::check()) {
            $this->logLogin($request, 'failed');
        }

        return $response;
    }

    /**
     * Log login event
     */
    private function logLogin(Request $request, string $status): void
    {
        $userId = Auth::id();

        // For failed login, extract email from request
        if ($status === 'failed') {
            $email = $request->input('email');
            // Find user by email to log attempt
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                $userId = $user->id;
            } else {
                return; // Don't log fake attempts
            }
        }

        if (!$userId) {
            return;
        }

        $deviceInfo = DeviceParserService::parse($request->userAgent());
        $ipAddress = $request->ip();

        SecurityLog::create([
            'user_id' => $userId,
            'event_type' => $status === 'success' ? 'login' : 'failed_login',
            'status' => $status,
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'browser' => $deviceInfo['browser'],
            'platform' => $deviceInfo['platform'],
            'device_type' => $deviceInfo['device_type'],
            'message' => $status === 'success' ? "Connexion réussie depuis {$deviceInfo['platform']}" : "Tentative échouée depuis {$ipAddress}",
        ]);
    }
}
