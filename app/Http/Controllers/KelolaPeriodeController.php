<?php

namespace App\Http\Controllers;

use App\Models\PeriodeModel;
use App\Models\RiwayatPelatihanModel;
use App\Models\RiwayatSertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KelolaPeriodeController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Periode',
            'list' => ['Home', 'Periode']
        ];

        $page = (object) [
            'title' => 'Daftar Kelola Periode'
        ];

        $activeMenu = 'kelola_periode';

        // Menangani filter berdasarkan tahun, nama dosen, dan nama tendik
        $selectedYear = $request->input('tahun_periode', null);
        $selectedDosen = $request->input('nama_dosen', null);
        $selectedTendik = $request->input('nama_tendik', null);

        // Ambil daftar tahun periode untuk dropdown filter
        $yearsList = PeriodeModel::select('tahun_periode')->distinct()->get();

        return view('kelola_periode.index', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'yearsList',
            'selectedYear',
            'selectedDosen',
            'selectedTendik'
        ));
    }

    // Ambil data periode dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $periodeQuery = PeriodeModel::query();

        if ($request->tahun_periode) {
            $periodeQuery->where('tahun_periode', $request->tahun_periode);
        }

        $selectedDosen = $request->input('nama_dosen');
        $selectedTendik = $request->input('nama_tendik');

        $periodeList = $periodeQuery->get();

        $dataPengguna = [];
        foreach ($periodeList as $periode) {
            $pelatihan = RiwayatPelatihanModel::with(['pengguna.dosen', 'pengguna.tendik'])
                ->where('id_periode', $periode->id_periode)
                ->when($selectedDosen, function ($query) use ($selectedDosen) {
                    $query->whereHas('pengguna.dosen', function ($q) use ($selectedDosen) {
                        $q->where('nama_lengkap', 'like', '%' . $selectedDosen . '%');
                    });
                })
                ->when($selectedTendik, function ($query) use ($selectedTendik) {
                    $query->whereHas('pengguna.tendik', function ($q) use ($selectedTendik) {
                        $q->where('nama_lengkap', 'like', '%' . $selectedTendik . '%');
                    });
                })
                ->selectRaw('id_pengguna, COUNT(*) as total_pelatihan')
                ->groupBy('id_pengguna')
                ->get();

            $sertifikasi = RiwayatSertifikasiModel::with(['pengguna.dosen', 'pengguna.tendik'])
                ->where('id_periode', $periode->id_periode)
                ->when($selectedDosen, function ($query) use ($selectedDosen) {
                    $query->whereHas('pengguna.dosen', function ($q) use ($selectedDosen) {
                        $q->where('nama_lengkap', 'like', '%' . $selectedDosen . '%');
                    });
                })
                ->when($selectedTendik, function ($query) use ($selectedTendik) {
                    $query->whereHas('pengguna.tendik', function ($q) use ($selectedTendik) {
                        $q->where('nama_lengkap', 'like', '%' . $selectedTendik . '%');
                    });
                })
                ->selectRaw('id_pengguna, COUNT(*) as total_sertifikasi')
                ->groupBy('id_pengguna')
                ->get();

            foreach ($pelatihan as $p) {
                $pengguna = $p->pengguna;
                if (!isset($dataPengguna[$pengguna->id_pengguna])) {
                    $dataPengguna[$pengguna->id_pengguna] = [
                        'nama_lengkap' => $pengguna->dosen->nama_lengkap ?? $pengguna->tendik->nama_lengkap ?? 'Tidak Diketahui',
                        'periode' => []
                    ];
                }

                $dataPengguna[$pengguna->id_pengguna]['periode'][$periode->tahun_periode]['pelatihan'] = $p->total_pelatihan;
            }

            foreach ($sertifikasi as $s) {
                $pengguna = $s->pengguna;
                if (!isset($dataPengguna[$pengguna->id_pengguna])) {
                    $dataPengguna[$pengguna->id_pengguna] = [
                        'nama_lengkap' => $pengguna->dosen->nama_lengkap ?? $pengguna->tendik->nama_lengkap ?? 'Tidak Diketahui',
                        'periode' => []
                    ];
                }

                $dataPengguna[$pengguna->id_pengguna]['periode'][$periode->tahun_periode]['sertifikasi'] = $s->total_sertifikasi;
            }
        }

        // Flatten the data to match the table columns
        $flattenedData = [];
        foreach ($dataPengguna as $pengguna) {
            foreach ($pengguna['periode'] as $tahun => $periode) {
                $flattenedData[] = [
                    'nama_lengkap' => $pengguna['nama_lengkap'],
                    'periode' => $tahun,
                    'pelatihan' => $periode['pelatihan'] ?? 0,
                    'sertifikasi' => $periode['sertifikasi'] ?? 0,
                    'aksi' => '<button class="btn btn-primary">Aksi</button>'
                ];
            }
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => count($flattenedData),
            'recordsFiltered' => count($flattenedData),
            'data' => $flattenedData
        ]);
    }
}
