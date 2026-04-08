<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PeminjamanInternalController;
use App\Http\Controllers\PeminjamanEksternalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-profile', [UserController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/my-profile/update', [UserController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/manual-book', [DashboardController::class, 'manualBook'])->name('manual.book');

    // MASTER ASSET
    Route::middleware('permission:master-asset')->group(function () {
        Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::post('/assets/{id}', [AssetController::class, 'update'])->name('assets.update');
        Route::delete('/assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy');
        Route::get('/assets/get-next-code/{kategori}', [AssetController::class, 'getNextCode']);
    });

    // PEMINJAMAN INTERNAL
    Route::middleware('permission:peminjaman-internal')->group(function () {
        // Rute kustom harus berada di atas Resource agar tidak dianggap sebagai ID oleh rute resource
        Route::delete('/peminjaman-internal/{id}', [PeminjamanInternalController::class, 'destroy'])->name('peminjaman-internal.destroy');
        Route::post('peminjaman-internal/upload-foto/{detailId}', [PeminjamanInternalController::class, 'uploadFotoBarang'])->name('peminjaman-internal.upload-foto');
        Route::post('/peminjaman-internal/{id}/approve-pimpinan', [PeminjamanInternalController::class, 'approvePimpinan'])->name('peminjaman-internal.approvePimpinan');
        // Route::post('/peminjaman-internal/{id}/reject-pimpinan', [PeminjamanInternalController::class, 'rejectPimpinan'])->name('peminjaman-internal.rejectPimpinan');
        Route::post('peminjaman-internal/{id}/approve', [PeminjamanInternalController::class, 'approve'])->name('peminjaman-internal.approve');
        Route::post('peminjaman-internal/{id}/reject', [PeminjamanInternalController::class, 'reject'])->name('peminjaman-internal.reject');
        Route::post('peminjaman-internal/{id}/cancel', [PeminjamanInternalController::class, 'cancel'])->name('peminjaman-internal.cancel');
        Route::post('peminjaman-internal/{id}/update-status/{newStatus}', [PeminjamanInternalController::class, 'updateStatus'])->name('peminjaman-internal.update-status');
        Route::delete('peminjaman-internal/delete-foto/{detailId}', [PeminjamanInternalController::class, 'deleteFoto'])->name('peminjaman-internal.delete-foto');
        // untuk pengembalian
        Route::get('peminjaman-internal/{id}/kembali', [PeminjamanInternalController::class, 'returnPage'])->name('peminjaman-internal.return-page');
        Route::post('peminjaman-internal/{id}/proses-kembali', [PeminjamanInternalController::class, 'processReturn'])->name('peminjaman-internal.process-return');
        Route::post('peminjaman-internal/upload-foto-kembali/{detailId}', [PeminjamanInternalController::class, 'uploadFotoKembali'])->name('peminjaman-internal.upload-foto-kembali');
        Route::delete('peminjaman-internal/delete-foto-kembali/{detailId}', [PeminjamanInternalController::class, 'deleteFotoKembali'])->name('peminjaman-internal.delete-foto-kembali');
        // export pdf dan word
        Route::get('/peminjaman-internal/{id}/pdf', [PeminjamanInternalController::class, 'exportPdf'])->name('peminjaman-internal.pdf');
        Route::get('/peminjaman-internal/{id}/word', [PeminjamanInternalController::class, 'exportWord'])->name('peminjaman-internal.word');
        // file form peminjaman
        Route::post('/peminjaman-internal/{id}/upload-form-pinjam', [PeminjamanInternalController::class, 'uploadFormPinjam'])->name('peminjaman-internal.upload-form-pinjam');
        Route::delete('/peminjaman-internal/{id}/delete-form-pinjam', [PeminjamanInternalController::class, 'deleteFormPinjam'])->name('peminjaman-internal.delete-form-pinjam');
        // Resource otomatis mencakup index, create, store, show, edit, update, destroy
        Route::resource('peminjaman-internal', PeminjamanInternalController::class);
    });

    // PEMINJAMAN EKSTERNAL
    Route::middleware('permission:peminjaman-eksternal')->group(function () {
        // Custom Routes untuk Update Kode & Approval
        Route::post('/peminjaman-eksternal/{id}/update-kode', [App\Http\Controllers\PeminjamanEksternalController::class, 'updateKode'])->name('peminjaman-eksternal.update-kode');
        Route::post('/peminjaman-eksternal/{id}/approve-pimpinan', [App\Http\Controllers\PeminjamanEksternalController::class, 'approvePimpinan'])->name('peminjaman-eksternal.approve-pimpinan');
        Route::post('/peminjaman-eksternal/{id}/reject-pimpinan', [App\Http\Controllers\PeminjamanEksternalController::class, 'rejectPimpinan'])->name('peminjaman-eksternal.reject-pimpinan');
        Route::post('/peminjaman-eksternal/{id}/approve-kasubbag', [App\Http\Controllers\PeminjamanEksternalController::class, 'approveKasubbag'])->name('peminjaman-eksternal.approve-kasubbag');
        Route::post('/peminjaman-eksternal/{id}/reject-kasubbag', [App\Http\Controllers\PeminjamanEksternalController::class, 'rejectKasubbag'])->name('peminjaman-eksternal.reject-kasubbag');
        Route::post('/peminjaman-eksternal/{id}/cancel', [App\Http\Controllers\PeminjamanEksternalController::class, 'cancel'])->name('peminjaman-eksternal.cancel');
        Route::post('/peminjaman-eksternal/upload-foto/{id}', [App\Http\Controllers\PeminjamanEksternalController::class, 'uploadFoto'])->name('peminjaman-eksternal.upload-foto');
        Route::delete('/peminjaman-eksternal/delete-foto/{id}', [App\Http\Controllers\PeminjamanEksternalController::class, 'deleteFoto'])->name('peminjaman-eksternal.delete-foto');
        Route::post('peminjaman-eksternal/{id}/update-status/{newStatus}', [PeminjamanEksternalController::class, 'updateStatus'])->name('peminjaman-eksternal.update-status');
        // Alur Pengembalian & Foto
        Route::get('/peminjaman-eksternal/{id}/return', [App\Http\Controllers\PeminjamanEksternalController::class, 'returnForm'])->name('peminjaman-eksternal.return');
        Route::post('/peminjaman-eksternal/{id}/return', [App\Http\Controllers\PeminjamanEksternalController::class, 'processReturn'])->name('peminjaman-eksternal.process-return');
        Route::post('/peminjaman-eksternal/upload-foto-kembali/{detailId}', [App\Http\Controllers\PeminjamanEksternalController::class, 'uploadFotoKembali'])->name('peminjaman-eksternal.upload-foto-kembali');
        Route::delete('/peminjaman-eksternal/delete-foto-kembali/{detailId}', [App\Http\Controllers\PeminjamanEksternalController::class, 'deleteFotoKembali'])->name('peminjaman-eksternal.delete-foto-kembali');
        // excport PDF & Word
        Route::get('/eksternal-peminjaman/{id}/export-pdf', [PeminjamanEksternalController::class, 'exportPdf'])->name('eksternal-peminjaman.export-pdf');
        Route::get('/eksternal-peminjaman/{id}/export-word', [PeminjamanEksternalController::class, 'exportWord'])->name('eksternal-peminjaman.export-word');
        // file form peminjaman
        Route::post('/peminjaman-eksternal/{id}/upload-form-pinjam', [PeminjamanEksternalController::class, 'uploadFormPinjam'])->name('peminjaman-eksternal.upload-form-pinjam');
        Route::delete('/peminjaman-eksternal/{id}/delete-form-pinjam', [PeminjamanEksternalController::class, 'deleteFormPinjam'])->name('peminjaman-eksternal.delete-form-pinjam');
        Route::resource('peminjaman-eksternal', PeminjamanEksternalController::class);
    });

    // HISTORY BARANG
    Route::middleware('permission:history-barang')->group(function () {
        Route::get('/history-barang', [App\Http\Controllers\HistoryBarangController::class, 'index'])->name('history-barang.index');
        Route::get('/history-barang/{id}', [App\Http\Controllers\HistoryBarangController::class, 'show'])->name('history-barang.show');
    });

    // HISTORY PEMINJAM
    Route::middleware('permission:history-peminjam')->group(function () {
        Route::get('/history-peminjam', [App\Http\Controllers\HistoryPeminjamController::class, 'index'])->name('history-peminjam.index');
        Route::get('/history-peminjam/{type}/{identifier}', [App\Http\Controllers\HistoryPeminjamController::class, 'show'])->name('history-peminjam.show');
    });

    // USER MANAGEMENT
    Route::middleware('permission:user-management')->group(function () {
        Route::resource('users', UserController::class);
    });

    // ROLE & AKSES
    Route::middleware('permission:role-akses')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    Route::middleware('permission:informasi')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    });
});