<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/artikel/{id}', [HomeController::class, 'show']);
Route::get('/ajax/artikel', [HomeController::class, 'getArtikelAjax']);
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/customer-stories', [HomeController::class, 'customerStories'])->name('customer-stories');
Route::get('/customer-stories/{id}', [HomeController::class, 'showCustomerStory'])->name('customer-story');

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

    // Customer Stories CRUD
    Route::get('/kelola_customer_stories', [AdminController::class, 'kelolaCustomerStories']);
    Route::post('/customer_stories', [AdminController::class, 'storeCustomerStories']);
    Route::get('/customer_stories/{id}/edit', [AdminController::class, 'editCustomerStories']);
    Route::put('/customer_stories/{id}', [AdminController::class, 'updateCustomerStories']);
    Route::delete('/customer_stories/{id}', [AdminController::class, 'destroyCustomerStories']);
});
