<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (! $request->user()?->hasStripeId()) {
        //     return inertia()->location(route('subscription.checkout'));
        // }

        // if (! $request->user()?->subscribed(config('cashier.product'))) {
        //     return inertia()->location(route('subscription.checkout'));
        // }

        return $next($request);
    }
}
