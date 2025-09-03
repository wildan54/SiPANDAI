<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\UserController;
use Mews\Captcha\Captcha;

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
Route::prefix('documents')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::get('/kategori', [DocumentCategoryController::class, 'kategori'])->name('documents.kategori');
    Route::get('/tipe', [DocumentTypeController::class, 'tipe'])->name('documents.tipe');
});

// Bidang
Route::get('/bidang', [BidangController::class, 'index'])->name('bidang.index');

// Pengguna
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
