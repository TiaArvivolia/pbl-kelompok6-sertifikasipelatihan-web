<?php

namespace App\Http\Controllers;

use App\Models\RiwayatSertifikasiModel;
use App\Models\SertifikasiModel;
use App\Models\MataKuliahModel;
use App\Models\BidangMinatModel;
use App\Models\RiwayatPelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanPelatihanModel;

class WelcomeController extends Controller
{
    public function index()
    {
        // Breadcrumb and page title
        $breadcrumb = (object) [
            'title' => 'Selamat Datang!',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        // Get logged-in user
        $user = auth()->user();
        $userId = (string) $user->id_pengguna; // Convert to string
        $userType = $user->id_jenis_pengguna;  // User type

        // Initialize variables
        $pengajuanDisetujui = collect();

        // Query for pengajuan pelatihan if user is a lecturer (id_jenis_pengguna 2) or staff (id_jenis_pengguna 3)
        if (in_array($userType, [2, 3])) {
            // Filter pengajuan pelatihan based on user id (id_peserta)
            $pengajuanDisetujui = PengajuanPelatihanModel::where('status', 'Disetujui')
                ->whereRaw("JSON_CONTAINS(id_peserta, ?)", [json_encode([$userId])])
                ->join('daftar_pelatihan', 'daftar_pelatihan.id_pelatihan', '=', 'pengajuan_pelatihan.id_pelatihan')
                ->whereDate('daftar_pelatihan.tanggal_selesai', '>=', now())
                ->get();
        }

        // Query for data based on user
        $totalCertificates = RiwayatSertifikasiModel::where('id_pengguna', $userId)->count();
        $totalPelatihanTerdata = RiwayatPelatihanModel::where('id_pengguna', $userId)->count();

        $certificationsPerPeriod = DB::table('riwayat_sertifikasi')
            ->join('periode', 'riwayat_sertifikasi.id_periode', '=', 'periode.id_periode')
            ->select('periode.tahun_periode', DB::raw('COUNT(*) as count'))
            ->where('riwayat_sertifikasi.id_pengguna', $userId)
            ->groupBy('periode.tahun_periode')
            ->orderBy('periode.tahun_periode', 'asc')
            ->get();

        $pelatihanPerPeriod = DB::table('riwayat_pelatihan')
            ->join('periode', 'riwayat_pelatihan.id_periode', '=', 'periode.id_periode')
            ->select('periode.tahun_periode', DB::raw('COUNT(*) as count'))
            ->where('riwayat_pelatihan.id_pengguna', $userId)
            ->groupBy('periode.tahun_periode')
            ->orderBy('periode.tahun_periode', 'asc')
            ->get();

        // For admin (id_jenis_pengguna 1) and leadership (id_jenis_pengguna 4), show all data
        if (in_array($userType, [1, 4])) {
            // Admin and leadership can view all data
            $totalCertificates = RiwayatSertifikasiModel::count();
            $totalPelatihanTerdata = RiwayatPelatihanModel::count();
            $certificationsPerPeriod = DB::table('riwayat_sertifikasi')
                ->join('periode', 'riwayat_sertifikasi.id_periode', '=', 'periode.id_periode')
                ->select('periode.tahun_periode', DB::raw('COUNT(*) as count'))
                ->groupBy('periode.tahun_periode')
                ->orderBy('periode.tahun_periode', 'asc')
                ->get();

            $pelatihanPerPeriod = DB::table('riwayat_pelatihan')
                ->join('periode', 'riwayat_pelatihan.id_periode', '=', 'periode.id_periode')
                ->select('periode.tahun_periode', DB::raw('COUNT(*) as count'))
                ->groupBy('periode.tahun_periode')
                ->orderBy('periode.tahun_periode', 'asc')
                ->get();

            // Admin and leadership can see all pengajuan pelatihan data
            $totalPengajuanPelatihan = PengajuanPelatihanModel::count();
            $pengajuanPelatihanMenunggu = PengajuanPelatihanModel::where('status', 'menunggu')->count();
        } else {
            // For lecturer and staff, filter based on their own submissions
            $totalPengajuanPelatihan = PengajuanPelatihanModel::whereRaw("JSON_CONTAINS(id_peserta, ?)", [json_encode([$userId])])->count();
            $pengajuanPelatihanMenunggu = PengajuanPelatihanModel::where('status', 'menunggu')
                ->whereRaw("JSON_CONTAINS(id_peserta, ?)", [json_encode([$userId])])
                ->count();
        }

        // Return the view with data
        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'totalCertificates' => $totalCertificates,
            'totalPelatihanTerdata' => $totalPelatihanTerdata,
            'certificationsPerPeriod' => $certificationsPerPeriod,
            'pelatihanPerPeriod' => $pelatihanPerPeriod,
            'totalPengajuanPelatihan' => $totalPengajuanPelatihan ?? 0,
            'pengajuanPelatihanMenunggu' => $pengajuanPelatihanMenunggu ?? 0,
            'pengajuanDisetujui' => $pengajuanDisetujui
        ]);
    }
}