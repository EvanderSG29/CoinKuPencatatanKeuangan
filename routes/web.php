<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('home');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Resource routes for kategori
Route::post('kategori/preview-defaults', [App\Http\Controllers\KategoriController::class, 'previewDefaults'])->name('kategori.previewDefaults');
Route::post('kategori/clear', [App\Http\Controllers\KategoriController::class, 'clear'])->name('kategori.clear');
Route::resource('kategori', App\Http\Controllers\KategoriController::class);

// Resource routes for transaksi
Route::post('transaksi/clear', [App\Http\Controllers\TransaksiController::class, 'clear'])->name('transaksi.clear');
Route::resource('transaksi', App\Http\Controllers\TransaksiController::class);

// Users listing and edit
Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/profile/{username}', [UserController::class, 'show'])->name('profile.show');
});

// Public asset routes
Route::get('/logo', function () {
    $path = storage_path('app/public/img/Logo.png');
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('logo');
