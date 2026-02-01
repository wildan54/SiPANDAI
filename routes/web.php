<?php

use App\Http\Controllers\Administrator\AccessLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrator\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Administrator\DocumentController;
use App\Http\Controllers\Administrator\UnitController;
use App\Http\Controllers\Administrator\DocumentTypeController;
use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\Administrator\DocumentCategoryController;
use App\Http\Controllers\Publik\DocumentController as PublicDocumentController;

// Refresh captcha publik (tidak perlu login)
Route::get('captcha-refresh', function () {
    return response()->json(['captcha' => captcha_img('flat')]);
});

// Semua route berikut wajib login
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dokumen
    Route::prefix('admin/dokumen')->name('documents.')->group(function () {
        // Dokumen utama
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/disetujui', [DocumentController::class, 'approvedDocuments'])->name('approved');
        Route::get('/ditolak', [DocumentController::class, 'rejectedDocuments'])->name('rejected');
        Route::get('/tersubmit', [DocumentController::class, 'submittedDocuments'])->name('submitted');
        Route::get('/draf', [DocumentController::class, 'draftDocuments'])->name('drafts');

        Route::get('/dokumen-baru', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');

        // Cek slug harus sebelum catch-all
        Route::get('/check-slug', [DocumentController::class, 'checkSlug'])->name('checkSlug');

        // Kategori Dokumen
        Route::get('/kategori', [DocumentCategoryController::class, 'index'])->name('categories.index');
        Route::post('/kategori', [DocumentCategoryController::class, 'store'])->name('categories.store');
        Route::put('/kategori/{category}', [DocumentCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/kategori/{category}', [DocumentCategoryController::class, 'destroy'])->name('categories.destroy');

        // Tipe Dokumen
        Route::get('/tipe', [DocumentTypeController::class, 'index'])->name('types.index');
        Route::post('/tipe', [DocumentTypeController::class, 'store'])->name('types.store');
        Route::put('/tipe/{documentType}', [DocumentTypeController::class, 'update'])->name('types.update');
        Route::delete('/tipe/{documentType}', [DocumentTypeController::class, 'destroy'])->name('types.destroy');

        // ⚠️ Route catch-all HARUS di paling bawah
        Route::get('/{document}/view',[DocumentController::class, 'view'])->name('view');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        Route::post('{document}/submit',  [DocumentController::class, 'submit']);
        Route::post('{document}/approve', [DocumentController::class, 'approve']);
        Route::post('{document}/reject',  [DocumentController::class, 'reject']);

        Route::post('/bulk-approve', [DocumentController::class, 'bulkApprove'])
        ->name('bulkApprove');

        Route::post('/bulk-submit', [DocumentController::class, 'bulkSubmit'])
        ->name('bulkSubmit');

        Route::post('/bulk-reject', [DocumentController::class, 'bulkReject'])
        ->name('bulkReject');

    });

    // Bidang / Unit
    Route::prefix('admin/bidang')->name('bidang.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::post('/', [UnitController::class, 'store'])->name('store');
        Route::put('/{id}', [UnitController::class, 'update'])->name('update');
        Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy');
    });

    // Pengguna
    Route::prefix('admin/pengguna')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/pengguna-baru', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/statistik', [AccessLogController::class, 'index'])->name('access_logs.index');
        Route::get('/statistik/{user}', [AccessLogController::class, 'show'])->name('access_logs.detail');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ====================================================
// 🔓 Route untuk publik (tidak perlu login)
// ====================================================

// Halaman utama publik (daftar dokumen + filter)
Route::get('/home', [PublicDocumentController::class, 'index'])->name('public.home');
Route::get('/', [PublicDocumentController::class, 'landingPage'])->name('public.landing-page');

// Halaman publik dokumen
Route::prefix('dokumen')->name('public.documents.')->group(function () {
    // Download

    Route::get('/dokumen/{slug}/download-file', [PublicDocumentController::class, 'downloadFileUpload'])->name('download.file');

    Route::get('/dokumen/{slug}/download-embed', [PublicDocumentController::class, 'downloadEmbed'])->name('download.embed');

    // By Type
    Route::get('/type/{slug}', [PublicDocumentController::class, 'types'])->name('types');

    // By Category
    Route::get('/category/{slug}', [PublicDocumentController::class, 'categories'])->name('categories');

    // By Unit
    Route::get('/unit/{slug}', [PublicDocumentController::class, 'units'])->name('units');

    // Detail dokumen
    Route::get('/{slug}', [PublicDocumentController::class, 'show'])->name('show');
});

   Route::get('/monitoring', function () {
        return view('monitoring');
    });


// Auth routes (login, register, password, etc.)
require __DIR__.'/auth.php';