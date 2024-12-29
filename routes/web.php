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
use App\Http\Controllers\DiagramStatistikSertifikasiController;
use App\Http\Controllers\DraftSuratTugasController;
use App\Http\Controllers\StatistikSertifikasiController;
use App\Http\Controllers\PelatihanSertifikasiController;
use App\Http\Controllers\KelolaPeriodeController;
use App\Http\Controllers\InputPelatihanController;
use App\Http\Controllers\InputSertifikasiController;
use App\Http\Controllers\KelolaAdminController;
use App\Http\Controllers\KelolaDosenController;
use App\Http\Controllers\KelolaJenisPenggunaController;
use App\Http\Controllers\KelolaPimpinanController;
use App\Http\Controllers\KelolaTendikController;
use App\Http\Controllers\PengajuanPelatihanController;
use App\Http\Controllers\RiwayatPelatihanController;
use App\Http\Controllers\RiwayatSertifikasiController;
use App\Http\Middleware\AuthorizeUser;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

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

// Menampilkan landing page sebelum login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home'); // Arahkan ke halaman hom jika sudah login
    } else{
        return view('landing_page'); // Tampilkan halaman landing page jika belum login
    }
});

// Rute untuk halaman landing_page yang terpisah
Route::get('/landing_page', function () {
    return view('landing_page'); // Halaman landing_page
});


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully.';
});

Route::pattern('id', '[0-9]+');
// Tugas Register
Route::get('register', [AuthController::class, 'register']);
Route::post('register', [AuthController::class, 'postRegister']);


Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');



Route::middleware(['auth'])->group(function () {
    // Route untuk menampilkan profil
    Route::get('/profile', [KelolaPenggunaController::class, 'showProfile'])->name('profile');

    // Route untuk update profil (termasuk upload gambar)
    Route::post('/profile/upload', [KelolaPenggunaController::class, 'uploadProfilePicture'])->name('profile.upload');

    // Route untuk update informasi pengguna
    Route::put('/profile/update', [KelolaPenggunaController::class, 'updateProfile'])->name('profile.update');

    // Route untuk mengubah password
    Route::post('/profile/change-password', [KelolaPenggunaController::class, 'changePassword'])->name('profile.changePassword');

    Route::get('/home', [WelcomeController::class, 'index']);

    // Middleware menggunakan ID role (misalnya 1 untuk Administrator)
    // Route::middleware(['authorize:ADM'])->group(function () {
    // Kelola Jenis Pengguna
    Route::prefix('jenis_pengguna')->group(function () {
        Route::get('/', [KelolaJenisPenggunaController::class, 'index']);
        Route::post('/list', [KelolaJenisPenggunaController::class, 'list']);
        Route::get('/create_ajax', [KelolaJenisPenggunaController::class, 'create_ajax']);
        Route::post('/ajax', [KelolaJenisPenggunaController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KelolaJenisPenggunaController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KelolaJenisPenggunaController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KelolaJenisPenggunaController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KelolaJenisPenggunaController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KelolaJenisPenggunaController::class, 'delete_ajax']);
        Route::get('/export_pdf', [KelolaJenisPenggunaController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaJenisPenggunaController::class, 'export_excel']);
        // });
    });

    // Kelola Pengguna
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
        Route::get('/export_pdf', [KelolaPenggunaController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaPenggunaController::class, 'export_excel']);
    });

    // Kelola Admin
    Route::prefix('admin')->group(function () {
        Route::get('/', [KelolaAdminController::class, 'index']);
        Route::post('/list', [KelolaAdminController::class, 'list']);
        Route::get('/create_ajax', [KelolaAdminController::class, 'create_ajax']);
        Route::post('/ajax', [KelolaAdminController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KelolaAdminController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KelolaAdminController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KelolaAdminController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KelolaAdminController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KelolaAdminController::class, 'delete_ajax']);
        Route::get('/export_pdf', [KelolaAdminController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaAdminController::class, 'export_excel']);
    });

    // Kelola Dosen
    Route::prefix('dosen')->group(function () {
        Route::get('/', [KelolaDosenController::class, 'index']);
        Route::post('/list', [KelolaDosenController::class, 'list']);
        Route::get('/create_ajax', [KelolaDosenController::class, 'create_ajax']);
        Route::post('/ajax', [KelolaDosenController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KelolaDosenController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KelolaDosenController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KelolaDosenController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KelolaDosenController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KelolaDosenController::class, 'delete_ajax']);
        Route::get('/export_pdf', [KelolaDosenController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaDosenController::class, 'export_excel']);
    });

    // Kelola Tendik
    Route::prefix('tendik')->group(function () {
        Route::get('/', [KelolaTendikController::class, 'index']);
        Route::post('/list', [KelolaTendikController::class, 'list']);
        Route::get('/create_ajax', [KelolaTendikController::class, 'create_ajax']);
        Route::post('/ajax', [KelolaTendikController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KelolaTendikController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KelolaTendikController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KelolaTendikController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KelolaTendikController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KelolaTendikController::class, 'delete_ajax']);
        Route::get('/export_pdf', [KelolaTendikController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaTendikController::class, 'export_excel']);
    });

    // Kelola Pimpinan
    Route::prefix('pimpinan')->group(function () {
        Route::get('/', [KelolaPimpinanController::class, 'index']);
        Route::post('/list', [KelolaPimpinanController::class, 'list']);
        Route::get('/create_ajax', [KelolaPimpinanController::class, 'create_ajax']);
        Route::post('/ajax', [KelolaPimpinanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [KelolaPimpinanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [KelolaPimpinanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KelolaPimpinanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KelolaPimpinanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KelolaPimpinanController::class, 'delete_ajax']);
        Route::get('/export_pdf', [KelolaPimpinanController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaPimpinanController::class, 'export_excel']);
    });

    // Jenis Pelatihan
    Route::prefix('jenis_pelatihan')->group(function () {
        Route::get('/', [JenisPelatihanController::class, 'index']);
        Route::post('/list', [JenisPelatihanController::class, 'list']);
        Route::get('/create_ajax', [JenisPelatihanController::class, 'create_ajax']);

        Route::post('/ajax', [JenisPelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [JenisPelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [JenisPelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [JenisPelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [JenisPelatihanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [JenisPelatihanController::class, 'delete_ajax']);
        Route::get('/export_pdf', [JenisPelatihanController::class, 'export_pdf']);
        Route::get('/export_excel', [JenisPelatihanController::class, 'export_excel']);
    });

    // Vendor Pelatihan
    Route::prefix('vendor_pelatihan')->group(function () {
        Route::get('/', [VendorPelatihanController::class, 'index']);
        Route::post('/list', [VendorPelatihanController::class, 'list']);
        Route::get('/create_ajax', [VendorPelatihanController::class, 'create_ajax']);
        Route::post('/ajax', [VendorPelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [VendorPelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [VendorPelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [VendorPelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [VendorPelatihanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [VendorPelatihanController::class, 'delete_ajax']);
        Route::get('/export_pdf', [VendorPelatihanController::class, 'export_pdf']);
        Route::get('/export_excel', [VendorPelatihanController::class, 'export_excel']);
    });

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
        Route::get('/export_pdf', [VendorSertifikasiController::class, 'export_pdf']);
        Route::get('/export_excel', [VendorSertifikasiController::class, 'export_excel']);
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
        Route::get('/export_pdf', [KelolaMataKuliahController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaMataKuliahController::class, 'export_excel']);
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
        Route::get('/export_pdf', [KelolaBidangMinatController::class, 'export_pdf']);
        Route::get('/export_excel', [KelolaBidangMinatController::class, 'export_excel']);
    });

    // Daftar Pelatihan
    Route::prefix('daftar_pelatihan')->group(function () {
        Route::get('/', [DaftarPelatihanController::class, 'index']);
        Route::post('/list', [DaftarPelatihanController::class, 'list']);
        Route::get('/create_ajax', [DaftarPelatihanController::class, 'create_ajax']);
        Route::post('/ajax', [DaftarPelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [DaftarPelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [DaftarPelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [DaftarPelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [DaftarPelatihanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [DaftarPelatihanController::class, 'delete_ajax']);
        Route::get('/export_pdf', [DaftarPelatihanController::class, 'export_pdf']);
        Route::get('/export_excel', [DaftarPelatihanController::class, 'export_excel']);
    });

    // Pengajuan Pelatihan
    Route::prefix('pengajuan_pelatihan')->group(function () {
        Route::get('/', [PengajuanPelatihanController::class, 'index']);
        Route::post('/list', [PengajuanPelatihanController::class, 'list']);
        Route::get('/create_ajax/{id}', [PengajuanPelatihanController::class, 'create_ajax']);
        Route::post('/ajax', [PengajuanPelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [PengajuanPelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [PengajuanPelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [PengajuanPelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [PengajuanPelatihanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [PengajuanPelatihanController::class, 'delete_ajax']);
        Route::get('/export_pdf', [PengajuanPelatihanController::class, 'export_pdf']);
        Route::get('/export_excel', [PengajuanPelatihanController::class, 'export_excel']);
        Route::get('/{id}/export_word', [PengajuanPelatihanController::class, 'export_word'])->name('pengajuan_pelatihan.export_word');
    });

    // Riwayat Pelatihan
    Route::prefix('riwayat_pelatihan')->group(function () {
        Route::get('/', [RiwayatPelatihanController::class, 'index']);
        Route::post('/list', [RiwayatPelatihanController::class, 'list']);
        Route::get('/create_ajax', [RiwayatPelatihanController::class, 'create_ajax']);
        Route::post('/ajax', [RiwayatPelatihanController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [RiwayatPelatihanController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [RiwayatPelatihanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [RiwayatPelatihanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [RiwayatPelatihanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [RiwayatPelatihanController::class, 'delete_ajax']);
        Route::get('/export_pdf', [RiwayatPelatihanController::class, 'export_pdf']);
        Route::get('/export_excel', [RiwayatPelatihanController::class, 'export_excel']);
    });

    // Riwayat Sertifikasi
    Route::prefix('riwayat_sertifikasi')->group(function () {
        Route::get('/', [RiwayatSertifikasiController::class, 'index']);
        Route::post('/list', [RiwayatSertifikasiController::class, 'list']);
        Route::get('/create_ajax', [RiwayatSertifikasiController::class, 'create_ajax']);
        Route::post('/ajax', [RiwayatSertifikasiController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [RiwayatSertifikasiController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [RiwayatSertifikasiController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [RiwayatSertifikasiController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [RiwayatSertifikasiController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [RiwayatSertifikasiController::class, 'delete_ajax']);
        Route::get('/export_pdf', [RiwayatSertifikasiController::class, 'export_pdf']);
        Route::get('/export_excel', [RiwayatSertifikasiController::class, 'export_excel']);
    });

    // Statistik sertifikasi
    Route::get('statistik_sertifikasi', [StatistikSertifikasiController::class, 'index']);
    Route::post('statistik_sertifikasi/list', [StatistikSertifikasiController::class, 'list']);

    // Diagram Statistik sertifikasi
    Route::get('diagram_statistik_sertifikasi', [DiagramStatistikSertifikasiController::class, 'index']);


    Route::get('/kelola_periode', [KelolaPeriodeController::class, 'index'])->name('kelola_periode.index');
    Route::post('/kelola_periode/list', [KelolaPeriodeController::class, 'list']);
});




Route::middleware(['auth'])->group(function () {});



// Draft Surat Tugas
Route::prefix('draft-surat-tugas')->name('draft-surat-tugas')->group(function () {
    Route::get('/', [DraftSuratTugasController::class, 'index']);
    Route::get('/create', [DraftSuratTugasController::class, 'create']);
    Route::get('/{id}', [DraftSuratTugasController::class, 'show']);
    Route::get('/{id}/edit', [DraftSuratTugasController::class, 'edit']);
});

// Statistik sertifikasi
Route::get('statistik-sertifikasi/create', [VendorSertifikasiController::class, 'create']);
Route::get('statistik-sertifikasi/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('statistik-sertifikasi/{id}/edit', [VendorSertifikasiController::class, 'edit']);

// Pelatihan Sertifikasi
Route::get('pelatihan-sertifikasi', [VendorSertifikasiController::class, 'index']);
Route::get('pelatihan-sertifikasi/create', [VendorSertifikasiController::class, 'create']);
Route::get('pelatihan-sertifikasi/{id}', [VendorSertifikasiController::class, 'show']);
Route::get('pelatihan-sertifikasi/{id}/edit', [VendorSertifikasiController::class, 'edit']);



// Route::get('/', function () {
//     return view('welcome');
// });