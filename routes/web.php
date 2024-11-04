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

Route::middleware(['auth'])->group(function () {
});
Route::get('/', [WelcomeController::class, 'index']);

// Kelola Pengguna
// routes/web.php


Route::prefix('pengguna')->group(function () {
    Route::get('/', [KelolaPenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/create_ajax', [KelolaPenggunaController::class, 'create_ajax'])->name('pengguna.create_ajax');
    Route::post('/ajax', [KelolaPenggunaController::class, 'store_ajax']);
    Route::get('/edit/{id}', [KelolaPenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/update/{id}', [KelolaPenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/destroy/{id}', [KelolaPenggunaController::class, 'destroy'])->name('pengguna.destroy');
});


// Jenis Pelatihan
Route::get('jenis-pelatihan', [JenisPelatihanController::class, 'index']);
Route::get('jenis-pelatihan/create', [JenisPelatihanController::class, 'create']);
Route::get('jenis-pelatihan/{id}', [JenisPelatihanController::class, 'show']);
Route::get('jenis-pelatihan/{id}/edit', [JenisPelatihanController::class, 'edit']);

// Vendor Pelatihan
Route::get('vendor-pelatihan', [VendorPelatihanController::class, 'index']);
Route::get('vendor-pelatihan/create', [VendorPelatihanController::class, 'create']);
Route::get('vendor-pelatihan/{id}', [VendorPelatihanController::class, 'show']);
Route::get('vendor-pelatihan/{id}/edit', [VendorPelatihanController::class, 'edit']);

// Vendor Sertifikasi
Route::get('vendor-sertifikasi', [VendorSertifikasiController::class, 'index']);
Route::get('vendor-sertifikasi/create', [VendorSertifikasiController::class, 'create']);
Route::get('vendor-sertifikasi/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('vendor-sertifikasi/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Kelola Mata Kuliah
// routes/web.php


Route::prefix('mata_kuliah')->name('mata_kuliah')->group(function () {
    Route::get('/', [KelolaMataKuliahController::class, 'index'])->name('index');
    Route::get('/create', [KelolaMataKuliahController::class, 'create'])->name('create');
    Route::post('/', [KelolaMataKuliahController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KelolaMataKuliahController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KelolaMataKuliahController::class, 'update'])->name('update');
    Route::delete('/{id}', [KelolaMataKuliahController::class, 'destroy'])->name('destroy');
});

// Kelola bidang minat

Route::prefix('bidang_minat')->name('bidang_minat')->group(function () {
Route::get('', [KelolaBidangMinatController::class, 'index']);
Route::get('/create', [KelolaBidangMinatController::class, 'create']);
Route::get('/{id}', [KelolaBidangMinatController::class, 'show']);
Route::get('/{id}/edit', [KelolaBidangMinatController::class, 'edit']);
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