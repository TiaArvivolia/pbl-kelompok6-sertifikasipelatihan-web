<?php

namespace App\Http\Controllers;

use App\Models\KelolaPimpinanModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KelolaPimpinanController extends Controller
{
    // Display the initial Kelola Pimpinan page
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pimpinan',
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
        $pimpinan = KelolaPimpinanModel::select('id_pimpinan', 'id_pengguna', 'nama_lengkap', 'nip', 'nidn', 'no_telepon', 'email', 'gambar_profil')
            ->with('pengguna');

        return DataTables::of($pimpinan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pimpinan) {
                $btn = '<button onclick="modalAction(\'' . url('/pimpinan/' . $pimpinan->id_pimpinan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pimpinan/' . $pimpinan->id_pimpinan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pimpinan/' . $pimpinan->id_pimpinan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi'])
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
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'          => 'required|string|max:20',
                'nidn'         => 'nullable|string|max:20',
                'no_telepon'   => 'nullable|string|max:20',
                'email'        => 'nullable|string|email|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $pimpinan = KelolaPimpinanModel::find($id);
            if ($pimpinan) {
                $pimpinan->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data pimpinan berhasil diperbarui.'
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
}