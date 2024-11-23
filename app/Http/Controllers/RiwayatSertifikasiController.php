<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\DaftarPelatihanModel;
use App\Models\RiwayatSertifikasiModel;
use App\Models\VendorSertifikasiModel;
use App\Models\MataKuliahModel;
use App\Models\BidangMinatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RiwayatSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Sertifikasi',
            'list' => ['Home', 'Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Daftar Riwayat Sertifikasi'
        ];

        $activeMenu = 'riwayat_sertifikasi';

        return view('riwayat_sertifikasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        // Create the base query for RiwayatSertifikasi with relationships
        $query = RiwayatSertifikasiModel::with(['pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan'])
            ->select('id_riwayat', 'id_pengguna', 'level_sertifikasi', 'jenis_sertifikasi', 'nama_sertifikasi', 'no_sertifikat', 'tanggal_terbit', 'masa_berlaku');

        // Apply filter if level_sertifikasi is provided in the request
        if ($request->filter_level_sertifikasi) {
            $query->where('level_sertifikasi', $request->filter_level_sertifikasi);
        }

        // Return DataTables response
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_lengkap', function ($pelatihan) {
                return $pelatihan->pengguna->dosen ? $pelatihan->pengguna->dosen->nama_lengkap : ($pelatihan->pengguna->tendik ? $pelatihan->pengguna->tendik->nama_lengkap : 'Tidak Tersedia');
            })
            // ->addColumn('penyelenggara', function ($sertifikasi) {
            //     // Access the 'nama' property from the related 'penyelenggara' (VendorSertifikasiModel)
            //     return $sertifikasi->penyelenggara ? $sertifikasi->penyelenggara->nama : 'Tidak Tersedia';
            // })
            ->addColumn('aksi', function ($sertifikasi) {
                $btn = '<button onclick="modalAction(\'' . url('/riwayat_sertifikasi/' . $sertifikasi->id_riwayat . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/riwayat_sertifikasi/' . $sertifikasi->id_riwayat . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/riwayat_sertifikasi/' . $sertifikasi->id_riwayat . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button> ';

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
        $penyelenggara = VendorSertifikasiModel::all();

        return view('riwayat_sertifikasi.create_ajax', compact('pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan', 'penyelenggara'));
    }


    public function store_ajax(Request $request)
    {
        $rules = [
            'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'level_sertifikasi' => 'required|in:Nasional,Internasional',
            'jenis_sertifikasi' => 'required|in:Profesi,Keahlian',
            'nama_sertifikasi' => 'nullable|string|max:100',
            'no_sertifikat' => 'required|string|max:100',
            'tanggal_terbit' => 'required|date',
            'masa_berlaku' => 'nullable|date|after:tanggal_terbit',
            'dokumen_sertifikat' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,pdf,docx,xlsx|max:10240', // Maksimum ukuran file 10MB
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

        // Menangani dokumen sertifikat jika ada
        $dokumenPath = null;
        if ($request->hasFile('dokumen_sertifikat') && $request->file('dokumen_sertifikat')->isValid()) {
            // Mengambil file yang diunggah
            $file = $request->file('dokumen_sertifikat');
            // Menyimpan file di folder 'dokumen' di storage/public dan mendapatkan path file
            $dokumenPath = $file->store('dokumen', 'public');
        }

        // Menyimpan data riwayat sertifikasi
        RiwayatSertifikasiModel::create([
            'id_pengguna' => $request->id_pengguna,
            'level_sertifikasi' => $request->level_sertifikasi,
            'jenis_sertifikasi' => $request->jenis_sertifikasi,
            'nama_sertifikasi' => $request->nama_sertifikasi,
            'no_sertifikat' => $request->no_sertifikat,
            'tanggal_terbit' => $request->tanggal_terbit,
            'masa_berlaku' => $request->masa_berlaku,
            'penyelenggara' => $request->penyelenggara,
            'dokumen_sertifikat' => $dokumenPath, // Menyimpan path dokumen di database
            'mk_list' => $request->has('mk_list') ? json_encode($request->input('mk_list')) : null, // Store mk_list as JSON
            'bidang_minat_list' => $request->has('bidang_minat_list') ? json_encode($request->input('bidang_minat_list')) : null, // Store bidang_minat_list as JSON
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Riwayat Sertifikasi berhasil disimpan'
        ]);
    }

    public function show_ajax($id)
    {
        // Mengambil data riwayat sertifikasi beserta relasi pengguna, mata kuliah, bidang minat, dan penyelenggara
        $sertifikasi = RiwayatSertifikasiModel::with([
            'pengguna',
            'mataKuliah',
            'bidangMinat',
            'daftarPelatihan',
            'penyelenggara',
        ])->find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Decode the JSON for mk_list and retrieve mata kuliah names
        $mataKuliahNames = [];
        if ($sertifikasi->mk_list) {
            $mkListArray = json_decode($sertifikasi->mk_list);
            foreach ($mkListArray as $idMk) {
                $mataKuliah = MataKuliahModel::find($idMk);
                if ($mataKuliah) {
                    $mataKuliahNames[] = $mataKuliah->nama_mk;
                }
            }
        }

        // Decode the JSON for bidang_minat_list and retrieve bidang minat names
        $bidangMinatNames = [];
        if ($sertifikasi->bidang_minat_list) {
            $bidangMinatListArray = json_decode($sertifikasi->bidang_minat_list);
            foreach ($bidangMinatListArray as $idBidangMinat) {
                $bidangMinat = BidangMinatModel::find($idBidangMinat);
                if ($bidangMinat) {
                    $bidangMinatNames[] = $bidangMinat->nama_bidang_minat;
                }
            }
        }

        return view('riwayat_sertifikasi.show_ajax', compact('sertifikasi', 'mataKuliahNames', 'bidangMinatNames'));
    }

    public function edit_ajax(string $id)
    {
        $sertifikasi = RiwayatSertifikasiModel::with([
            'pengguna',
            'mataKuliah',
            'bidangMinat',
            'daftarPelatihan',
            'penyelenggara'
        ])->find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Retrieve the list of pengguna, mataKuliah, bidangMinat, and penyelenggara
        $pengguna = Pengguna::with(['dosen', 'tendik'])
            ->whereHas('dosen')
            ->orWhereHas('tendik')
            ->get();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();
        $daftarPelatihan = DaftarPelatihanModel::all();
        $penyelenggara = VendorSertifikasiModel::all();

        return view('riwayat_sertifikasi.edit_ajax', compact('sertifikasi', 'pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan', 'penyelenggara'));
    }

    public function update_ajax(Request $request, $id)
    {
        // Validasi input
        $rules = [
            'id_pengguna' => 'exists:pengguna,id_pengguna',
            'id_pelatihan' => 'nullable|exists:daftar_pelatihan,id_pelatihan',
            'level_sertifikasi' => 'required|in:Nasional,Internasional',
            'jenis_sertifikasi' => 'required|in:Profesi,Keahlian',
            'nama_sertifikasi' => 'nullable|string|max:100',
            'no_sertifikat' => 'required|string|max:100',
            'tanggal_terbit' => 'required|date',
            'masa_berlaku' => 'nullable|date|after:tanggal_terbit',
            'dokumen_sertifikat' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,pdf,docx,xlsx|max:10240', // Validasi untuk dokumen
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

        // Cari riwayat sertifikasi berdasarkan ID
        $sertifikasi = RiwayatSertifikasiModel::find($id);
        if ($sertifikasi) {
            // Menangani dokumen sertifikat jika ada file yang diunggah
            $dokumenPath = $sertifikasi->dokumen_sertifikat; // Ambil dokumen yang lama (jika ada)

            // Cek apakah dokumen lama ada dan hapus sebelum mengupdate
            if ($dokumenPath && Storage::exists('public/' . $dokumenPath)) {
                Storage::delete('public/' . $dokumenPath); // Hapus dokumen lama
            }

            // Menangani dokumen sertifikat baru jika ada file yang diunggah
            if ($request->hasFile('dokumen_sertifikat') && $request->file('dokumen_sertifikat')->isValid()) {
                // Mengambil file yang diunggah
                $file = $request->file('dokumen_sertifikat');
                // Menyimpan file di folder 'dokumen' di storage/public dan mendapatkan path file
                $dokumenPath = $file->store('dokumen', 'public');
            }

            // Perbarui riwayat sertifikasi dengan data baru
            $updateData = [
                'id_pengguna' => $request->id_pengguna,
                'id_pelatihan' => $request->id_pelatihan,
                'level_sertifikasi' => $request->level_sertifikasi,
                'jenis_sertifikasi' => $request->jenis_sertifikasi,
                'nama_sertifikasi' => $request->nama_sertifikasi,
                'no_sertifikat' => $request->no_sertifikat,
                'tanggal_terbit' => $request->tanggal_terbit,
                'masa_berlaku' => $request->masa_berlaku,
                'penyelenggara' => $request->penyelenggara,
                'dokumen_sertifikat' => $dokumenPath, // Menyimpan path dokumen yang baru
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
            $sertifikasi->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Riwayat Sertifikasi berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Riwayat Sertifikasi tidak ditemukan'
        ]);
    }



    public function confirm_ajax(string $id)
    {
        $sertifikasi = RiwayatSertifikasiModel::find($id);
        return view('riwayat_sertifikasi.confirm_ajax', ['sertifikasi' => $sertifikasi]);
    }

    public function delete_ajax(string $id)
    {
        try {
            $sertifikasi = RiwayatSertifikasiModel::findOrFail($id);
            $sertifikasi->delete();

            return response()->json([
                'status' => true,
                'message' => 'Riwayat Sertifikasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data riwayat sertifikasi.'
            ]);
        }
    }
}