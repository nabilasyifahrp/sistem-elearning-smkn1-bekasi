<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('loginError', 'Silahkan login terlebih dahulu.');
        }

        if ($user->role !== $role) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'guru') {
                return redirect()->route('guru.dashboard');
            } elseif ($user->role === 'siswa') {
                return redirect()->route('siswa.dashboard');
            } else {
                abort(403, 'Role tidak dikenali');
            }
        }

        return $next($request);
    }
}
