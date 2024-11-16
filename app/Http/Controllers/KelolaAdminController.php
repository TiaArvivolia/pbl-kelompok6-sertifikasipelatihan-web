<?php

namespace App\Http\Controllers;

use App\Models\KelolaAdminModel;
use App\Models\Pengguna;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->addColumn('aksi', function ($admin) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/' . $admin->id_admin . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi']) // Ensure action column supports HTML
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
            'gambar_profil' => $request->file('gambar_profil') ? $request->file('gambar_profil')->store('profile_pictures') : null, // Simpan file jika ada
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

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'no_telepon'  => 'required|string|max:15',
                'email' => 'required|string|email|max:100',
                'username' => 'nullable|string|max:50|unique:pengguna,username,' . $id . ',id_pengguna',
                'password' => 'nullable|string|min:8|confirmed',
                'gambar_profil' => 'nullable|file|mimes:jpeg,png,jpg|max:2048', // Validasi file
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Cari data admin
            $admin = KelolaAdminModel::find($id);

            if ($admin) {
                // Update data admin
                $admin->update($request->only(['nama_lengkap', 'nip', 'no_telepon', 'email']));

                // Tangani unggahan file
                if ($request->hasFile('gambar_profil')) {
                    $file = $request->file('gambar_profil');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads/admin', $fileName, 'public');

                    // // Hapus file lama jika ada
                    // if ($admin->gambar_profil && \Storage::exists('public/' . $admin->gambar_profil)) {
                    //     \Storage::delete('public/' . $admin->gambar_profil);
                    // }

                    // Simpan path file baru
                    $admin->gambar_profil = $filePath;
                    $admin->save();
                }

                // Update tabel pengguna
                $pengguna = $admin->pengguna; // Relasi ke model Pengguna
                if ($pengguna) {
                    $penggunaData = [];
                    if ($request->filled('username')) {
                        $penggunaData['username'] = $request->input('username');
                    }
                    if ($request->filled('password')) {
                        $penggunaData['password'] = bcrypt($request->input('password')); // Hash password
                    }
                    if (!empty($penggunaData)) {
                        $pengguna->update($penggunaData);
                    }
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Data admin berhasil diperbarui.',
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Admin tidak ditemukan.',
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
}