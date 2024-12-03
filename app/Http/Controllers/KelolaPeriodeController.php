<?php

namespace App\Http\Controllers;

use App\Models\PeriodeModel;
use App\Models\RiwayatPelatihan;
use App\Models\RiwayatSertifikasi;
use Illuminate\Http\Request;

class KelolaPeriodeController extends Controller
{
    public function index(Request $request)
    {
        // Breadcrumb and page title
        $breadcrumb = (object) [
            'title' => 'Kelola Periode',
            'list' => ['Home', 'Periode']
        ];

        $page = (object) [
            'title' => 'Daftar Kelola Periode'
        ];

        $activeMenu = 'kelola_periode';

        // Menangani filter berdasarkan tahun
        $selectedYear = $request->input('tahun_periode', null);

        $periodeQuery = PeriodeModel::withCount(['riwayatPelatihan', 'riwayatSertifikasi']);

        if ($selectedYear) {
            $periodeQuery->where('tahun_periode', $selectedYear);
        }

        $periodeList = $periodeQuery->get();

        // Mengambil daftar tahun periode untuk dropdown filter
        $yearsList = PeriodeModel::select('tahun_periode')->distinct()->get();

        // Kirim data ke view
        return view('kelola_periode.index', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'periodeList',
            'yearsList',
            'selectedYear'
        ));
    }
}