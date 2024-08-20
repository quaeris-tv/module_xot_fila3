<?php

declare(strict_types=1);
/**
 * @see https://laravel.com/docs/11.x/urls#default-values
 */

namespace Modules\Xot\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultLocaleForUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $lang = $request->user()?->locale ?? app()->getLocale();
        // URL::defaults(['locale' => $request->user()->locale]);
        URL::defaults(['lang' => $lang]);

        return $next($request);
    }
}
