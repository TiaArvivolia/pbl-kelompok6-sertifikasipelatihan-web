<?php

namespace App\Http\Controllers;

use App\Models\Pengguna; 
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class KelolaPenggunaController extends Controller
{
    public function index()
    {
        // Mengambil judul halaman dan data level untuk filter
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'Pengguna']
        ];
        $page = (object) [
            'title' => 'Daftar Pengguna'
        ];
        $activeMenu = 'pengguna';

        $level = Pengguna::select('peran')->distinct()->get(); // Mengambil data peran sebagai filter

        return view('pengguna.index', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $pengguna = Pengguna::select(
            'id_pengguna',
            'username',
            'nama_lengkap',
            'peran'
        );

        // Filter berdasarkan peran pengguna jika diberikan
        if ($request->peran) {
            $pengguna->where('peran', $request->peran);
        }

        return DataTables::of($pengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                $btn = '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function exportExcel()
    {
        $filename = 'Pengguna_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PenggunaExport, $filename);
    }

    public function exportPdf()
    {
        $pengguna = Pengguna::all();

        $pdf = Pdf::loadView('pengguna.export_pdf', compact('pengguna'));
        return $pdf->download('Pengguna_' . now()->format('Ymd_His') . '.pdf');
    }
}
