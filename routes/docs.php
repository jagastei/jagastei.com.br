<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['prefix' => 'documentacao', 'as' => 'docs.'], function () {
    Route::redirect('/', '/documentacao/introducao');

    Route::get('/introducao', function () {
        return Inertia::render('Docs/Intro');
    })->name('intro');

    Route::get('/autenticacao', function () {
        return Inertia::render('Docs/Auth');
    })->name('auth');

    Route::get('/movimentacoes', function () {
        return Inertia::render('Docs/Transactions');
    })->name('transactions');

    Route::get('/movimentacoes/listar', function () {
        return Inertia::render('Docs/Transactions/List');
    })->name('transactions.list');

    Route::get('/movimentacoes/criar', function () {
        return Inertia::render('Docs/Transactions/Create');
    })->name('transactions.create');

    Route::get('/movimentacoes/excluir', function () {
        return Inertia::render('Docs/Transactions/Destroy');
    })->name('transactions.destroy');
});