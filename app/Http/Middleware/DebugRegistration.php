<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->path() === 'register' && $request->method() === 'POST') {
            Log::info('🔍 DEBUG REGISTRATION');
            Log::info('Methods: ' . $request->method());
            Log::info('All inputs: ' . json_encode($request->all(), JSON_PRETTY_PRINT));
            Log::info('Role: ' . $request->role);
            Log::info('Vendor fields submitted:' . json_encode([
                'shop_name' => $request->shop_name,
                'phone' => $request->phone,
                'address' => $request->address,
            ], JSON_PRETTY_PRINT));
        }

        return $next($request);
    }
}
