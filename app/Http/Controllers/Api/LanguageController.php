<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Constant;
use App\Http\Controllers\Controller;

final class LanguageController extends Controller
{
    public function index(string $language)
    {
        if (! isset(Constant::AVAILABLE_LANGUAGES()[$language])) {
            return abort(400, 'Invalid language');
        }

        return response()->file(
            lang_path("{$language}.json"),
        );
    }
}
