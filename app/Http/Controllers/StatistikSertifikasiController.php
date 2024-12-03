<?php

namespace App\Http\Controllers;

use App\Models\RiwayatSertifikasiModel;
use App\Models\SertifikasiModel;
use App\Models\MataKuliahModel;
use App\Models\BidangMinatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Controller method
        $certificationsPerPeriod = DB::table('riwayat_sertifikasi')
            ->join('periode', 'riwayat_sertifikasi.id_periode', '=', 'periode.id_periode') // Join with the periode table
            ->select('periode.tahun_periode', DB::raw('COUNT(*) as count')) // Select the tahun_periode and count of records
            ->groupBy('periode.tahun_periode') // Group by tahun_periode
            ->orderBy('periode.tahun_periode', 'asc') // Order by tahun_periode
            ->get();


        return view('statistik_sertifikasi.index', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'totalCertificates',
            'totalCertifiedParticipants',
            'certificationsByLevel',
            'certificationsByType',
            'certificationsBySubject',
            'certificationsByField',
            'certificationsPerPeriod'
        ));
    }
}