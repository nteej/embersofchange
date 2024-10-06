<?php

namespace App\Helpers;

use App\Services\MicrosoftTranslatorService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
class TranslationHelper
{
    protected $translatorService;

    public function __construct(MicrosoftTranslatorService $translatorService)
    {
        $this->translatorService = $translatorService;
    }

    /**
     * Get the translated string for the current locale.
     *
     * @param string $key
     * @param string $fallback
     * @return string
     */
    public function translate($key, $fallback)
    {
        // Get the current locale
        $locale = App::getLocale();
        //dd(trans()->has($key));

        // Check if the translation exists locally
        if (trans()->has($key)) {
            return trans($key);
        }

        // If not, fetch from Microsoft Translator API and cache it
        $cachedTranslation = Cache::remember("translation_{$locale}_{$key}", 60 * 60, function () use ($key, $locale, $fallback) {
           
            return $this->translatorService->translate($fallback, $locale);
        });
        //dd($key, $locale, $fallback,$cachedTranslation);
        return $cachedTranslation;
    }
}