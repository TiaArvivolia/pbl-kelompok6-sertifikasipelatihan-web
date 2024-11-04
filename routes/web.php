<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\KelolaPenggunaController;
use App\Http\Controllers\JenisPelatihanController;
use App\Http\Controllers\VendorPelatihanController;
use App\Http\Controllers\VendorSertifikasiController;
use App\Http\Controllers\KelolaMataKuliahController;
use App\Http\Controllers\KelolaBidangMinatController;
use App\Http\Controllers\DaftarSertifikasiController;
use App\Http\Controllers\DaftarPelatihanController;
use App\Http\Controllers\DraftSuratTugasController;
use App\Http\Controllers\StatistikSertifikasiController;
use App\Http\Controllers\PelatihanSertifikasiController;
use App\Http\Controllers\KelolaPeriodeController;
use App\Http\Controllers\InputPelatihanController;
use App\Http\Controllers\InputSertifikasiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+');
// Tugas Register
Route::get('register', [AuthController::class, 'register']);
Route::post('register', [AuthController::class, 'postRegister']);


Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {});
Route::get('/', [WelcomeController::class, 'index']);

// Kelola Pengguna
// routes/web.php


Route::prefix('pengguna')->group(function () {
    Route::get('/', [KelolaPenggunaController::class, 'index']);
    Route::post('/list', [KelolaPenggunaController::class, 'list']);
    Route::get('/create_ajax', [KelolaPenggunaController::class, 'create_ajax']);
    Route::post('/ajax', [KelolaPenggunaController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [KelolaPenggunaController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [KelolaPenggunaController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [KelolaPenggunaController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [KelolaPenggunaController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [KelolaPenggunaController::class, 'delete_ajax']);
});


// Jenis Pelatihan
Route::get('jenis-pelatihan', [JenisPelatihanController::class, 'index']);
Route::get('jenis-pelatihan/create', [JenisPelatihanController::class, 'create']);
Route::get('jenis-pelatihan/{id}', [JenisPelatihanController::class, 'show']);
Route::get('jenis-pelatihan/{id}/edit', [JenisPelatihanController::class, 'edit']);

// Vendor Pelatihan
Route::get('vendorpelatihan', [VendorPelatihanController::class, 'index']);
Route::get('vendorpelatihan/create', [VendorPelatihanController::class, 'create']);
Route::get('vendorpelatihan/{id}', [VendorPelatihanController::class, 'show']);
Route::get('vendorpelatihan/{id}/edit', [VendorPelatihanController::class, 'edit']);

// Vendor Sertifikasi
Route::prefix('vendor_sertifikasi')->group(function () {
    Route::get('/', [VendorSertifikasiController::class, 'index']);
    Route::post('/list', [VendorSertifikasiController::class, 'list']);
    Route::get('/create_ajax', [VendorSertifikasiController::class, 'create_ajax']);
    Route::post('/ajax', [VendorSertifikasiController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [VendorSertifikasiController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [VendorSertifikasiController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [VendorSertifikasiController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [VendorSertifikasiController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [VendorSertifikasiController::class, 'delete_ajax']);
});

// Kelola Mata Kuliah
Route::prefix('mata_kuliah')->name('mata_kuliah')->group(function () {
    Route::get('/', [KelolaMataKuliahController::class, 'index']);
    Route::post('/list', [KelolaMataKuliahController::class, 'list']);
    Route::get('/create_ajax', [KelolaMataKuliahController::class, 'create_ajax']);
    Route::post('/ajax', [KelolaMataKuliahController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [KelolaMataKuliahController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [KelolaMataKuliahController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [KelolaMataKuliahController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [KelolaMataKuliahController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [KelolaMataKuliahController::class, 'delete_ajax']);
});

// Kelola bidang minat

Route::prefix('bidang_minat')->name('bidang_minat')->group(function () {
    Route::get('/', [KelolaBidangMinatController::class, 'index']);
    Route::post('/list', [KelolaBidangMinatController::class, 'list']);
    Route::get('/create_ajax', [KelolaBidangMinatController::class, 'create_ajax']);
    Route::post('/ajax', [KelolaBidangMinatController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [KelolaBidangMinatController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [KelolaBidangMinatController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [KelolaBidangMinatController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [KelolaBidangMinatController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [KelolaBidangMinatController::class, 'delete_ajax']);
});

// Daftar sertifikasi
Route::get('daftar-sertifikasi', [VendorSertifikasiController::class, 'index']);
Route::get('daftar-sertifikasi/create', [VendorSertifikasiController::class, 'create']);
Route::get('daftar-sertifikasi/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('daftar-sertifikasi/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Daftar Pelatihan
Route::get('daftar-pelatihan', [VendorSertifikasiController::class, 'index']);
Route::get('daftar-pelatihan/create', [VendorSertifikasiController::class, 'create']);
Route::get('daftar-pelatihan/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('daftar-pelatihan/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Draft Surat Tugas
Route::get('draft-surat-tugas', [VendorSertifikasiController::class, 'index']);
Route::get('draft-surat-tugas/create', [VendorSertifikasiController::class, 'create']);
Route::get('draft-surat-tugas/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('draft-surat-tugas/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Statistik sertifikasi
Route::get('statistik-sertifikasi', [VendorSertifikasiController::class, 'index']);
Route::get('statistik-sertifikasi/create', [VendorSertifikasiController::class, 'create']);
Route::get('statistik-sertifikasi/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('statistik-sertifikasi/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Pelatihan Sertifikasi
Route::get('pelatihan-sertifikasi', [VendorSertifikasiController::class, 'index']);
Route::get('pelatihan-sertifikasi/create', [VendorSertifikasiController::class, 'create']);
Route::get('pelatihan-sertifikasi/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('pelatihan-sertifikasi/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Input Pelatihan
Route::get('input-pelatihan', [VendorSertifikasiController::class, 'index']);
Route::get('input-pelatihan/create', [VendorSertifikasiController::class, 'create']);
Route::get('input-pelatihan/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('input-pelatihan/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Input Sertifikasi
Route::get('input-sertifikasi', [VendorSertifikasiController::class, 'index']);
Route::get('input-sertifikasi/create', [VendorSertifikasiController::class, 'create']);
Route::get('input-sertifikasi/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('input-sertifikasi/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Route::get('/', function () {
//     return view('welcome');
// });