<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TransactionInController;
use App\Http\Controllers\TransactionOutController;
use App\Http\Controllers\WalletController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/documentacao', function () {
    return Inertia::render('Docs');
});

Route::redirect('/convite', '/register');

Route::middleware('auth')->group(function () {
    Route::get('/painel', [DashboardController::class, 'index'])->name('dashboard');

    // wallets
    // Route::get('/carteiras', [WalletController::class, 'index'])->name('wallets.index');
    Route::post('/carteiras', [WalletController::class, 'store'])->name('wallets.store');
    Route::delete('/carteiras/{wallet}', [WalletController::class, 'destroy'])->name('wallets.destroy');

    Route::put('/trocar-carteira', [WalletController::class, 'switch'])->name('wallets.switch');

    // transaction in
    Route::get('/entradas', [TransactionInController::class, 'index'])->name('transactions.in.index');
    Route::post('/entradas', [TransactionInController::class, 'store'])->name('transactions.in.store');
    Route::post('/entradas/importar', [TransactionInController::class, 'import'])->name('transactions.in.import');
    Route::delete('/entradas/{transaction}', [TransactionInController::class, 'destroy'])->name('transactions.in.destroy');

    // transaction out
    Route::get('/saidas', [TransactionOutController::class, 'index'])->name('transactions.out.index');
    Route::post('/saidas', [TransactionOutController::class, 'store'])->name('transactions.out.store');
    Route::post('/saidas/importar', [TransactionOutController::class, 'import'])->name('transactions.out.import');
    Route::delete('/saidas/{transaction}', [TransactionOutController::class, 'destroy'])->name('transactions.out.destroy');

    Route::get('/metas', [GoalController::class, 'index'])->name('goals.index');
    Route::post('/metas', [GoalController::class, 'store'])->name('goals.store');
    Route::put('/metas/{goal}', [GoalController::class, 'update'])->name('goals.update');
    Route::delete('/metas/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');

    Route::get('/orcamentos', [BudgetController::class, 'index'])->name('budgets.index');
    Route::post('/orcamentos', [BudgetController::class, 'store'])->name('budgets.store');
    Route::put('/orcamentos/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/orcamentos/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    Route::get('/contas', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/contas', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/contas/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/contas/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    Route::get('/cartoes', [CardController::class, 'index'])->name('cards.index');
    Route::post('/cartoes', [CardController::class, 'store'])->name('cards.store');
    Route::put('/cartoes/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::delete('/cartoes/{card}', [CardController::class, 'destroy'])->name('cards.destroy');

    // Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/minha-conta', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/minha-conta', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/minha-conta', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/plano', [StripeController::class, 'billing'])->name('subscription.billing');
    Route::get('/plano/checkout', [StripeController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/plano/checkout-success', [StripeController::class, 'checkoutSuccess'])->name('subscription.checkout-success');
    Route::get('/plano/checkout-cancel', [StripeController::class, 'checkoutCancel'])->name('subscription.checkout-cancel');

    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::post('/suporte', [SupportController::class, 'store'])->name('support.store');
});

require __DIR__.'/auth.php';
