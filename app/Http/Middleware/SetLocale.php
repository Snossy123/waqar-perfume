<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $locale = $request->header('Accept-Language') 
                 ?: $request->query('lang')
                 ?: config('app.locale');
        
        if (in_array($locale, config('app.available_locales', ['en', 'ar']))) {
            app()->setLocale($locale);
        } else {
            app()->setLocale(config('app.fallback_locale', 'en'));
        }
        
        $response = $next($request);
        $response->headers->set('Content-Language', app()->getLocale());
        
        return $response;
    }
}
