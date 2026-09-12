<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KetidakhadiranController as AdminKetidakhadiranController;
use App\Http\Controllers\Admin\LaporanExportController;
use App\Http\Controllers\Admin\LokasiPresensiController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\KetidakhadiranController as PegawaiKetidakhadiranController;
use App\Http\Controllers\Pegawai\PresensiController as PegawaiPresensiController;
use App\Http\Controllers\Pegawai\RiwayatController;
use Illuminate\Support\Facades\Route;

// ===================== AUTH =====================
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ===================== ADMIN =====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('jabatan', JabatanController::class)->except(['show']);
    Route::resource('lokasi-presensi', LokasiPresensiController::class)->except(['show']);
    Route::resource('pegawai', PegawaiController::class)->except(['show']);

    Route::get('ketidakhadiran', [AdminKetidakhadiranController::class, 'index'])->name('ketidakhadiran.index');
    Route::get('ketidakhadiran/{ketidakhadiran}/download', [AdminKetidakhadiranController::class, 'download'])->name('ketidakhadiran.download');
    Route::patch('ketidakhadiran/{ketidakhadiran}/approve', [AdminKetidakhadiranController::class, 'approve'])->name('ketidakhadiran.approve');
    Route::patch('ketidakhadiran/{ketidakhadiran}/reject', [AdminKetidakhadiranController::class, 'reject'])->name('ketidakhadiran.reject');

    Route::get('presensi/mingguan', [AdminPresensiController::class, 'mingguan'])->name('presensi.mingguan');
    Route::get('presensi/bulanan', [AdminPresensiController::class, 'bulanan'])->name('presensi.bulanan');
    Route::get('presensi', [AdminPresensiController::class, 'index'])->name('presensi.index');
    Route::get('presensi/export', [LaporanExportController::class, 'exportPdf'])->name('presensi.export');
});

// ===================== PEGAWAI =====================
Route::prefix('pegawai')->name('pegawai.')->middleware(['auth', 'pegawai'])->group(function () {
    Route::get('dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
    Route::get('presensi', [PegawaiPresensiController::class, 'smartRedirect'])->name('presensi.index');

    Route::get('presensi/masuk', [PegawaiPresensiController::class, 'clockInForm'])->name('presensi.clock-in-form');
    Route::post('presensi/masuk', [PegawaiPresensiController::class, 'clockIn'])->name('presensi.clock-in');
    Route::get('presensi/pulang', [PegawaiPresensiController::class, 'clockOutForm'])->name('presensi.clock-out-form');
    Route::post('presensi/pulang', [PegawaiPresensiController::class, 'clockOut'])->name('presensi.clock-out');

    Route::get('ketidakhadiran/create', [PegawaiKetidakhadiranController::class, 'create'])->name('ketidakhadiran.create');
    Route::post('ketidakhadiran', [PegawaiKetidakhadiranController::class, 'store'])->name('ketidakhadiran.store');
    Route::get('ketidakhadiran/riwayat', [PegawaiKetidakhadiranController::class, 'riwayat'])->name('ketidakhadiran.riwayat');
    Route::get('ketidakhadiran/{ketidakhadiran}/download', [PegawaiKetidakhadiranController::class, 'download'])->name('ketidakhadiran.download');

    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});

Route::get('/', function () {
    return redirect()->route('login');
});
