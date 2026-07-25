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
            // Allow access to billing page, payment routes, and Livewire updates/assets without redirect loop.
            // Note: Livewire component updates POST back to the current route (e.g. /billing),
            // so we must check for the X-Livewire header.
            if (
                $request->routeIs('billing') ||
                $request->routeIs('logout') ||
                $request->routeIs('payment.callback') ||
                $request->routeIs('payment.return') ||
                $request->hasHeader('X-Livewire') ||
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
