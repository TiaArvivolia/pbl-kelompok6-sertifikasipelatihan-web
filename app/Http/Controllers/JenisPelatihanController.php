<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class JenisPelatihanController extends Controller
{
    public function index()
    {
        // Mengambil judul halaman
        $breadcrumb = (object) [
            'title' => 'Daftar Jenis Pelatihan',
            'list' => ['Home', 'Jenis Pelatihan']
        ];
        $page = (object) [
            'title' => 'Daftar Jenis Pelatihan'
        ];
        $activeMenu = 'jenis_pelatihan';

        $jenis_pelatihan = JenisPelatihanModel::all(); // Ambil data jenis pelatihan untuk tampilan

        return view('jenis_pelatihan.index', compact('breadcrumb', 'page', 'activeMenu', 'jenis_pelatihan'));
    }

    public function list(Request $request)
    {
        // Initialize the query
        $query = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan');

        // Get the filtered jenis pelatihan
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis_pelatihan) {
                $btn = '<button onclick="modalAction(\'' . url('/jenis_pelatihan/' . $jenis_pelatihan->id_jenis_pelatihan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis_pelatihan/' . $jenis_pelatihan->id_jenis_pelatihan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenis_pelatihan/' . $jenis_pelatihan->id_jenis_pelatihan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Create Jenis Pelatihan via AJAX
    public function create_ajax()
    {
        return view('jenis_pelatihan.create_ajax');
    }

    // Store Jenis Pelatihan via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Define validation rules
            $rules = [
                'nama_jenis_pelatihan' => 'required|string|max:100|unique:jenis_pelatihan,nama_jenis_pelatihan',
            ];

            try {
                // Validate the request data
                $validated = $request->validate($rules);

                // Store the validated data in the database
                JenisPelatihanModel::create($validated);

                return response()->json([
                    'status' => true,
                    'message' => 'Data jenis pelatihan berhasil disimpan'
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

    // Show Jenis Pelatihan details via AJAX
    public function show_ajax($id_jenis_pelatihan)
    {
        $jenis_pelatihan = JenisPelatihanModel::find($id_jenis_pelatihan);
        return view('jenis_pelatihan.show_ajax', ['jenis_pelatihan' => $jenis_pelatihan]);
    }

    // Edit Jenis Pelatihan via AJAX
    public function edit_ajax($id_jenis_pelatihan)
    {
        $jenis_pelatihan = JenisPelatihanModel::find($id_jenis_pelatihan);
        return view('jenis_pelatihan.edit_ajax', ['jenis_pelatihan' => $jenis_pelatihan]);
    }

    // Update Jenis Pelatihan data via AJAX
    public function update_ajax(Request $request, $id_jenis_pelatihan)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_jenis_pelatihan' => 'required|string|max:100|unique:jenis_pelatihan,nama_jenis_pelatihan,' . $id_jenis_pelatihan . ',id_jenis_pelatihan',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $jenis_pelatihan = JenisPelatihanModel::find($id_jenis_pelatihan);
            $jenis_pelatihan->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data jenis pelatihan berhasil diubah'
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $jenis_pelatihan = JenisPelatihanModel::find($id);

        return view('jenis_pelatihan.confirm_ajax', ['jenis_pelatihan' => $jenis_pelatihan]);
    }

    // Delete Jenis Pelatihan via AJAX
    public function delete_ajax($id_jenis_pelatihan)
    {
        try {
            JenisPelatihanModel::destroy($id_jenis_pelatihan);
            return response()->json([
                'status' => true,
                'message' => 'Data jenis pelatihan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data jenis pelatihan gagal dihapus: ' . $e->getMessage()
            ]);
        }
    }
}