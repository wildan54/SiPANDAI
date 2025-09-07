<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentCategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('captcha-refresh', function () {
    return response()->json(['captcha' => captcha_img('flat')]);
});


// Dokumen
Route::prefix('dokumen')->name('documents.')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('index');
    Route::get('/create', [DocumentController::class, 'create'])->name('create');

    // Kategori Dokumen
    Route::get('/kategori', [DocumentCategoryController::class, 'index'])->name('category.categories');
    Route::post('/kateogori', [DocumentCategoryController::class, 'store'])->name('categories.store');
        Route::put('/kategori/{category}', [DocumentCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{id}', [DocumentCategoryController::class, 'destroy'])->name('categories.destroy');

    // Tipe Dokumen
    Route::get('/tipe', [DocumentTypeController::class, 'index'])->name('type.types');
    Route::post('/tipe', [DocumentTypeController::class, 'store'])->name('types.store');
    Route::put('/tipe/{documentType}', [DocumentTypeController::class, 'update'])->name('types.update');
    Route::delete('/tipe/{id}', [DocumentTypeController::class, 'destroy'])->name('types.destroy');
});

// Bidang
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
    Route::resource('pengguna', UserController::class);
});

// Pengguna
Route::prefix('pengguna')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index'); // users.index
    Route::get('/pengguna-baru', [UserController::class, 'create'])->name('create'); // users.create
    Route::post('/', [UserController::class, 'store'])->name('store'); // users.store
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit'); // users.edit
    Route::put('/{user}', [UserController::class, 'update'])->name('update'); // users.update
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); // users.destroy
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
