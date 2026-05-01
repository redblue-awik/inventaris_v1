<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;

// Route resource untuk Barang (otomatis menyediakan index, create, store, edit, update, destroy)
Route::resource('barang', BarangController::class);

// Route untuk Peminjaman
Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');

// Route untuk Laporan
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

// Halaman utama redirect ke barang
Route::get('/', function () {
    return redirect()->route('barang.index');
});

// Route tes (opsional)
Route::get('/tes', function () {
    return "OK";
});