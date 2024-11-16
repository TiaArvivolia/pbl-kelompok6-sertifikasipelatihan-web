<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\MataKuliahModel;
use App\Models\Pengguna;
use App\Models\DaftarPelatihanModel;
use App\Models\RiwayatPelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RiwayatPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Pelatihan',
            'list' => ['Home', 'Pelatihan']
        ];

        $page = (object) [
            'title' => 'Daftar Riwayat Pelatihan'
        ];

        $activeMenu = 'riwayat_pelatihan';

        return view('riwayat_pelatihan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        // Create the base query for RiwayatPelatihan with relationships
        $query = RiwayatPelatihanModel::with(['pengguna.dosen', 'pengguna.tendik', 'pengguna.jenisPengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan'])
            ->select('id_riwayat', 'id_pengguna', 'nama_pelatihan', 'level_pelatihan', 'lokasi', 'penyelenggara', 'tanggal_mulai', 'tanggal_selesai');

        // Apply filter if level_pelatihan is provided in the request
        if ($request->filter_level_pelatihan) {
            $query->where('level_pelatihan', $request->filter_level_pelatihan);
        }

        // Return DataTables response
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_lengkap', function ($pelatihan) {
                return $pelatihan->pengguna->dosen ? $pelatihan->pengguna->dosen->nama_lengkap : ($pelatihan->pengguna->tendik ? $pelatihan->pengguna->tendik->nama_lengkap : 'Tidak Tersedia');
            })
            ->addColumn('nama_jenis_pengguna', function ($riwayat) {
                // Menampilkan nama jenis pengguna
                return $riwayat->pengguna && $riwayat->pengguna->jenisPengguna ? $riwayat->pengguna->jenisPengguna->nama_jenis_pengguna : '-';
            })
            ->addColumn('aksi', function ($pelatihan) {
                $btn = '<button onclick="modalAction(\'' . url('/riwayat_pelatihan/' . $pelatihan->id_riwayat . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/riwayat_pelatihan/' . $pelatihan->id_riwayat . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/riwayat_pelatihan/' . $pelatihan->id_riwayat . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create_ajax()
    {
        $pengguna = Pengguna::all();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();
        $daftarPelatihan = DaftarPelatihanModel::all();

        return view('riwayat_pelatihan.create_ajax', compact('pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan'));
    }

    public function store_ajax(Request $request)
    {
        $rules = [
            'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'id_pelatihan' => 'nullable|exists:daftar_pelatihan,id_pelatihan',
            'level_pelatihan' => 'required|in:Nasional,Internasional',
            'nama_pelatihan' => 'required|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string|max:100',
            'penyelenggara' => 'nullable|string|max:100',
            'dokumen_pelatihan' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,pdf,docx,xlsx',
            'tag_mk' => 'nullable|exists:mata_kuliah,id_mata_kuliah',
            'tag_bidang_minat' => 'nullable|exists:bidang_minat,id_bidang_minat'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        RiwayatPelatihanModel::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Riwayat Pelatihan berhasil disimpan'
        ]);
    }

    public function show_ajax($id)
    {
        // Mengambil data riwayat pelatihan beserta relasi pengguna, mata kuliah, bidang minat, dan jenis pengguna
        $pelatihan = RiwayatPelatihanModel::with([
            'pengguna',
            'mataKuliah',
            'bidangMinat',
            'daftarPelatihan',
            'pengguna.dosen',
            'pengguna.tendik',
            'pengguna.jenisPengguna'
        ])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        return view('riwayat_pelatihan.show_ajax', compact('pelatihan'));
    }

    public function edit_ajax(string $id)
    {
        // Eager loading the 'pengguna' with its related 'dosen' and 'tendik' models
        $pelatihan = RiwayatPelatihanModel::with([
            'pengguna.dosen',
            'pengguna.tendik',
            'mataKuliah',
            'bidangMinat',
            'daftarPelatihan'
        ])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Retrieve the list of pengguna (user), mataKuliah, bidangMinat, and daftarPelatihan
        $pengguna = Pengguna::all();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();
        $daftarPelatihan = DaftarPelatihanModel::all();

        // Pass all the variables to the view
        return view('riwayat_pelatihan.edit_ajax', compact('pelatihan', 'pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan'));
    }


    public function update_ajax(Request $request, $id)
    {
        $rules = [
            'id_pengguna' => 'exists:pengguna,id_pengguna',
            'id_pelatihan' => 'nullable|exists:daftar_pelatihan,id_pelatihan',
            'level_pelatihan' => 'required|in:Nasional,Internasional',
            'nama_pelatihan' => 'required|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string|max:100',
            'penyelenggara' => 'nullable|string|max:100',
            'dokumen_pelatihan' => 'nullable|string|max:255',
            'tag_mk' => 'nullable|exists:mata_kuliah,id_mata_kuliah',
            'tag_bidang_minat' => 'nullable|exists:bidang_minat,id_bidang_minat'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $pelatihan = RiwayatPelatihanModel::find($id);
        if ($pelatihan) {
            $pelatihan->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Riwayat Pelatihan berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Riwayat Pelatihan tidak ditemukan'
        ]);
    }

    public function confirm_ajax(string $id)
    {
        $pelatihan = RiwayatPelatihanModel::find($id);
        return view('riwayat_pelatihan.confirm_ajax', ['pelatihan' => $pelatihan]);
    }

    public function delete_ajax(string $id)
    {
        try {
            RiwayatPelatihanModel::destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Riwayat Pelatihan berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Riwayat Pelatihan gagal dihapus'
            ]);
        }
    }
}