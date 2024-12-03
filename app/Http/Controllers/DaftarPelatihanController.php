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
        )->with(['pengajuan', 'vendorPelatihan', 'mataKuliah', 'bidangMinat']);

        return DataTables::of($pelatihan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pelatihan) {
                $user = auth()->user();

                // Check if the user is a lecturer or staff
                if ($user->id_jenis_pengguna == 2 || $user->id_jenis_pengguna == 3) {
                    // For lecturers and staff, only show the "Detail" button for Pelatihan
                    return '<button onclick="modalAction(\'' . url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button>';
                }

                // For admins, show all CRUD buttons for Pelatihan
                $btn = '<button onclick="modalAction(\'' . url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/daftar_pelatihan/' . $pelatihan->id_pelatihan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
                return $btn;
            })
            ->addColumn('pengajuan', function ($pelatihan) {
                if (isset($pelatihan->pengajuan)) {
                    $pengajuan = $pelatihan->pengajuan;
                    $btn = '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';

                    // Admins can perform CRUD operations on Pengajuan
                    if (auth()->user()->id_jenis_pengguna == 1) {
                        $btn .= '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                        $btn .= '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
                    }

                    return $btn;
                }

                // If there's no Pengajuan, show the button to add a new Pengajuan
                return '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/create_ajax') . '\')" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Pengajuan Pelatihan
                        </button>';
            })
            ->rawColumns(['aksi', 'pengajuan'])
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
            // Definisikan aturan validasi
            $rules = [
                'nama_pelatihan'    => 'required|string|max:100',
                'level_pelatihan'   => 'required|in:Nasional,Internasional',
                'tanggal_mulai'     => 'nullable|date',
                'tanggal_selesai'   => 'nullable|date',
                'kuota'             => 'nullable|integer',
                'lokasi'            => 'nullable|string|max:100',
                'biaya'             => 'nullable|numeric',
                'jml_jam'           => 'nullable|integer',
                'id_vendor_pelatihan' => 'required|exists:vendor_pelatihan,id_vendor_pelatihan',
                'mk_list'           => 'nullable|array', // Pastikan mk_list adalah array jika ada
                'mk_list.*'         => 'exists:mata_kuliah,id_mata_kuliah', // Validasi setiap elemen mk_list
                'bidang_minat_list' => 'nullable|array', // Pastikan bidang_minat_list adalah array jika ada
                'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat', // Validasi setiap elemen bidang_minat_list
            ];

            // Lakukan validasi
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Persiapkan data untuk disimpan
            $data = $request->all();
            $data['mk_list'] = $request->has('mk_list') ? json_encode($request->input('mk_list')) : null; // Ubah mk_list menjadi JSON
            $data['bidang_minat_list'] = $request->has('bidang_minat_list') ? json_encode($request->input('bidang_minat_list')) : null; // Ubah bidang_minat_list menjadi JSON

            // Simpan data ke database
            DaftarPelatihanModel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data pelatihan berhasil disimpan'
            ]);
        }

        // Jika bukan permintaan AJAX, arahkan ke halaman utama
        return redirect('/');
    }

    public function show_ajax($id)
    {
        $pelatihan = DaftarPelatihanModel::with(['vendorPelatihan', 'mataKuliah', 'bidangMinat'])->find($id);

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

        return view('daftar_pelatihan.show_ajax', compact('pelatihan', 'mataKuliahNames', 'bidangMinatNames'));
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
            // Definisikan aturan validasi
            $rules = [
                'nama_pelatihan'     => 'required|string|max:100',
                'level_pelatihan'    => 'required|in:Nasional,Internasional',
                'tanggal_mulai'      => 'nullable|date',
                'tanggal_selesai'    => 'nullable|date',
                'kuota'              => 'nullable|integer',
                'lokasi'             => 'nullable|string|max:100',
                'biaya'              => 'nullable|numeric',
                'jml_jam'            => 'nullable|integer',
                'id_vendor_pelatihan' => 'required|exists:vendor_pelatihan,id_vendor_pelatihan',
                'mk_list'            => 'nullable|array',
                'mk_list.*'          => 'exists:mata_kuliah,id_mata_kuliah',
                'bidang_minat_list'  => 'nullable|array',
                'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat',
            ];

            // Lakukan validasi
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            // Cari data pelatihan berdasarkan ID
            $pelatihan = DaftarPelatihanModel::find($id);
            if ($pelatihan) {
                // Siapkan data yang akan di-update
                $data = $request->except(['mk_list', 'bidang_minat_list']); // Kecualikan data array

                if ($request->filled('mk_list')) {
                    $data['mk_list'] = json_encode($request->mk_list); // Simpan `mk_list` sebagai JSON
                }

                if ($request->filled('bidang_minat_list')) {
                    $data['bidang_minat_list'] = json_encode($request->bidang_minat_list); // Simpan `bidang_minat_list` sebagai JSON
                }

                // Update data ke database
                $pelatihan->update($data);

                return response()->json([
                    'status'  => true,
                    'message' => 'Data pelatihan berhasil diperbarui.'
                ]);
            }

            // Jika data pelatihan tidak ditemukan
            return response()->json([
                'status'  => false,
                'message' => 'Data pelatihan tidak ditemukan.'
            ]);
        }

        // Jika bukan permintaan AJAX, arahkan ke halaman utama
        return redirect('/');
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
