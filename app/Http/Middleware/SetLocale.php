<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Constant;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->user()?->language ?? Constant::DEFAULT_LANGUAGE;

        App::setLocale($locale);

        // Carbon reads app locale
        // Carbon::setLocale('pt');

        return $next($request);
    }
}
