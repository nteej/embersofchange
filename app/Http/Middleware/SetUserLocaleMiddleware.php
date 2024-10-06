<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\MicrosoftTranslatorService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
//use GeoIP;
class SetUserLocaleMiddleware
{
   
    protected $translatorService;

    public function __construct(MicrosoftTranslatorService $translatorService)
    {
        $this->translatorService = $translatorService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Detect the user's language from request headers or any other method
        $userLanguage = $request->header('Accept-Language');

        // Get user's IP address
        $ip = $request->ip();//'193.166.125.254';//'103.207.170.147';//'193.166.125.254';fin//$request->ip();
        //dd($ip);
        //$loc = GeoIP::getLocation($ip);
        //dd($loc);
        // Use a geolocation API to get country info
        $api = "https://api.geoapify.com/v1/ipinfo?apiKey=736df5066d3f4b41914c30f225a89e61&ip=$ip";
        //dd($api);
        $locationData = file_get_contents($api);
        
        $location = json_decode($locationData);
        //dd($location->country->languages[0]->iso_code);
        $countryCode = $location->country ?? 'US'; // Default to 'US' if country is not found

        // Fallback to a default language if no user language is detected
        if (!$userLanguage) {
            $userLanguage = 'en'; // default language
        }
        $userLanguage = $location->country->languages[0]->iso_code;
        // Call the Microsoft Translator service to check if the language is supported
        $supportedLanguages = $this->translatorService->getSupportedLanguages();
        
        // Extract supported language codes
        $languageCodes = array_column($supportedLanguages, 'code');
 
        // Match the user's language against supported language codes
        $locale = in_array($userLanguage, $languageCodes) ? $userLanguage : 'en';
        //dd($locale);
        //$locale = $languageMap[$countryCode] ?? config('app.fallback_locale'); // Fallback if not mapped
        // Set the application locale
        App::setLocale($locale);
        
        //dd(App::getLocale());
        
        // Proceed with the request
        return $next($request);
    }
}
