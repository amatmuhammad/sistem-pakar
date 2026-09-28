<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\AturanCfController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosaCfController;
use App\Http\Controllers\DiagnosaCbrController;
use App\Http\Controllers\PenyakitHamaController;
use App\Http\Controllers\PerbandinganController;

/* ===================== AUTENTIKASI & AKSES TAMU ===================== */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/guest-access', [AuthController::class, 'guestAccess'])->name('guest.access');
Route::get('/guest-logout', [AuthController::class, 'guestLogout'])->name('guest.logout');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    if (session('is_guest')) {
        return redirect()->route('diagnosa-cbr.form');
    }
    return redirect()->route('login');
});

/* ===================== DIAGNOSA (AUTH ATAU GUEST MODE) ===================== */
Route::group(['middleware' => function ($request, $next) {
    if (auth()->check() || session('is_guest')) {
        return $next($request);
    }
    return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu atau gunakan Akses Tamu.');
}], function () {
    /* DIAGNOSA CBR (Form, Proses & Hapus Sesi) */
    Route::get('/diagnosa-cbr', [DiagnosaCbrController::class, 'form'])->name('diagnosa-cbr.form');
    Route::post('/diagnosa-cbr/proses', [DiagnosaCbrController::class, 'proses'])->name('diagnosa-cbr.proses');
    Route::post('/diagnosa-cbr/clear-session', [DiagnosaCbrController::class, 'clearSession'])->name('diagnosa-cbr.clear-session');

    /* METODE CERTAINTY FACTOR (Form, Proses & Hapus Sesi) */
    Route::get('/diagnosa-cf', [DiagnosaCfController::class, 'form'])->name('diagnosa-cf.form');
    Route::post('/diagnosa-cf/proses', [DiagnosaCfController::class, 'proses'])->name('diagnosa-cf.proses');
    Route::post('/diagnosa-cf/clear-session', [DiagnosaCfController::class, 'clearSession'])->name('diagnosa-cf.clear-session');
});

/* ===================== AREA ADMIN (KHUSUS AUTH / USER TERDAFTAR) ===================== */
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* MASTER DATA GEJALA */
    Route::get('/gejala', [GejalaController::class, 'index'])->name('gejala.index');
    Route::post('/gejala', [GejalaController::class, 'store'])->name('gejala.store');
    Route::put('/gejala/{gejala}', [GejalaController::class, 'update'])->name('gejala.update');
    Route::delete('/gejala/{gejala}', [GejalaController::class, 'destroy'])->name('gejala.destroy');

    /* MASTER DATA PENYAKIT & HAMA */
    Route::get('/penyakit', [PenyakitHamaController::class, 'index'])->name('penyakit.index');
    Route::post('/penyakit', [PenyakitHamaController::class, 'store'])->name('penyakit.store');
    Route::put('/penyakit/{penyakit}', [PenyakitHamaController::class, 'update'])->name('penyakit.update');
    Route::delete('/penyakit/{penyakit}', [PenyakitHamaController::class, 'destroy'])->name('penyakit.destroy');

    /* MASTER DATA ATURAN CERTAINTY FACTOR */
    Route::get('/aturan-cf', [AturanCfController::class, 'index'])->name('aturan-cf.index');
    Route::post('/aturan-cf', [AturanCfController::class, 'store'])->name('aturan-cf.store');
    Route::put('/aturan-cf/{id}', [AturanCfController::class, 'update'])->name('aturan-cf.update');
    Route::delete('/aturan-cf/{aturanCf}', [AturanCfController::class, 'destroy'])->name('aturan-cf.destroy');

    /* RIWAYAT & DETAIL KASUS CBR */
    Route::get('/diagnosa-cbr/kasus', [DiagnosaCbrController::class, 'kasus'])->name('diagnosa-cbr.kasus');
    Route::get('/diagnosa-cbr/hasil', [DiagnosaCbrController::class, 'hasil'])->name('diagnosa-cbr.hasil');


    /* HASIL DIAGNOSA CF */
    Route::get('/diagnosa-cf/hasil', [DiagnosaCfController::class, 'hasil'])->name('diagnosa-cf.hasil');

    Route::get('/perbandingan-akurasi', [PerbandinganController::class, 'index'])->name('perbandingan.akurasi');
});
