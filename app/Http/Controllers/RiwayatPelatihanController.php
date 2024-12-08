<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\MataKuliahModel;
use App\Models\Pengguna;
use App\Models\DaftarPelatihanModel;
use App\Models\PeriodeModel;
use App\Models\RiwayatPelatihanModel;
use App\Models\VendorPelatihanModel;
use App\Models\VendorSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
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
        // Get the logged-in user
        $user = auth()->user();

        // Create the base query for RiwayatPelatihan with relationships
        $query = RiwayatPelatihanModel::with(['pengguna.dosen', 'pengguna.tendik', 'pengguna.jenisPengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan'])
            ->select('id_riwayat', 'id_pengguna', 'nama_pelatihan', 'level_pelatihan', 'lokasi', 'penyelenggara', 'tanggal_mulai', 'tanggal_selesai');

        // If the user is a lecturer (dosen), filter the records by their ID
        if ($user->id_jenis_pengguna === 2 || $user->id_jenis_pengguna === 3) {
            $query->where('id_pengguna', $user->id_pengguna); // Assuming the logged-in user's ID is stored in 'id' and associated with 'id_pengguna'
        }

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
                return $riwayat->pengguna && $riwayat->pengguna->jenisPengguna ? $riwayat->pengguna->jenisPengguna->nama_jenis_pengguna : '-';
            })

            ->addColumn('aksi', function ($pelatihan) {
                // Dapatkan pengguna yang sedang login
                $user = auth()->user();
                // Cek apakah pengguna adalah pimpinan (role 4)
                if ($user->id_jenis_pengguna === 4) {
                    // Hanya tampilkan tombol "Detail" untuk pimpinan
                    $btn = '<button onclick="modalAction(\'' . url('/riwayat_pelatihan/' . $pelatihan->id_riwayat . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                    return $btn;
                }

                // Jika bukan pimpinan, tampilkan tombol "Detail", "Edit", dan "Hapus"
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
        // Mengambil data pengguna yang sedang login
        $user = auth()->user();

        // Inisialisasi query untuk mengambil data 'pengguna'
        $penggunaQuery = Pengguna::with(['dosen', 'tendik']) // Mengambil relasi dosen dan tendik
            ->where(function ($query) use ($user) {
                // Jika pengguna adalah admin (diasumsikan 'id_jenis_pengguna' untuk admin adalah 1)
                if ($user->id_jenis_pengguna === 1) {
                    // Admin hanya bisa melihat dosen dan tendik
                    $query->whereHas('dosen') // Memastikan relasi dengan dosen ada
                        ->orWhereHas('tendik'); // Atau relasi dengan tendik ada
                } else {
                    // Jika pengguna bukan admin (misalnya dosen atau tendik), hanya dapat melihat riwayat mereka sendiri
                    $query->where('id_pengguna', $user->id_pengguna) // Filter berdasarkan id_pengguna pengguna yang sedang login
                        ->where(function ($subQuery) {
                            // Memastikan bahwa pengguna memiliki relasi dengan dosen atau tendik
                            $subQuery->whereHas('dosen')->orWhereHas('tendik');
                        });
                }
            });

        // Mendapatkan data pengguna yang telah difilter
        $pengguna = $penggunaQuery->get();

        // Mengambil data lainnya yang dibutuhkan untuk tampilan
        $mataKuliah = MataKuliahModel::all(); // Mengambil semua mata kuliah
        $bidangMinat = BidangMinatModel::all(); // Mengambil semua bidang minat
        $daftarPelatihan = DaftarPelatihanModel::all(); // Mengambil semua daftar pelatihan
        $penyelenggara = VendorPelatihanModel::all(); // Mengambil semua penyelenggara pelatihan
        $periode = PeriodeModel::all(); // Mengambil semua periode pelatihan

        // Mengembalikan tampilan dengan data yang diperlukan
        return view('riwayat_pelatihan.create_ajax', compact('pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan', 'penyelenggara', 'periode'));
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
            'id_periode' => 'required|exists:periode,id_periode',
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
            'id_periode' => $request->id_periode,
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
            'penyelenggara',
            'periode'
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
            'pengguna',
            'pengguna',
            'mataKuliah',
            'bidangMinat',
            'daftarPelatihan',
            'penyelenggara',
            'periode'
        ])->find($id);

        if (!$pelatihan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }
        // Mengambil data pengguna yang sedang login
        $user = auth()->user();

        // Inisialisasi query untuk mengambil data 'pengguna'
        $penggunaQuery = Pengguna::with(['dosen', 'tendik']) // Mengambil relasi dosen dan tendik
            ->where(function ($query) use ($user) {
                // Jika pengguna adalah admin (diasumsikan 'id_jenis_pengguna' untuk admin adalah 1)
                if ($user->id_jenis_pengguna === 1) {
                    // Admin hanya bisa melihat dosen dan tendik
                    $query->whereHas('dosen') // Memastikan relasi dengan dosen ada
                        ->orWhereHas('tendik'); // Atau relasi dengan tendik ada
                } else {
                    // Jika pengguna bukan admin (misalnya dosen atau tendik), hanya dapat melihat riwayat mereka sendiri
                    $query->where('id_pengguna', $user->id_pengguna) // Filter berdasarkan id_pengguna pengguna yang sedang login
                        ->where(function ($subQuery) {
                            // Memastikan bahwa pengguna memiliki relasi dengan dosen atau tendik
                            $subQuery->whereHas('dosen')->orWhereHas('tendik');
                        });
                }
            });

        // Mendapatkan data pengguna yang telah difilter
        $pengguna = $penggunaQuery->get();
        $mataKuliah = MataKuliahModel::all();
        $bidangMinat = BidangMinatModel::all();
        $daftarPelatihan = DaftarPelatihanModel::all();
        $penyelenggara = VendorPelatihanModel::all();
        $periode = PeriodeModel::all();

        // Pass all the variables to the view
        return view('riwayat_pelatihan.edit_ajax', compact('pelatihan', 'pengguna', 'mataKuliah', 'bidangMinat', 'daftarPelatihan', 'penyelenggara', 'periode'));
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
            'id_periode' => 'nullable|string|max:100',
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
            'id_periode' => $request->id_periode,
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

    public function export_pdf()
    {
        $riwayat_pelatihan = RiwayatPelatihanModel::with(['pengguna', 'daftarPelatihan', 'penyelenggara', 'periode'])
            ->select('id_riwayat', 'id_pengguna', 'id_pelatihan', 'level_pelatihan', 'nama_pelatihan', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'penyelenggara', 'dokumen_pelatihan', 'id_periode')
            ->orderBy('tanggal_mulai', 'asc') // Change to a valid column
            ->get();
    
        $pdf = Pdf::loadView('riwayat_pelatihan.export_pdf', compact('riwayat_pelatihan'));
        $pdf  ->setPaper('a4', 'landscape'); // Set paper size and orientation
    
        return $pdf->stream('Data_riwayat_pelatihan_' . date('Y-m-d_H-i-s') . '.pdf');
    }
    
    public function export_excel()
{
    $riwayat_pelatihan = RiwayatPelatihanModel::with(['pengguna', 'daftarPelatihan', 'penyelenggara', 'periode'])
        ->select('id_riwayat', 'id_pengguna', 'id_pelatihan', 'level_pelatihan', 'nama_pelatihan', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'penyelenggara', 'dokumen_pelatihan', 'id_periode')
        ->orderBy('tanggal_mulai', 'asc') // Change to a valid column
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header columns
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Level Pelatihan');
    $sheet->setCellValue('C1', 'Nama Pelatihan');
    $sheet->setCellValue('D1', 'Tanggal Mulai');
    $sheet->setCellValue('E1', 'Tanggal Selesai');
    $sheet->setCellValue('F1', 'Lokasi');
    $sheet->setCellValue('G1', 'Penyelenggara');
    $sheet->setCellValue('H1', 'Dokumen');
    $sheet->setCellValue('I1', 'ID Periode');
    $sheet->getStyle('A1:I1')->getFont()->setBold(true);

    // Fill data
    $row = 2;
    foreach ($riwayat_pelatihan as $index => $data) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $data->level_pelatihan);
        $sheet->setCellValue('C' . $row, $data->nama_pelatihan);
        $sheet->setCellValue('D' . $row, $data->tanggal_mulai);
        $sheet->setCellValue('E' . $row, $data->tanggal_selesai);
        $sheet->setCellValue('F' . $row, $data->lokasi);
        $sheet->setCellValue('G' . $row, $data->penyelenggara);
        $sheet->setCellValue('H' . $row, $data->dokumen_pelatihan);
        $sheet->setCellValue('I' . $row, $data->id_periode);
        $row++;
    }

    // Auto size columns
        foreach (range('A', 'I') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Save Excel file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Data_Riwayat_Pelatihan_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}
}