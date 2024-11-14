<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\KelolaTendikModel; // Model untuk tendik
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KelolaTendikController extends Controller
{
    // Menampilkan halaman utama Kelola Tendik
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tendik',
            'list' => ['Home', 'Tendik']
        ];

        $page = (object) [
            'title' => 'Daftar tendik yang terdaftar dalam sistem'
        ];

        $activeMenu = 'tendik'; // Set active menu

        return view('tendik.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data tendik dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $tendik = KelolaTendikModel::select('id_tendik', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil', 'tag_bidang_minat')
            ->with('pengguna', 'bidangMinat');

        return DataTables::of($tendik)
            ->addIndexColumn()
            ->addColumn('aksi', function ($tendik) {
                $btn = '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi']) // Ensure action column supports HTML
            ->make(true);
    }

    public function create_ajax()
    {
        $tendik = KelolaTendikModel::with(['pengguna', 'bidangMinat'])->get();
        if (!$tendik) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Ambil semua data pengguna dan bidang minat untuk pilihan dropdown
        $pengguna = Pengguna::all();
        $bidangMinat = BidangMinatModel::all();

        return view('tendik.create_ajax', compact('tendik', 'bidangMinat', 'pengguna'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_pengguna'    => 'required|exists:pengguna,id_pengguna',
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'no_telepon'  => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            KelolaTendikModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data tendik berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function show_ajax($id)
    {
        // Load the tendik record with pengguna and bidangMinat relationships
        $tendik = KelolaTendikModel::with(['pengguna', 'bidangMinat'])->find($id);

        if (!$tendik) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        return view('tendik.show_ajax', compact('tendik'));
    }

    public function edit_ajax(string $id)
    {
        $tendik = KelolaTendikModel::with(['pengguna', 'bidangMinat'])->find($id);
        if (!$tendik) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Ambil semua data bidang minat untuk pilihan dropdown
        $bidangMinat = BidangMinatModel::all();

        return view('tendik.edit_ajax', compact('tendik', 'bidangMinat'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'no_telepon'  => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $tendik = KelolaTendikModel::find($id);
            if ($tendik) {
                $tendik->bidangMinat()->associate(BidangMinatModel::find($request->tag_bidang_minat));
                $tendik->update($request->except(['tag_bidang_minat'])); // Update other fields except the relation

                return response()->json([
                    'status'  => true,
                    'message' => 'Data tendik berhasil diperbarui.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Tendik tidak ditemukan'
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $tendik = KelolaTendikModel::find($id);
        return view('tendik.confirm_ajax', ['tendik' => $tendik]);
    }

    public function delete_ajax(string $id)
    {
        try {
            KelolaTendikModel::destroy($id);
            return response()->json([
                'status'  => true,
                'message' => 'Data tendik berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tendik gagal dihapus'
            ]);
        }
    }
}
