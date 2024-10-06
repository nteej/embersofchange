<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
class GeoLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   
    public function handle(Request $request, Closure $next): Response
    {
        // Get user's IP address
        $ip = $request->ip();

        // Use a geolocation API to get country info
        $locationData = file_get_contents("http://ipinfo.io/{$ip}/json");
        $location = json_decode($locationData);
        $countryCode = $location->country ?? 'US'; // Default to 'US' if country is not found

        
        // Map country to locale
        $languageMap = [
            'US' => 'en',
            'FR' => 'fr',
            'ES' => 'es',
            'DE' => 'de',
            'FI' => 'fi'
            // Add more mappings as needed
        ];

        $locale = $languageMap[$countryCode] ?? config('app.fallback_locale'); // Fallback if not mapped

        // Set the locale in Laravel
        App::setLocale($locale);

        // Continue processing the request
        return $next($request);
    }
}
