<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\KelolaDosenModel;
use App\Models\MataKuliahModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KelolaDosenController extends Controller
{
    // Menampilkan halaman utama Kelola Dosen
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Dosen',
            'list' => ['Home', 'Dosen']
        ];

        $page = (object) [
            'title' => 'Daftar dosen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dosen'; // Set active menu

        return view('dosen.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data dosen dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $dosen = KelolaDosenModel::select('id_dosen', 'id_pengguna', 'nama_lengkap', 'nip', 'nidn', 'tempat_lahir', 'tanggal_lahir', 'no_telepon', 'email', 'gambar_profil')
            ->with('pengguna');

        return DataTables::of($dosen)
            ->addIndexColumn()
            ->addColumn('aksi', function ($dosen) {
                $btn = '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi']) // Ensure action column supports HTML
            ->make(true);
    }

    public function create_ajax()
    {
        $dosen = KelolaDosenModel::with(['pengguna', 'mataKuliah', 'bidangMinat'])->get();
        if (!$dosen) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Ambil semua data mata kuliah dan bidang minat untuk pilihan dropdown
        $pengguna = Pengguna::all(); // Pastikan model MataKuliah sudah sesuai
        $mataKuliah = MataKuliahModel::all(); // Pastikan model MataKuliah sudah sesuai
        $bidangMinat = BidangMinatModel::all(); // Pastikan model BidangMinat sudah sesuai

        return view('dosen.create_ajax', compact('dosen', 'mataKuliah', 'bidangMinat', 'pengguna'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_pengguna'    => 'required|exists:pengguna,id_pengguna',
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'nidn' => 'required|string|max:20',
                'no_telepon'  => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'tag_mk' => 'required|exists:mata_kuliah,id_mata_kuliah',  // Menambahkan validasi untuk tag_mk
                'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat', // Validasi untuk tag_bidang_minat
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            KelolaDosenModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function show_ajax($id)
    {
        // Load the dosen record with pengguna, mataKuliah, and bidangMinat relationships
        $dosen = KelolaDosenModel::with(['pengguna', 'mataKuliah', 'bidangMinat'])->find($id);

        if (!$dosen) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        return view('dosen.show_ajax', compact('dosen'));
    }

    public function edit_ajax(string $id)
    {
        $dosen = KelolaDosenModel::with(['pengguna', 'mataKuliah', 'bidangMinat'])->find($id);
        if (!$dosen) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Ambil semua data mata kuliah dan bidang minat untuk pilihan dropdown
        $mataKuliah = MataKuliahModel::all(); // Pastikan model MataKuliah sudah sesuai
        $bidangMinat = BidangMinatModel::all(); // Pastikan model BidangMinat sudah sesuai

        return view('dosen.edit_ajax', compact('dosen', 'mataKuliah', 'bidangMinat'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'nidn' => 'required|string|max:20',
                'no_telepon'  => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'tag_mk' => 'required|exists:mata_kuliah,id_mata_kuliah',  // Menambahkan validasi untuk tag_mk
                'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat', // Validasi untuk tag_bidang_minat
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $dosen = KelolaDosenModel::find($id);
            if ($dosen) {
                // Menangani update untuk tag_mk dan tag_bidang_minat
                $dosen->mataKuliah()->associate(MataKuliahModel::find($request->tag_mk));  // Mengupdate relasi Mata Kuliah
                $dosen->bidangMinat()->associate(BidangMinatModel::find($request->tag_bidang_minat)); // Mengupdate relasi Bidang Minat

                $dosen->update($request->except(['tag_mk', 'tag_bidang_minat'])); // Mengupdate kolom lainnya selain relasi

                return response()->json([
                    'status'  => true,
                    'message' => 'Data dosen berhasil diperbarui.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Dosen tidak ditemukan'
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $dosen = KelolaDosenModel::find($id);
        return view('dosen.confirm_ajax', ['dosen' => $dosen]);
    }

    public function delete_ajax(string $id)
    {
        try {
            KelolaDosenModel::destroy($id);
            return response()->json([
                'status'  => true,
                'message' => 'Data dosen berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Data dosen gagal dihapus'
            ]);
        }
    }
}
