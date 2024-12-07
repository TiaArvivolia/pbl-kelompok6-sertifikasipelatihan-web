<?php

namespace App\Http\Controllers;

use App\Models\KelolaDosenModel;
use App\Models\KelolaTendikModel;
use App\Models\RiwayatSertifikasi;
use App\Models\Pengguna;

class StatistikSertifikasiController extends Controller
{
    public function index()
    {
        // Breadcrumb and page title
        $breadcrumb = (object) [
            'title' => 'Statistik Sertifikasi',
            'list' => ['Home', 'Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Daftar Statistik Sertifikasi'
        ];

        $activeMenu = 'statistik_sertifikasi';

        // Mengambil data dosen beserta jumlah sertifikasi yang dimiliki
        $dosen = KelolaDosenModel::withCount('pengguna.riwayatSertifikasi')->get();

        // Mengambil data tendik beserta jumlah sertifikasi yang dimiliki
        $tendik = KelolaTendikModel::withCount('pengguna.riwayatSertifikasi')->get();

        // Menampilkan data ke view
        return view('statistik_sertifikasi.index', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'dosen',
            'tendik'
        ));
    }
}
