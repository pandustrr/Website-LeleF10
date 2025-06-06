<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProduksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BibitController;
use App\Http\Controllers\PakanController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\SiklusController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\AnalisisPrediksiController;
use App\Http\Controllers\ProfileController;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('produksi');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/update/{field}', [ProfileController::class, 'update'])
            ->where('field', 'username|password|tempat_tanggal_lahir|alamat|nomor_telepon')
            ->name('profile.update');
    });

    Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi');

    Route::prefix('bibit')->group(function () {
        Route::post('/store', [BibitController::class, 'store'])->name('bibit.store');
        Route::get('/{id}/edit', [BibitController::class, 'edit'])->name('bibit.edit');
        Route::put('/{id}', [BibitController::class, 'update'])->name('bibit.update');
        Route::delete('/{id}', [BibitController::class, 'destroy'])->name('bibit.destroy');
    });
    Route::prefix('pakan')->group(function () {
        Route::post('/store', [PakanController::class, 'store'])->name('pakan.store');
        Route::get('/{id}/edit', [PakanController::class, 'edit'])->name('pakan.edit');
        Route::put('/{id}', [PakanController::class, 'update'])->name('pakan.update');
        Route::delete('/{id}', [PakanController::class, 'destroy'])->name('pakan.destroy');
    });

    Route::prefix('panen')->group(function () {
        Route::post('/store', [PanenController::class, 'store'])->name('panen.store');
        Route::get('/{id}/edit', [PanenController::class, 'edit'])->name('panen.edit');
        Route::put('/{id}', [PanenController::class, 'update'])->name('panen.update');
        Route::delete('/{id}', [PanenController::class, 'destroy'])->name('panen.destroy');

        // Simulasi
        Route::post('/simulasi', [PanenController::class, 'simulasi'])->name('panen.simulasi');
        Route::put('/simulasi-harga', [PanenController::class, 'simulasiHarga'])->name('panen.simulasi-harga');
    });

    Route::prefix('siklus')->group(function () {
        Route::post('/', [SiklusController::class, 'store'])->name('siklus.store');
        Route::put('/{siklus}', [SiklusController::class, 'update'])->name('siklus.update');
        Route::delete('/{siklus}', [SiklusController::class, 'destroy'])->name('siklus.destroy');
    });

    // Keuangan Routes - hanya untuk admin
    Route::middleware(['can:admin'])->prefix('keuangan')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('keuangan');
        Route::get('/refresh-predictions', [KeuanganController::class, 'refreshPredictions']);
    });

    Route::prefix('analisisPrediksi')->group(function () {
        Route::get('/', [AnalisisPrediksiController::class, 'index'])->name('analisisPrediksi');
    });
});
