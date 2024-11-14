<?php

namespace App\Http\Controllers;

use App\Models\KelolaAdminModel;
use App\Models\Pengguna;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
        $pengguna = Pengguna::select('id_pengguna', 'username')->get();

        return view('admin.create_ajax')->with('pengguna', $pengguna);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_pengguna'    => 'required|exists:pengguna,id_pengguna',
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'no_telepon'  => 'required|string|max:15',
                'email' => 'required|string|email|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            KelolaAdminModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data admin berhasil disimpan'
            ]);
        }
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
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $admin = KelolaAdminModel::find($id);
            if ($admin) {
                $admin->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data admin berhasil diperbarui.'
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
}
