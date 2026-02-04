<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk bypass authentication di development.
 * 
 * ⚠️ PERINGATAN: Middleware ini HANYA untuk development!
 * PASTIKAN untuk menonaktifkannya sebelum deployment ke production!
 * 
 * Cara mengaktifkan:
 *   Set BYPASS_AUTH=true di file .env
 * 
 * Cara menonaktifkan:
 *   Set BYPASS_AUTH=false atau hapus dari .env
 */
class BypassAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah bypass diaktifkan via .env
        if (config('app.bypass_auth', false)) {
            // Auto-login sebagai admin (user pertama)
            $admin = User::first();
            
            if ($admin && !Auth::check()) {
                Auth::login($admin);
            }
        }

        return $next($request);
    }
}
