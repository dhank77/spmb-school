<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentRequired
{
    /**
     * Handle an incoming request.
     *
     * Redirect unpaid student applicants to the billing page.
     * Only students with the 'student' role are subject to this gate.
     * Admin users bypass payment checks entirely.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('student') && ! $user->isPaid()) {
            // Allow access to billing page and Livewire/assets without redirect loop
            if (
                $request->routeIs('billing') ||
                $request->routeIs('logout') ||
                $request->is('livewire/*') ||
                $request->is('_ignition/*')
            ) {
                return $next($request);
            }

            return redirect()->route('billing');
        }

        return $next($request);
    }
}
