<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/language/{language}', [LanguageController::class, 'index'])->name('language.index');

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
});

require __DIR__.'/webhooks.php';
