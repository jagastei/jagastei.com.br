<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/language/{language}', [LanguageController::class, 'index'])->name('language.index');

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/painel', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');

    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::post('/suporte', [SupportController::class, 'store'])->name('support.store');
});

require __DIR__.'/webhooks.php';
