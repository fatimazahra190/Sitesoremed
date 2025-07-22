<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->status === 'inactive') {
                auth()->logout();
                return redirect()->route('login')->withErrors(['Votre compte est inactif.']);
            }
            if ($user->suspended_until && $user->suspended_until->isFuture()) {
                auth()->logout();
                return redirect()->route('login')->withErrors(['Votre compte est suspendu jusqu\'à ' . $user->suspended_until->format('d/m/Y H:i')]);
            }
        }
        if (Auth::check()) {
            if (Auth::user()->utype === 'USR') {
                return $next($request);
            } else {
                session()->flush();
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }
    }
} 