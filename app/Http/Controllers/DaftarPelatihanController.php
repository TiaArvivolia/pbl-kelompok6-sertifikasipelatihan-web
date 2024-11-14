<?php

namespace App\Http\Controllers;

use App\Models\DaftarPelatihanModel;
use App\Models\VendorPelatihanModel;
use App\Models\MataKuliahModel;
use App\Models\BidangMinatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DaftarPelatihanController extends Controller
{
    // Menampilkan halaman utama Daftar Pelatihan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pelatihan',
            'list' => ['Home', 'Pelatihan']
        ];

        $page = (object) [
            'title' => 'Daftar pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'daftar_pelatihan'; // Set active menu

        return view('daftar_pelatihan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data pelatihan dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $pelatihan = DaftarPelatihanModel::select(
            'id_pelatihan', 
            'nama_pelatihan', 
            'level_pelatihan', 
            'tanggal_mulai', 
            'tanggal_selesai', 
            'kuota', 
            'lokasi', 
            'biaya', 
            'jml_jam', 
            'id_vendor_pelatihan', 
            'tag_mk', 
            'tag_bidang_minat'
        )
        ->with(['vendorPelatihan', 'mataKuliah', 'bidangMinat']);

        return DataTables::of($pelatihan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pelatihan) {
                $btn = '<button onclick="modalAction(\'' . url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi']) // Ensure action column supports HTML
            ->make(true);
    }

    public function create_ajax()
    {
        // Ambil semua data vendor pelatihan, mata kuliah, dan bidang minat untuk pilihan dropdown
        $vendorPelatihan = VendorPelatihanModel::all();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();

        return view('daftar_pelatihan.create_ajax', compact('vendorPelatihan', 'mataKuliah', 'bidangMinat'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan'    => 'required|string|max:100',
                'level_pelatihan' => 'required|in:Nasional,Internasional',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'kuota'  => 'nullable|integer',
                'lokasi' => 'nullable|string|max:100',
                'biaya' => 'nullable|numeric',
                'jml_jam' => 'nullable|integer',
                'id_vendor_pelatihan' => 'required|exists:vendor_pelatihan,id_vendor_pelatihan',
                'tag_mk' => 'nullable|exists:mata_kuliah,id_mata_kuliah',
                'tag_bidang_minat' => 'nullable|exists:bidang_minat,id_bidang_minat',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            DaftarPelatihanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data pelatihan berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function show_ajax($id)
    {
        $pelatihan = DaftarPelatihanModel::with(['vendorPelatihan', 'mataKuliah', 'bidangMinat'])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        return view('daftar_pelatihan.show_ajax', compact('pelatihan'));
    }

    public function edit_ajax(string $id)
    {
        $pelatihan = DaftarPelatihanModel::with(['vendorPelatihan', 'mataKuliah', 'bidangMinat'])->find($id);
        if (!$pelatihan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        $vendorPelatihan = VendorPelatihanModel::all();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();

        return view('daftar_pelatihan.edit_ajax', compact('pelatihan', 'vendorPelatihan', 'mataKuliah', 'bidangMinat'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_pelatihan'    => 'required|string|max:100',
                'level_pelatihan' => 'required|in:Nasional,Internasional',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'kuota'  => 'nullable|integer',
                'lokasi' => 'nullable|string|max:100',
                'biaya' => 'nullable|numeric',
                'jml_jam' => 'nullable|integer',
                'id_vendor_pelatihan' => 'required|exists:vendor_pelatihan,id_vendor_pelatihan',
                'tag_mk' => 'nullable|exists:mata_kuliah,id_mata_kuliah',
                'tag_bidang_minat' => 'nullable|exists:bidang_minat,id_bidang_minat',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $pelatihan = DaftarPelatihanModel::find($id);
            if ($pelatihan) {
                $pelatihan->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data pelatihan berhasil diperbarui.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Pelatihan tidak ditemukan'
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $pelatihan = DaftarPelatihanModel::find($id);

        return view('daftar_pelatihan.confirm_ajax', ['pelatihan' => $pelatihan]);
    }

    public function delete_ajax(string $id)
    {
        try {
            DaftarPelatihanModel::destroy($id);
            return response()->json([
                'status'  => true,
                'message' => 'Data pelatihan berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Data pelatihan gagal dihapus'
            ]);
        }
    }
}