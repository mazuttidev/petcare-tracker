<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesagemController;
use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

// ── Rotas de autenticação — acessíveis apenas por visitantes (guest) ───────
Route::middleware('guest')->group(function () {
    Route::get('/login',      [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login',     [AuthController::class, 'login'])->middleware('throttle:login');

    Route::get('/registrar',  [AuthController::class, 'mostrarRegistro'])->name('register');
    Route::post('/registrar', [AuthController::class, 'registrar']);
});

// Logout exige sessão autenticada (POST para proteger contra CSRF via GET)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Rotas protegidas — exigem autenticação 
Route::middleware('auth')->group(function () {
    Route::get('/',          [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('pets', PetController::class);

    // Pesagens aninhadas ao pet
    Route::resource('pets.pesagens', PesagemController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->parameters(['pesagens' => 'pesagem']);
});

// Styleguide — auxiliar de desenvolvimento, sem autenticação
Route::get('/styleguide', fn () => view('styleguide'))->name('styleguide');
