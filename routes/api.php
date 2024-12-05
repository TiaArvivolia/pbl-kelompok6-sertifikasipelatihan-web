<?php

use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\KelolaDosenController;
use App\Http\Controllers\Api\RiwayatPelatihanController;
use App\Http\Controllers\Api\RiwayatSertifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::middleware('auth:api')->get('/pengguna', function (Request $request) {
    return $request->user();
});


Route::prefix('pengguna')->group(function () {
    // Menampilkan daftar pengguna
    Route::get('/', [PenggunaController::class, 'index']);

    // Menyimpan data pengguna baru
    Route::post('/', [PenggunaController::class, 'store']);

    // Menampilkan detail pengguna
    Route::get('{id}', [PenggunaController::class, 'show']);

    // Mengupdate data pengguna
    Route::put('{id}', [PenggunaController::class, 'update']);

    // Menghapus data pengguna
    Route::delete('{id}', [PenggunaController::class, 'destroy']);
});

Route::prefix('dosen')->group(function () {
    Route::get('/', [KelolaDosenController::class, 'index']); // Get all dosen
    Route::post('/', [KelolaDosenController::class, 'store']); // Create a new dosen
    Route::get('/{id}', [KelolaDosenController::class, 'show']); // Get a specific dosen
    Route::put('/{id}', [KelolaDosenController::class, 'update']); // Update a dosen
    Route::delete('/{id}', [KelolaDosenController::class, 'destroy']); // Delete a dosen
});

Route::prefix('riwayat_sertifikasi')->group(function () {
    Route::get('/', [RiwayatSertifikasiController::class, 'index']); // Get all records
    Route::post('/', [RiwayatSertifikasiController::class, 'store']); // Create a new record
    Route::get('{id}', [RiwayatSertifikasiController::class, 'show']); // Show a single record
    Route::put('{id}', [RiwayatSertifikasiController::class, 'update']); // Update a record
    Route::delete('{id}', [RiwayatSertifikasiController::class, 'destroy']); // Delete a record
});
Route::prefix('riwayat_pelatihan')->group(function () {
    Route::get('/', [RiwayatPelatihanController::class, 'index']); // Get all records
    Route::post('/', [RiwayatPelatihanController::class, 'store']); // Create a new record
    Route::get('{id}', [RiwayatPelatihanController::class, 'show']); // Show a single record
    Route::put('{id}', [RiwayatPelatihanController::class, 'update']); // Update a record
    Route::delete('{id}', [RiwayatPelatihanController::class, 'destroy']); // Delete a record
});