<?php

namespace App\Http\Controllers;

use App\Models\JenisPenggunaModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KelolaJenisPenggunaController extends Controller
{
    public function index()
    {
        // Mengambil judul halaman
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Pengguna',
            'list' => ['Home', 'Jenis Pengguna']
        ];
        $page = (object) [
            'title' => 'Daftar Jenis Pengguna'
        ];
        $activeMenu = 'jenis_pengguna';

        $jenis_pengguna = JenisPenggunaModel::all(); // Ambil data jenis pengguna untuk tampilan

        return view('jenis_pengguna.index', compact('breadcrumb', 'page', 'activeMenu', 'jenis_pengguna'));
    }

    public function list(Request $request)
    {
        // Initialize the query
        $query = JenisPenggunaModel::select('id_jenis_pengguna', 'kode_jenis_pengguna', 'nama_jenis_pengguna');

        // Get the filtered jenis pengguna
        return DataTables::of($query)
            ->addIndexColumn()
            // ->addColumn('aksi', function ($jenis_pengguna) {
            //     $btn = '<button onclick="modalAction(\'' . url('/jenis_pengguna/' . $jenis_pengguna->id_jenis_pengguna . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
            //     $btn .= '<button onclick="modalAction(\'' . url('/jenis_pengguna/' . $jenis_pengguna->id_jenis_pengguna . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
            //     $btn .= '<button onclick="modalAction(\'' . url('/jenis_pengguna/' . $jenis_pengguna->id_jenis_pengguna . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
            //     return $btn;
            // })
            // ->rawColumns(['aksi'])
            ->make(true);
    }

    // Create Jenis Pengguna via AJAX
    public function create_ajax()
    {
        return view('jenis_pengguna.create_ajax');
    }

    // Store Jenis Pengguna via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Define validation rules
            $rules = [
                'kode_jenis_pengguna' => 'required|string|unique:jenis_pengguna,kode_jenis_pengguna|max:10',
                'nama_jenis_pengguna' => 'required|string|max:50',
            ];

            try {
                // Validate the request data
                $validated = $request->validate($rules);

                // Store the validated data in the database
                JenisPenggunaModel::create($validated);

                return response()->json([
                    'status' => true,
                    'message' => 'Data jenis pengguna berhasil disimpan'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $e->errors(),
                ]);
            }
        }

        return response()->json(['status' => false, 'message' => 'Request tidak valid'], 400);
    }

    // Show Jenis Pengguna details via AJAX
    public function show_ajax($kode_jenis_pengguna)
    {
        $jenis_pengguna = JenisPenggunaModel::find($kode_jenis_pengguna);
        return view('jenis_pengguna.show_ajax', ['jenis_pengguna' => $jenis_pengguna]);
    }

    // Edit Jenis Pengguna via AJAX
    public function edit_ajax($kode_jenis_pengguna)
    {
        $jenis_pengguna = JenisPenggunaModel::find($kode_jenis_pengguna);
        return view('jenis_pengguna.edit_ajax', ['jenis_pengguna' => $jenis_pengguna]);
    }

    // Update Jenis Pengguna data via AJAX
    public function update_ajax(Request $request, $kode_jenis_pengguna)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_jenis_pengguna' => 'required|string|max:10|unique:jenis_pengguna,kode_jenis_pengguna,' . $kode_jenis_pengguna . ',kode_jenis_pengguna',
                'nama_jenis_pengguna' => 'required|string|max:50',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $jenis_pengguna = JenisPenggunaModel::find($kode_jenis_pengguna);
            $jenis_pengguna->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data jenis pengguna berhasil diubah'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $jenis_pengguna = JenisPenggunaModel::find($id);

        return view('jenis_pengguna.confirm_ajax', ['jenis_pengguna' => $jenis_pengguna]);
    }

    // Delete Jenis Pengguna via AJAX
    public function delete_ajax($kode_jenis_pengguna)
    {
        try {
            JenisPenggunaModel::destroy($kode_jenis_pengguna);
            return response()->json([
                'status' => true,
                'message' => 'Data jenis pengguna berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data jenis pengguna gagal dihapus: ' . $e->getMessage()
            ]);
        }
    }
}