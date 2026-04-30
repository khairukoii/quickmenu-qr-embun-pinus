<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;

// Halaman Pelanggan
Route::get('/', [FrontController::class, 'index']);
Route::post('/checkout', [FrontController::class, 'checkout']);
Route::get('/cek-status/{id}', [FrontController::class, 'checkStatus']); // <-- Tambahkan ini

// Rute Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang DIKUNCI (Hanya bisa dibuka kalau sudah login)
Route::middleware('auth')->group(function () {
    // Rute Admin
    Route::get('/admin', [MenuController::class, 'index']);
    Route::post('/admin/menu/tambah', [MenuController::class, 'store'])->name('menu.store');
    Route::post('/admin/menu/{id}/hapus', [MenuController::class, 'destroy'])->name('menu.destroy');
    Route::get('/admin/order/{id}/struk', [MenuController::class, 'cetakStruk'])->name('order.struk'); // <-- TAMBAHKAN INI
    
    // Rute Dapur
    Route::get('/dapur', [MenuController::class, 'dapur']);
    Route::post('/dapur/order/{id}/selesai', [MenuController::class, 'selesaikanPesanan']);
    Route::post('/menu/{id}/toggle', [App\Http\Controllers\MenuController::class, 'toggleStatus'])->name('menu.toggleStatus');
    Route::put('/menu/{id}', [App\Http\Controllers\MenuController::class, 'update'])->name('menu.update');

    Route::post('/order/{id}/konfirmasi-bayar', function($id) {
    $order = \App\Models\Order::find($id);
    $order->status = 'pending'; // Pindahkan ke dapur
    $order->save();
    return redirect()->back()->with('success', 'Uang tunai diterima! Pesanan otomatis masuk ke dapur.');
})->name('order.konfirmasi_bayar');
});