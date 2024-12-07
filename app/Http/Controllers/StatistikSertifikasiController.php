<?php

namespace App\Http\Controllers;

use App\Models\JenisPenggunaModel;
use App\Models\KelolaDosenModel;
use App\Models\KelolaTendikModel;
use App\Models\RiwayatSertifikasiModel;
use Illuminate\Http\Request;

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

        // Get list of distinct pengguna (users) and jenis_pengguna (user types)
        $dosenList = KelolaDosenModel::all();
        $tendikList = KelolaTendikModel::all();
        $jenisPenggunaList = JenisPenggunaModel::all();

        // Display data to the view
        return view('statistik_sertifikasi.index', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'dosenList',
            'tendikList',
            'jenisPenggunaList'
        ));
    }

    public function list(Request $request)
    {
        // Get statistik sertifikasi data with pengguna details
        $statistikSertifikasi = RiwayatSertifikasiModel::with(['pengguna.dosen', 'pengguna.tendik', 'pengguna.jenisPengguna'])
            ->selectRaw('id_pengguna, COUNT(*) as total_sertifikasi')
            ->groupBy('id_pengguna');

        // Apply filters based on request input
        if ($request->has('nama_lengkap') && $request->input('nama_lengkap') != '') {
            $statistikSertifikasi->whereHas('pengguna', function ($query) use ($request) {
                $query->whereHas('dosen', function ($query) use ($request) {
                    $query->where('nama_lengkap', 'like', '%' . $request->input('nama_lengkap') . '%');
                })
                    ->orWhereHas('tendik', function ($query) use ($request) {
                        $query->where('nama_lengkap', 'like', '%' . $request->input('nama_lengkap') . '%');
                    });
            });
        }

        if ($request->has('jenis_pengguna') && $request->input('jenis_pengguna') != '') {
            $statistikSertifikasi->whereHas('pengguna.jenisPengguna', function ($query) use ($request) {
                $query->where('nama_jenis_pengguna', 'like', '%' . $request->input('jenis_pengguna') . '%');
            });
        }

        // Fetch filtered data
        $statistikSertifikasi = $statistikSertifikasi->get();

        // Prepare data for table output
        $dataPengguna = [];
        foreach ($statistikSertifikasi as $sertifikasi) {
            $pengguna = $sertifikasi->pengguna;
            if (!isset($dataPengguna[$pengguna->id_pengguna])) {
                $dataPengguna[$pengguna->id_pengguna] = [
                    'nama_lengkap' => $pengguna->dosen->nama_lengkap ?? $pengguna->tendik->nama_lengkap ?? 'Tidak Diketahui',
                    'total_sertifikasi' => 0,
                    'jenis_pengguna' => $pengguna->jenisPengguna->nama_jenis_pengguna ?? 'Tidak Diketahui'
                ];
            }
            $dataPengguna[$pengguna->id_pengguna]['total_sertifikasi'] = $sertifikasi->total_sertifikasi;
        }

        // Flatten the data
        $flattenedData = [];
        foreach ($dataPengguna as $pengguna) {
            $flattenedData[] = [
                'nama_lengkap' => $pengguna['nama_lengkap'],
                'total_sertifikasi' => $pengguna['total_sertifikasi'],
                'jenis_pengguna' => $pengguna['jenis_pengguna']
            ];
        }

        // Return JSON response for DataTable
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => count($flattenedData),
            'recordsFiltered' => count($flattenedData),
            'data' => $flattenedData
        ]);
    }
}
