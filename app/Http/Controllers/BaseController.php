<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MicrosoftTranslatorService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
class BaseController extends Controller
{
    protected $translatorService;

    public function __construct(MicrosoftTranslatorService $translatorService)
    {
        $this->translatorService = $translatorService;
    }

    public function index()
    {
        // Fetch the array of supported languages
       $languages = $this->translatorService->getSupportedLanguages();
        // Pass the languages array to your view
        return view('index', compact('languages'));
    }
    public function updateLocale(Request $request)
    {
        // Validate the locale
       // $request->validate([
        //    'locale' => 'required|string|in:en,fr,es', // Add more languages as needed
       // ]);
       // Set the selected locale in session
       //dd($request->locale);
       Session::put('locale', $request->locale);
       //dd($request->locale);
       App::setLocale($request->locale);
       //dd(App::getLocale());
       \Illuminate\Support\Facades\View::composer('*', function ($view) {
        $view->with('translationHelper', new \App\Helpers\TranslationHelper(new \App\Services\MicrosoftTranslatorService));
    });
        

        // Optionally return a success response
        return response()->json(['message' => 'Locale updated successfully.']);
    }
}
