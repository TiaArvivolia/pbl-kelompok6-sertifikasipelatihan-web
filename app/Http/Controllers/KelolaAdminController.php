<?php

namespace App\Http\Controllers;

use App\Models\KelolaAdminModel;
use App\Models\Pengguna;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KelolaAdminController extends Controller
{
    // Display the initial Kelola Admin page
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Admin',
            'list' => ['Home', 'Admin']
        ];

        $page = (object) [
            'title' => 'Daftar admin yang terdaftar dalam sistem'
        ];

        $activeMenu = 'admin'; // Set active menu

        return view('admin.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data admin dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $admin = KelolaAdminModel::select('id_admin', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil')
            ->with('pengguna');

        return DataTables::of($admin)
            ->addIndexColumn()
            ->addColumn('gambar_profil', function ($admin) {
                // Cek apakah ada gambar_profil
                if ($admin->gambar_profil) {
                    $url = asset('storage/' . $admin->gambar_profil); // Pastikan folder `storage` bisa diakses
                    return '<img src="' . $url . '"  width="150" height="150" class="img-thumbnail">';
                }
                return '<span class="text-muted">Tidak ada gambar</span>'; // Placeholder jika gambar kosong
            })
            ->addColumn('aksi', function ($admin) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';

                return $btn;
            })
            ->rawColumns(['gambar_profil', 'aksi']) // Pastikan kolom 'gambar_profil' mendukung HTML
            ->make(true);
    }

    public function create_ajax()
    {
        // $pengguna = Pengguna::select('id_pengguna', 'username')->get();

        // return view('admin.create_ajax')->with('pengguna', $pengguna);
        return view('admin.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:pengguna,username',
            'password' => 'required|string|min:8|confirmed', // Menambahkan 'confirmed' untuk memvalidasi password dan konfirmasi password
            'password_confirmation' => 'required|string|min:8', // Pastikan kolom password_confirmation diisi
            'nama_lengkap' => 'required|string|max:100',
            'nip' => 'required|string|max:20',
            'no_telepon' => 'required|string|max:15',
            'email' => 'required|string|email|max:100',
        ]);

        // Simpan data ke tabel pengguna dan dapatkan ID
        $id_pengguna = DB::table('pengguna')->insertGetId([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')), // Hash password
            'id_jenis_pengguna' => 1, // Set sebagai admin
        ]);

        // Simpan data ke tabel admin
        DB::table('admin')->insert([
            'id_pengguna' => $id_pengguna,
            'nama_lengkap' => $request->input('nama_lengkap'),
            'nip' => $request->input('nip'),
            'no_telepon' => $request->input('no_telepon'),
            'email' => $request->input('email'),
            'gambar_profil' => $request->file('gambar_profil') ? $request->file('gambar_profil')->store('profile_pictures', 'public') : null, // Simpan file jika ada
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data admin berhasil disimpan!',
        ]);

        return redirect('/');
    }


    public function show_ajax(string $id)
    {
        $admin = KelolaAdminModel::with('pengguna')->find($id);
        return view('admin.show_ajax', ['admin' => $admin]);
    }

    // Show form for editing Admin via AJAX
    public function edit_ajax(string $id)
    {
        $admin = KelolaAdminModel::find($id);
        $pengguna = Pengguna::select('id_pengguna', 'username')->get();

        return view('admin.edit_ajax', ['admin' => $admin, 'pengguna' => $pengguna]);
    }

    // Update Admin data via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
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

            // Mencari admin berdasarkan ID
            $admin = KelolaAdminModel::find($id);
            if ($admin) {
                // Tangani penggantian gambar profil jika ada
                if ($request->hasFile('gambar_profil')) {
                    // Menghapus gambar lama jika ada
                    if ($admin->gambar_profil && Storage::disk('public')->exists($admin->gambar_profil)) {
                        Storage::disk('public')->delete($admin->gambar_profil);
                    }

                    // Menyimpan gambar baru
                    $path = $request->file('gambar_profil')->store('profile_pictures', 'public');
                    $admin->gambar_profil = $path;
                }

                // Update data admin selain gambar profil
                $admin->update($request->except(['gambar_profil', 'username', 'password']));

                // Cek dan update data pengguna (username dan password)
                if ($request->filled('username') || $request->filled('password')) {
                    $pengguna = Pengguna::where('id_pengguna', $admin->id_pengguna)->first();

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
                    'message' => 'Data admin dan pengguna berhasil diperbarui.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Admin tidak ditemukan'
            ]);
        }
    }

    // Menampilkan konfirmasi penghapusan pengguna dengan AJAX
    public function confirm_ajax(string $id)
    {
        $admin = KelolaAdminModel::find($id);
        return view('admin.confirm_ajax', ['admin' => $admin]);
    }


    // Delete Admin via AJAX
    public function delete_ajax(string $id)
    {
        try {
            KelolaAdminModel::destroy($id);
            return response()->json([
                'status'  => true,
                'message' => 'Data admin berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Data admin gagal dihapus'
            ]);
        }
    }

    public function export_pdf()
    {
        // Fetch admin data
        $admins = KelolaAdminModel::select('id_admin', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email')
            ->with('pengguna')
            ->get();


        // Share data with the view
        $pdf = Pdf::loadView('admin.export_pdf', ['admins' => $admins]);
        $pdf->setPaper('a4', 'portrait'); // Ukuran dan orientasi kertas

        return $pdf->stream('Data_Admin_' . date('Y-m-d_H-i-s') . '.pdf');
    }
    public function export_excel()
    {
        $admins = KelolaAdminModel::select('id_admin', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Admin');
        $sheet->setCellValue('C1', 'ID Pengguna');
        $sheet->setCellValue('D1', 'Nama Lengkap');
        $sheet->setCellValue('E1', 'NIP');
        $sheet->setCellValue('F1', 'No Telepon');
        $sheet->setCellValue('G1', 'Email');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Isi data
        $row = 2;
        foreach ($admins as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->id_admin);
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

        // Save file Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_Admin_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}