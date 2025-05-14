<?php

use App\Http\Controllers\DatasController;
use App\Http\Controllers\SpjController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect(url('/admin'));
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/export-espeje', [SpjController::class, 'export'])->name('export-espeje');
    Route::get('/download-template/{indikator_id}/{waktu_mulai}/{waktu_selesai}', [DatasController::class, 'download_template'])->name('download.template');
    Route::post('/import-datas', [DatasController::class, 'import'])->name('import.datas');
});