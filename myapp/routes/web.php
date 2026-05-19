<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/artikel/{id}', [HomeController::class, 'show']);

// Auth Routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);

// Admin Routes (Protected by Auth)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    
    // Artikel CRUD
    Route::get('/kelola_artikel', [AdminController::class, 'kelolaArtikel']);
    Route::post('/artikel', [AdminController::class, 'storeArtikel']);
    Route::get('/artikel/{id}/edit', [AdminController::class, 'editArtikel']);
    Route::put('/artikel/{id}', [AdminController::class, 'updateArtikel']);
    Route::delete('/artikel/{id}', [AdminController::class, 'destroyArtikel']);

    // FAQ CRUD
    Route::get('/kelola_faq', [AdminController::class, 'kelolaFaq']);
    Route::post('/faq', [AdminController::class, 'storeFaq']);
    Route::delete('/faq/{id}', [AdminController::class, 'destroyFaq']);
});
