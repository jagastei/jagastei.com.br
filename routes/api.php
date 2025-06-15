<?php

declare(strict_types=1);

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/language/{language}', [LanguageController::class, 'index'])->name('language.index');

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/painel', [DashboardController::class, 'index'])->name('dashboard');

    Route::put('/trocar-carteira', [WalletController::class, 'switch'])->name('wallets.switch');

    Route::get('/movimentacoes', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/movimentacoes', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/movimentacoes/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::post('/suporte', [SupportController::class, 'store'])->name('support.store');
});

require __DIR__.'/webhooks.php';
