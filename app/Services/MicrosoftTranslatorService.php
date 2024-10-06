<?php

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
class MicrosoftTranslatorService
{
    protected $rapidApiKey;
    protected $endpoint = 'https://microsoft-translator-text.p.rapidapi.com';
    protected $translator = "https://microsoft-translator-text.p.rapidapi.com/translate";
    protected $languageArray = [];
    public function __construct()
    {
        $this->client = new Client();
        $this->rapidApiKey = config('services.rapidapi.key');
    }

    /**
     * Fetch supported languages from Microsoft Translator API.
     *
     * @return array
     */
    public function getSupportedLanguages()
    {
        // Send a GET request to the API to fetch the supported languages
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->rapidApiKey,
            'X-RapidAPI-Host' => 'microsoft-translator-text.p.rapidapi.com',
        ])->get($this->endpoint. '/languages', [
            'api-version' => '3.0',
            'scope' => 'translation'
        ]);

        // Decode the JSON response to an array
        $data = json_decode($response->body(), true);

        //dd($data);

        // Extract the 'translation' section which contains the supported languages
        $languages = $data['translation'] ?? [];

        // Format the languages into a simpler array
        
        foreach ($languages as $key => $value) {
            $this->languageArray[] = [
                'code' => $key,
                'name' => $value['name'],
                'nativeName' => $value['nativeName'],
            ];
        }
        
        return $this->languageArray;
    }

    public function translate($text, $targetLanguage)
    {
        $locale =  App::getLocale();
        $response = $this->client->post($this->translator.'?api-version=3.0&profanityAction=NoAction&textType=plain&to='.$locale, [
            'headers' => [
                'X-RapidAPI-Key' => $this->rapidApiKey,
                'X-RapidAPI-Host' => 'microsoft-translator-text.p.rapidapi.com',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                [
                    "Text"=> $text
                ]
            
            ],
        ]);
        //dd($response);
        $body = json_decode((string) $response->getBody(), true);
        
        return $body[0]['translations'][0]['text'] ?? $text; // Return translated text
    }

    /**
     * Translate text using Microsoft Translator API.
     *
     * @param string $text
     * @param string $targetLanguage
     * @return string
     */
    /*
    public function translate($text,$locale)
    {
        $lang = $locale;
        //dd($locale);
        //$targetLanguage= $this->languageArray[]
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->rapidApiKey,
            'X-RapidAPI-Host' => 'microsoft-translator-text.p.rapidapi.com',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->endpoint . '/translate', [
            'api-version' => '3.0',
            'to' =>  $lang, // Array of target languages
            'textType' => 'plain',
            'profanityAction'=>'NoAction',
            [
                [
                    "Text"=> "I would really like to drive your car around the block a few times."
                ]
            ]
        ]);
        //dd($response);
        // Decode the JSON response to an array
        $result = json_decode($response->body(), true);
       // dd($result);
        // Return the translated text
        return $result[0]['translations'][0]['text'] ?? $text;
    }
    */
}