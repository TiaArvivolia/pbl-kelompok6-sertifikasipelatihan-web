<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\KelolaDosenModel;
use App\Models\MataKuliahModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
<<<<<<< HEAD
use Illuminate\Support\Facades\Storage;

=======
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
>>>>>>> 1c5563fd55489ba71a6b0190fa2840e2b00765b7

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
            ->addColumn('gambar_profil', function ($dosen) {
                // Cek apakah ada gambar_profil
                if ($dosen->gambar_profil) {
                    $url = asset('storage/' . $dosen->gambar_profil); // Pastikan folder `storage` bisa diakses
                    return '<img src="' . $url . '"  width="150" height="150" class="img-thumbnail">';
                }
                return '<span class="text-muted">Tidak ada gambar</span>'; // Placeholder jika gambar kosong
            })
            ->addColumn('aksi', function ($dosen) {
                $btn = '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/dosen/' . $dosen->id_dosen . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
                return $btn;
            })
            ->rawColumns(['gambar_profil', 'aksi']) // Pastikan kolom 'gambar_profil' mendukung HTML
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
        // Validate the input fields
        $request->validate([
            'username' => 'required|string|max:50|unique:pengguna,username', // Unique username
            'password' => 'required|string|min:8|confirmed', // Ensure password is confirmed
            'password_confirmation' => 'required|string|min:8', // Confirmation password field
            'nama_lengkap' => 'required|string|max:100',
            'nip' => 'required|string|max:20',
            'nidn' => 'required|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
            'tag_mk' => 'required|exists:mata_kuliah,id_mata_kuliah', // Validates mata kuliah tag
            'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat', // Validates bidang minat tag
        ]);

        // Insert into the pengguna table and get the ID
        $id_pengguna = DB::table('pengguna')->insertGetId([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')), // Hash the password before saving
            'id_jenis_pengguna' => 2, // Set as dosen (id_jenis_pengguna for dosen)
        ]);

        // Save dosen details to the kelola_dosen table
        DB::table('dosen')->insert([
            'id_pengguna' => $id_pengguna,
            'nama_lengkap' => $request->input('nama_lengkap'),
            'nip' => $request->input('nip'),
            'nidn' => $request->input('nidn'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'no_telepon' => $request->input('no_telepon'),
            'email' => $request->input('email'),
            'tag_mk' => $request->input('tag_mk'),
            'tag_bidang_minat' => $request->input('tag_bidang_minat'),
            'gambar_profil' => $request->file('gambar_profil') ? $request->file('gambar_profil')->store('profile_pictures', 'public') : null, // Handle optional profile picture upload
        ]);

        // Return a successful response
        return response()->json([
            'status' => true,
            'message' => 'Data dosen berhasil disimpan'
        ]);

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
                'tag_mk' => 'required|exists:mata_kuliah,id_mata_kuliah',
                'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat',
                'gambar_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
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
                // Update mata kuliah dan bidang minat
                $dosen->mataKuliah()->associate(MataKuliahModel::find($request->tag_mk));
                $dosen->bidangMinat()->associate(BidangMinatModel::find($request->tag_bidang_minat));
    
                // Tangani penggantian gambar
                if ($request->hasFile('gambar_profil')) {
                    // Hapus gambar lama jika ada
                    if ($dosen->gambar_profil && Storage::disk('public')->exists($dosen->gambar_profil)) {
                        Storage::disk('public')->delete($dosen->gambar_profil);
                    }
    
                    // Simpan gambar baru
                    $path = $request->file('gambar_profil')->store('profile_pictures', 'public');
                    $dosen->gambar_profil = $path;
                }
    
                // Update data dosen
                $dosen->update($request->except(['tag_mk', 'tag_bidang_minat', 'gambar_profil']));
    
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
    
public function export_pdf()
{
    // Fetch admin data
    $dosen = KelolaDosenModel::select('id_dosen', 'id_pengguna', 'nama_lengkap', 'nip', 'nidn', 'tempat_lahir', 'tanggal_lahir', 'no_telepon', 'email')
        ->with('pengguna')
        ->get();


    // Share data with the view
    $pdf = Pdf::loadView('dosen.export_pdf', ['dosen' => $dosen]);
    $pdf->setPaper('a4', 'landscape'); // Ukuran dan orientasi kertas

    return $pdf->stream('Data_Dosen_' . date('Y-m-d_H-i-s') . '.pdf');
}
public function export_excel()
{
    $dosen = KelolaDosenModel::select('id_dosen', 'id_pengguna', 'nama_lengkap', 'nip', 'nidn', 'tempat_lahir', 'tanggal_lahir', 'no_telepon', 'email')
        ->orderBy('nama_lengkap', 'asc')
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'ID Dosen');
    $sheet->setCellValue('C1', 'ID Pengguna');
    $sheet->setCellValue('D1', 'Nama Lengkap');
    $sheet->setCellValue('E1', 'NIP');
    $sheet->setCellValue('F1', 'NIDN');
    $sheet->setCellValue('G1', 'Tempat Lahir');
    $sheet->setCellValue('H1', 'Tanggal Lahir');
    $sheet->setCellValue('I1', 'No Telepon');
    $sheet->setCellValue('J1', 'Email');
    $sheet->getStyle('A1:J1')->getFont()->setBold(true);

    // Isi data
    $row = 2;
    foreach ($dosen as $index => $data) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $data->id_dosen);
        $sheet->setCellValue('C' . $row, $data->id_pengguna);
        $sheet->setCellValue('D' . $row, $data->nama_lengkap);
        $sheet->setCellValue('E' . $row, $data->nip);
        $sheet->setCellValue('F' . $row, $data->nidn);
        $sheet->setCellValue('G' . $row, $data->tempat_lahir);
        $sheet->setCellValue('H' . $row, $data->tanggal_lahir);
        $sheet->setCellValue('I' . $row, $data->no_telepon);
        $sheet->setCellValue('J' . $row, $data->email);
        $row++;
    }

    // Auto size kolom
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Save file Excel
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Data_Dosen_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

}