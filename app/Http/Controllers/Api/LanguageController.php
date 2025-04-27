<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public $availableLanguages = [
        'en',
        'pt',
    ];

    public function index(string $language)
    {
        if(! in_array($language, $this->availableLanguages)) {
            return abort(400);
        }

        return response()->file(
            lang_path("{$language}.json"),
        );
    }
}
