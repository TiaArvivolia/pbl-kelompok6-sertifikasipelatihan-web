<?php

namespace App\Http\Controllers;

use App\Models\KelolaPimpinanModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KelolaPimpinanController extends Controller
{
    // Display the initial Kelola Pimpinan page
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pimpinan',
            'list' => ['Home', 'Pimpinan']
        ];

        $page = (object) [
            'title' => 'Daftar pimpinan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pimpinan';

        return view('pimpinan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Retrieve Pimpinan data in JSON for DataTables
    public function list(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $pimpinan = KelolaPimpinanModel::select('id_pimpinan', 'id_pengguna', 'nama_lengkap', 'nip', 'nidn', 'no_telepon', 'email', 'gambar_profil')
            ->with('pengguna');

        // Filter by logged-in user's pimpinan data
        if ($user->id_jenis_pengguna == 4) { // If the user is a pimpinan
            $pimpinan->where('id_pengguna', $user->id_pengguna);
        }

        return DataTables::of($pimpinan)
            ->addIndexColumn()
            ->addColumn('gambar_profil', function ($pimpinan) {
                // Cek apakah ada gambar_profil
                if ($pimpinan->gambar_profil) {
                    $url = asset('storage/' . $pimpinan->gambar_profil); // Pastikan folder storage bisa diakses
                    return '<img src="' . $url . '"  width="150" height="150" class="img-thumbnail">';
                }
                return '<span class="text-muted">Tidak ada gambar</span>'; // Placeholder jika gambar kosong
            })
            ->addColumn('aksi', function ($pimpinan) {
                $user = auth()->user();
                $btn = '<button onclick="modalAction(\'' . url('/pimpinan/' . $pimpinan->id_pimpinan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pimpinan/' . $pimpinan->id_pimpinan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                if ($user->id_jenis_pengguna == 1) {
                    $btn .= '<button onclick="modalAction(\'' . url('/pimpinan/' . $pimpinan->id_pimpinan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
                }
                return $btn;
            })
            ->rawColumns(['gambar_profil', 'aksi']) // Pastikan kolom 'gambar_profil' mendukung HTML
            ->make(true);
    }

    // Show the create form for Pimpinan via AJAX
    public function create_ajax()
    {
        $pengguna = Pengguna::select('id_pengguna', 'username')->get();

        return view('pimpinan.create_ajax')->with('pengguna', $pengguna);
    }

    // Store new Pimpinan data via AJAX
    public function store_ajax(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:pengguna,username',  // Validasi username
            'password' => 'required|string|min:8|confirmed',  // Validasi password dan konfirmasi
            'password_confirmation' => 'required|string|min:8',  // Validasi konfirmasi password
            'nama_lengkap' => 'required|string|max:100',  // Nama lengkap
            'nip' => 'required|string|max:20',  // NIP
            'nidn' => 'required|string|max:20',  // NIP
            'no_telepon' => 'required|string|max:15',  // Nomor telepon
            'email' => 'required|string|email|max:100',  // Email
            'gambar_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        // Simpan data ke tabel pengguna dan dapatkan ID
        $id_pengguna = DB::table('pengguna')->insertGetId([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')), // Hash password
            'id_jenis_pengguna' => 4, // Set sebagai pimpinan
        ]);

        // Simpan data ke tabel pimpinan
        DB::table('pimpinan')->insert([
            'id_pengguna' => $id_pengguna,
            'nama_lengkap' => $request->input('nama_lengkap'),
            'nip' => $request->input('nip'),
            'nidn' => $request->input('nidn'),
            'no_telepon' => $request->input('no_telepon'),
            'email' => $request->input('email'),
            'gambar_profil' => $request->file('gambar_profil') ? $request->file('gambar_profil')->store('profile_pictures') : null, // Simpan file jika ada
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data pimpinan berhasil disimpan!',
        ]);

        return redirect('/');
    }

    // Display Pimpinan details via AJAX
    public function show_ajax(string $id)
    {
        $pimpinan = KelolaPimpinanModel::with('pengguna')->find($id);
        return view('pimpinan.show_ajax', ['pimpinan' => $pimpinan]);
    }

    // Show form for editing Pimpinan via AJAX
    public function edit_ajax(string $id)
    {
        $pimpinan = KelolaPimpinanModel::find($id);
        $pengguna = Pengguna::select('id_pengguna', 'username')->get();

        return view('pimpinan.edit_ajax', ['pimpinan' => $pimpinan, 'pengguna' => $pengguna]);
    }

    // Update Pimpinan data via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'nidn' => 'nullable|string|max:20',
                'no_telepon'  => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'gambar_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Gambar validasi
                'username' => 'nullable|string|max:100', // Validasi untuk username
                'password' => 'nullable|string|min:6|confirmed', // Validasi untuk password
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            // Mencari pimpinan berdasarkan ID
            $pimpinan = KelolaPimpinanModel::find($id);
            if ($pimpinan) {
                // Tangani penggantian gambar profil jika ada
                if ($request->hasFile('gambar_profil')) {
                    // Menghapus gambar lama jika ada
                    if ($pimpinan->gambar_profil && Storage::disk('public')->exists($pimpinan->gambar_profil)) {
                        Storage::disk('public')->delete($pimpinan->gambar_profil);
                    }

                    // Menyimpan gambar baru
                    $path = $request->file('gambar_profil')->store('profile_pictures', 'public');
                    $pimpinan->gambar_profil = $path;
                }

                // Update data pimpinan selain gambar profil
                $pimpinan->update($request->except(['gambar_profil', 'username', 'password']));

                // Cek dan update data pengguna (username dan password)
                if ($request->filled('username') || $request->filled('password')) {
                    $pengguna = Pengguna::where('id_pengguna', $pimpinan->id_pengguna)->first();

                    if ($pengguna) {
                        if ($request->filled('username')) {
                            $pengguna->username = $request->username;
                        }

                        if ($request->filled('password')) {
                            // Encrypt password before saving
                            $pengguna->password = bcrypt($request->password);
                        }

                        $pengguna->save();
                    }
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Data pimpinan dan pengguna berhasil diperbarui.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Pimpinan tidak ditemukan'
            ]);
        }
    }



    // Display delete confirmation for Pimpinan via AJAX
    public function confirm_ajax(string $id)
    {
        $pimpinan = KelolaPimpinanModel::find($id);
        return view('pimpinan.confirm_ajax', ['pimpinan' => $pimpinan]);
    }

    // Delete Pimpinan via AJAX
    public function delete_ajax(string $id)
    {
        try {
            KelolaPimpinanModel::destroy($id);
            return response()->json([
                'status'  => true,
                'message' => 'Data pimpinan berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Data pimpinan gagal dihapus'
            ]);
        }
    }
    public function export_pdf()
    {
        // Ambil data pimpinan
        $pimpinan = KelolaPimpinanModel::select('id_pimpinan', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil')
            ->with('pengguna')
            ->get();

        // Share data dengan view
        $pdf = Pdf::loadView('pimpinan.export_pdf', ['pimpinan' => $pimpinan]);
        $pdf->setPaper('a4', 'portrait'); // Ukuran kertas dan orientasi

        return $pdf->stream('Data_Pimpinan_' . date('Y-m-d_H-i-s') . '.pdf');
    }
    public function export_excel()
    {
        // Ambil data pimpinan
        $pimpinan = KelolaPimpinanModel::select('id_pimpinan', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil')
            ->with('pengguna')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        // Buat spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Pimpinan');
        $sheet->setCellValue('C1', 'ID Pengguna');
        $sheet->setCellValue('D1', 'Nama Lengkap');
        $sheet->setCellValue('E1', 'NIP');
        $sheet->setCellValue('F1', 'No Telepon');
        $sheet->setCellValue('G1', 'Email');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Isi data
        $row = 2;
        foreach ($pimpinan as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->id_pimpinan);
            $sheet->setCellValue('C' . $row, $data->id_pengguna);
            $sheet->setCellValue('D' . $row, $data->nama_lengkap);
            $sheet->setCellValue('E' . $row, $data->nip);
            $sheet->setCellValue('F' . $row, $data->no_telepon);
            $sheet->setCellValue('G' . $row, $data->email);
            $row++;
        }

        // Auto size kolom
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Simpan file Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_Pimpinan_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
