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
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];


        $activeMenu = 'dashboard';

        // Query for data
        $totalCertificates = RiwayatSertifikasiModel::count();

        $totalCertifiedParticipants = [
            'dosen' => RiwayatSertifikasiModel::whereHas('pengguna', function ($query) {
                $query->where('id_jenis_pengguna', 2);
            })->count(),
            'tendik' => RiwayatSertifikasiModel::whereHas('pengguna', function ($query) {
                $query->where('id_jenis_pengguna', 3);
            })->count()
        ];

        $totalPelatihanTerdata = RiwayatPelatihanModel::count();

        $certificationsByLevel = SertifikasiModel::select('level_sertifikasi', DB::raw('count(*) as count'))
            ->groupBy('level_sertifikasi')
            ->get();

        $certificationsByType = SertifikasiModel::select('jenis_sertifikasi', DB::raw('count(*) as count'))
            ->groupBy('jenis_sertifikasi')
            ->get();

        $certificationsBySubject = DB::table('riwayat_sertifikasi')
            ->join('mata_kuliah', DB::raw("JSON_CONTAINS(riwayat_sertifikasi.mk_list, JSON_QUOTE(CAST(mata_kuliah.id_mata_kuliah AS CHAR)))"), '=', DB::raw('true'))
            ->select('mata_kuliah.nama_mk', DB::raw('COUNT(*) as count'))
            ->groupBy('mata_kuliah.nama_mk')
            ->get();

        $certificationsByField = DB::table('riwayat_sertifikasi')
            ->join('bidang_minat', DB::raw("JSON_CONTAINS(riwayat_sertifikasi.bidang_minat_list, JSON_QUOTE(CAST(bidang_minat.id_bidang_minat AS CHAR)))"), '=', DB::raw('true'))
            ->select('bidang_minat.nama_bidang_minat', DB::raw('COUNT(*) as count'))
            ->groupBy('bidang_minat.nama_bidang_minat')
            ->get();

<<<<<<< HEAD
        $totalPengajuanPelatihan = PengajuanPelatihanModel::count();

        $pengajuanPelatihanMenunggu = PengajuanPelatihanModel::where('status', 'menunggu')->count();

        // In the Controller method
        // $certificationsPerPeriod = DB::table('riwayat_sertifikasi')
        //     ->select('tahun_periode', DB::raw('COUNT(*) as count'))
        //     ->groupBy('tahun_periode')
        //     ->orderBy('tahun_periode', 'asc')
        //     ->get();
=======
        $certificationsPerPeriod = DB::table('riwayat_sertifikasi')
            ->join('periode', 'riwayat_sertifikasi.id_periode', '=', 'periode.id_periode') // Join with the periode table
            ->select('periode.tahun_periode', DB::raw('COUNT(*) as count')) // Select the tahun_periode and count of records
            ->groupBy('periode.tahun_periode') // Group by tahun_periode
            ->orderBy('periode.tahun_periode', 'asc') // Order by tahun_periode
            ->get();
>>>>>>> 1a2341e8c9d11be1f8b77e67357c4e3caf7470a9

        $pelatihanPerPeriod = DB::table('riwayat_pelatihan')
            ->join('periode', 'riwayat_pelatihan.id_periode', '=', 'periode.id_periode') // Join with the periode table
            ->select('periode.tahun_periode', DB::raw('COUNT(*) as count')) // Select the tahun_periode and count of records
            ->groupBy('periode.tahun_periode') // Group by tahun_periode
            ->orderBy('periode.tahun_periode', 'asc') // Order by tahun_periode
            ->get();

<<<<<<< HEAD


            return view('welcome', [
                'breadcrumb' => $breadcrumb,
                'activeMenu' => $activeMenu,
                'totalCertificates' => $totalCertificates,
                'totalCertifiedParticipants' => $totalCertifiedParticipants,
                'certificationsByLevel' => $certificationsByLevel,
                'certificationsByType' => $certificationsByType,
                'certificationsBySubject' => $certificationsBySubject,
                'certificationsByField' => $certificationsByField,
                'totalPelatihanTerdata' => $totalPelatihanTerdata,
                'totalPengajuanPelatihan' => $totalPengajuanPelatihan,
                'pengajuanPelatihanMenunggu' => $pengajuanPelatihanMenunggu
                // 'certificationsPerPeriod' => $certificationsPerPeriod,
                // 'totalCertificationsAllPeriods' => $totalCertificationsAllPeriods,
            ]);
=======
        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'totalCertificates' => $totalCertificates,
            'totalCertifiedParticipants' => $totalCertifiedParticipants,
            'certificationsByLevel' => $certificationsByLevel,
            'certificationsByType' => $certificationsByType,
            'certificationsBySubject' => $certificationsBySubject,
            'certificationsByField' => $certificationsByField,
            'totalPelatihanTerdata' => $totalPelatihanTerdata,
            'certificationsPerPeriod' => $certificationsPerPeriod,
            'pelatihanPerPeriod' => $pelatihanPerPeriod,
            // 'totalCertificationsAllPeriods' => $totalCertificationsAllPeriods,
        ]);
>>>>>>> 1a2341e8c9d11be1f8b77e67357c4e3caf7470a9
    }
}
