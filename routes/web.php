<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\AturanCfController;
use App\Http\Controllers\DiagnosaCfController;
use App\Http\Controllers\DiagnosaCbrController;
use App\Http\Controllers\PenyakitHamaController;

Route::get('/', function () {
    return view('layouts.app');
});

/* ===================== MASTER DATA GEJALA ===================== */
    Route::get('/gejala', [GejalaController::class, 'index'])->name('gejala.index');
    Route::post('/gejala', [GejalaController::class, 'store'])->name('gejala.store');
    Route::put('/gejala/{gejala}', [GejalaController::class, 'update'])->name('gejala.update');
    Route::delete('/gejala/{gejala}', [GejalaController::class, 'destroy'])->name('gejala.destroy');

    /* ===================== MASTER DATA PENYAKIT & HAMA ===================== */
    Route::get('/penyakit', [PenyakitHamaController::class, 'index'])->name('penyakit.index');
    Route::post('/penyakit', [PenyakitHamaController::class, 'store'])->name('penyakit.store');
    Route::put('/penyakit/{penyakit}', [PenyakitHamaController::class, 'update'])->name('penyakit.update');
    Route::delete('/penyakit/{penyakit}', [PenyakitHamaController::class, 'destroy'])->name('penyakit.destroy');

    /* ===================== MASTER DATA ATURAN CERTAINTY FACTOR ===================== */
    Route::get('/aturan-cf', [AturanCfController::class, 'index'])->name('aturan-cf.index');
    Route::post('/aturan-cf', [AturanCfController::class, 'store'])->name('aturan-cf.store');
    Route::put('/aturan-cf/{id}', [AturanCfController::class, 'update'])->name('aturan-cf.update');
    Route::delete('/aturan-cf/{aturanCf}', [AturanCfController::class, 'destroy'])->name('aturan-cf.destroy');

    /* ===================== DIAGNOSA CBR ===================== */
    Route::get('/diagnosa-cbr', [DiagnosaCbrController::class, 'form'])->name('diagnosa-cbr.form');
    Route::post('/diagnosa-cbr/proses', [DiagnosaCbrController::class, 'proses'])->name('diagnosa-cbr.proses');

    /* ===================== DIAGNOSA CERTAINTY FACTOR ===================== */
    Route::get('/diagnosa-cf', [DiagnosaCfController::class, 'index'])->name('diagnosa-cf.index');
    Route::post('/diagnosa-cf/proses', [DiagnosaCfController::class, 'proses'])->name('diagnosa-cf.proses');