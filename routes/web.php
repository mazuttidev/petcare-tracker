<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesagemController;
use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/',          [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('pets', PetController::class);

// Pesagens aninhadas ao pet — não precisam de index/show próprios (exibidas no perfil do pet)
// ->parameters() corrige o singular gerado pelo Laravel ('pesagen') para 'pesagem'
Route::resource('pets.pesagens', PesagemController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->parameters(['pesagens' => 'pesagem']);

Route::get('/styleguide', function () {
    return view('styleguide');
})->name('styleguide');

// Auth — rotas de visualização (controllers serão implementados separadamente)
Route::get('/login',    fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');
