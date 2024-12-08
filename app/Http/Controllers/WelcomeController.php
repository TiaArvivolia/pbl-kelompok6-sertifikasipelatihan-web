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

        // Ambil ID pengguna yang sedang login
        $user = auth()->user();  // Mendapatkan pengguna yang sedang login
        $userId = (string) auth()->user()->id_pengguna; // Ubah menjadi string
        $userType = $user->id_jenis_pengguna;  // ID jenis pengguna

        // Jika pengguna adalah dosen (id_jenis_pengguna 2) atau tendik/admin (id_jenis_pengguna 3)
        if (in_array($userType, [2, 3])) {
            // Ambil pengajuan pelatihan yang disetujui dan filter berdasarkan ID peserta
            $pengajuanDisetujui = PengajuanPelatihanModel::where('status', 'Disetujui')
                ->whereRaw("JSON_CONTAINS(id_peserta, ?)", [json_encode([$userId])])
                // Bergabung dengan tabel daftar_pelatihan untuk memeriksa tanggal selesai
                ->join('daftar_pelatihan', 'daftar_pelatihan.id_pelatihan', '=', 'pengajuan_pelatihan.id_pelatihan')
                // Memfilter pelatihan yang belum melebihi tanggal selesai
                ->whereDate('daftar_pelatihan.tanggal_selesai', '>=', now())
                ->get();
        } else {
            $pengajuanDisetujui = collect();  // Tidak menampilkan notifikasi untuk selain dosen dan tendik
        }

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

        $certificationsPerPeriod = DB::table('riwayat_sertifikasi')
            ->join('periode', 'riwayat_sertifikasi.id_periode', '=', 'periode.id_periode') // Join with the periode table
            ->select('periode.tahun_periode', DB::raw('COUNT(*) as count')) // Select the tahun_periode and count of records
            ->groupBy('periode.tahun_periode') // Group by tahun_periode
            ->orderBy('periode.tahun_periode', 'asc') // Order by tahun_periode
            ->get();

        $pelatihanPerPeriod = DB::table('riwayat_pelatihan')
            ->join('periode', 'riwayat_pelatihan.id_periode', '=', 'periode.id_periode') // Join with the periode table
            ->select('periode.tahun_periode', DB::raw('COUNT(*) as count')) // Select the tahun_periode and count of records
            ->groupBy('periode.tahun_periode') // Group by tahun_periode
            ->orderBy('periode.tahun_periode', 'asc') // Order by tahun_periode
            ->get();


        // Hitung jumlah pengajuan pelatihan
        $totalPengajuanPelatihan = PengajuanPelatihanModel::count();

        // Hitung jumlah pengajuan pelatihan dengan status "menunggu"
        $pengajuanPelatihanMenunggu = PengajuanPelatihanModel::where('status', 'menunggu')->count();

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
            'totalPengajuanPelatihan' => $totalPengajuanPelatihan,
            'pengajuanPelatihanMenunggu' => $pengajuanPelatihanMenunggu,
            'pengajuanDisetujui' => $pengajuanDisetujui
        ]);
    }
}