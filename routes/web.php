<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentCategoryController;

// Halaman publik
Route::get('/', function () {
    return view('welcome');
});

// Refresh captcha publik (tidak perlu login)
Route::get('captcha-refresh', function () {
    return response()->json(['captcha' => captcha_img('flat')]);
});

// Semua route berikut wajib login
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Dokumen
    Route::prefix('dokumen')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/dokumen-baru', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');

        // Kategori Dokumen
        Route::get('/kategori', [DocumentCategoryController::class, 'index'])->name('category.categories');
        Route::post('/kategori', [DocumentCategoryController::class, 'store'])->name('categories.store');
        Route::put('/kategori/{category}', [DocumentCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/kategori/{id}', [DocumentCategoryController::class, 'destroy'])->name('categories.destroy');

        // Tipe Dokumen
        Route::get('/tipe', [DocumentTypeController::class, 'index'])->name('type.types');
        Route::post('/tipe', [DocumentTypeController::class, 'store'])->name('types.store');
        Route::put('/tipe/{documentType}', [DocumentTypeController::class, 'update'])->name('types.update');
        Route::delete('/tipe/{id}', [DocumentTypeController::class, 'destroy'])->name('types.destroy');

        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
    });

    

    // Bidang / Unit
    Route::prefix('bidang')->name('bidang.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::post('/', [UnitController::class, 'store'])->name('store');
        Route::put('/{id}', [UnitController::class, 'update'])->name('update');
        Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy');
    });

    // Pengguna
    Route::prefix('pengguna')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/pengguna-baru', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (login, register, password, etc.)
require __DIR__.'/auth.php';