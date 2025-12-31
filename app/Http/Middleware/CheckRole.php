<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect('login');
    }

    // Mengecek apakah role user saat ini ada dalam daftar role yang diizinkan di route
    if (in_array(auth()->user()->role, $roles)) {
        return $next($request);
    }

    // Jika tidak punya akses, lempar ke halaman 403 (Forbidden)
    abort(403, 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.');
}
}