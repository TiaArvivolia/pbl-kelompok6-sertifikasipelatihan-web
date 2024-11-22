<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\MataKuliahModel;
use App\Models\Pengguna;
use App\Models\DaftarPelatihanModel;
use App\Models\RiwayatPelatihanModel;
use App\Models\VendorPelatihanModel;
use App\Models\VendorSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        // Mengambil data pengguna yang memiliki relasi dengan dosen atau tendik
        $pengguna = Pengguna::with(['dosen', 'tendik'])
            ->whereHas('dosen')
            ->orWhereHas('tendik')
            ->get();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();
        $daftarPelatihan = DaftarPelatihanModel::all();
        $penyelenggara = VendorPelatihanModel::all();

        return view('riwayat_pelatihan.create_ajax', compact('pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan', 'penyelenggara'));
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
            'penyelenggara' => 'required|exists:vendor_pelatihan,id_vendor_pelatihan',
            'dokumen_pelatihan' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,pdf,docx,xlsx|max:10240', // Maksimum ukuran file 10MB
            'mk_list' => 'nullable|array', // Ensure mk_list is an array if provided
            'mk_list.*' => 'exists:mata_kuliah,id_mata_kuliah', // Each mata kuliah in mk_list must exist
            'bidang_minat_list' => 'nullable|array', // Ensure bidang_minat_list is an array if provided
            'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat', // Each bidang minat in bidang_minat_list must exist
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // Menangani dokumen pelatihan jika ada
        $dokumenPath = null;
        if ($request->hasFile('dokumen_pelatihan') && $request->file('dokumen_pelatihan')->isValid()) {
            // Mengambil file yang diunggah
            $file = $request->file('dokumen_pelatihan');
            // Menyimpan file di folder 'dokumen_pelatihan' di storage/public dan mendapatkan path file
            $dokumenPath = $file->store('dokumen_pelatihan', 'public');
        }

        // Menyimpan data riwayat pelatihan
        RiwayatPelatihanModel::create([
            'id_pengguna' => $request->id_pengguna,
            'id_pelatihan' => $request->id_pelatihan,
            'level_pelatihan' => $request->level_pelatihan,
            'nama_pelatihan' => $request->nama_pelatihan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi' => $request->lokasi,
            'penyelenggara' => $request->penyelenggara,
            'dokumen_pelatihan' => $dokumenPath, // Menyimpan path dokumen pelatihan di database
            'mk_list' => $request->has('mk_list') ? json_encode($request->input('mk_list')) : null, // Store mk_list as JSON
            'bidang_minat_list' => $request->has('bidang_minat_list') ? json_encode($request->input('bidang_minat_list')) : null, // Store bidang_minat_list as JSON
        ]);

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
            'pengguna.jenisPengguna',
            'penyelenggara'
        ])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Decode the JSON for mk_list and retrieve mata kuliah names
        $mataKuliahNames = [];
        if ($pelatihan->mk_list) {
            $mkListArray = json_decode($pelatihan->mk_list);
            foreach ($mkListArray as $idMk) {
                $mataKuliah = MataKuliahModel::find($idMk);
                if ($mataKuliah) {
                    $mataKuliahNames[] = $mataKuliah->nama_mk;
                }
            }
        }

        // Decode the JSON for bidang_minat_list and retrieve bidang minat names
        $bidangMinatNames = [];
        if ($pelatihan->bidang_minat_list) {
            $bidangMinatListArray = json_decode($pelatihan->bidang_minat_list);
            foreach ($bidangMinatListArray as $idBidangMinat) {
                $bidangMinat = BidangMinatModel::find($idBidangMinat);
                if ($bidangMinat) {
                    $bidangMinatNames[] = $bidangMinat->nama_bidang_minat;
                }
            }
        }

        return view('riwayat_pelatihan.show_ajax', compact('pelatihan', 'mataKuliahNames', 'bidangMinatNames'));
    }

    public function edit_ajax(string $id)
    {
        // Filter 'pengguna' dengan relasi 'dosen' dan 'tendik', lalu memuat relasi terkait
        $pelatihan = RiwayatPelatihanModel::with([
            'pengguna.dosen',      // Relasi pengguna -> dosen
            'pengguna.tendik',     // Relasi pengguna -> tendik
            'mataKuliah',          // Relasi mata kuliah
            'bidangMinat',         // Relasi bidang minat
            'daftarPelatihan',     // Relasi daftar pelatihan
            'penyelenggara'        // Relasi penyelenggara
        ])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Retrieve the list of pengguna (user), mataKuliah, bidangMinat, and daftarPelatihan
        $pengguna = Pengguna::all();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();
        $daftarPelatihan = DaftarPelatihanModel::all();
        $penyelenggara = VendorPelatihanModel::all();

        // Pass all the variables to the view
        return view('riwayat_pelatihan.edit_ajax', compact('pelatihan', 'pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan', 'penyelenggara'));
    }


    public function update_ajax(Request $request, $id)
    {
        // Validasi input
        $rules = [
            'id_pengguna' => 'exists:pengguna,id_pengguna',
            'id_pelatihan' => 'nullable|exists:daftar_pelatihan,id_pelatihan',
            'level_pelatihan' => 'required|in:Nasional,Internasional',
            'nama_pelatihan' => 'required|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string|max:100',
            'penyelenggara' => 'nullable|string|max:100',
            'dokumen_pelatihan' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,pdf,docx,xlsx|max:10240',
            'mk_list' => 'nullable|array',
            'mk_list.*' => 'exists:mata_kuliah,id_mata_kuliah',
            'bidang_minat_list' => 'nullable|array',
            'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        // Cari riwayat pelatihan berdasarkan ID
        $pelatihan = RiwayatPelatihanModel::find($id);
        if (!$pelatihan) {
            return response()->json([
                'status' => false,
                'message' => 'Riwayat Pelatihan tidak ditemukan.'
            ]);
        }

        // Update dokumen pelatihan jika ada
        $dokumenPath = $pelatihan->dokumen_pelatihan;
        if ($request->hasFile('dokumen_pelatihan') && $request->file('dokumen_pelatihan')->isValid()) {
            // Hapus dokumen lama jika ada
            if ($dokumenPath && Storage::exists('public/' . $dokumenPath)) {
                Storage::delete('public/' . $dokumenPath);
            }
            // Simpan dokumen baru
            $dokumenPath = $request->file('dokumen_pelatihan')->store('dokumen_pelatihan', 'public');
        }

        // Update data pelatihan
        $updateData = [
            'id_pengguna' => $request->id_pengguna,
            'id_pelatihan' => $request->id_pelatihan,
            'level_pelatihan' => $request->level_pelatihan,
            'nama_pelatihan' => $request->nama_pelatihan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi' => $request->lokasi,
            'penyelenggara' => $request->penyelenggara,
            'dokumen_pelatihan' => $dokumenPath,
        ];

        // Update mk_list jika ada
        if ($request->filled('mk_list')) {
            $updateData['mk_list'] = json_encode($request->mk_list);
        }

        // Update bidang_minat_list jika ada
        if ($request->filled('bidang_minat_list')) {
            $updateData['bidang_minat_list'] = json_encode($request->bidang_minat_list);
        }

        // Simpan pembaruan ke database
        $pelatihan->update($updateData);

        return response()->json([
            'status' => true,
            'message' => 'Riwayat Pelatihan berhasil diperbarui.'
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