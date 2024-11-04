<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
// use App\Models\PenggunaModel;

class KelolaPenggunaController extends Controller
{
    public function index()
    {
        // Mengambil judul halaman dan data level untuk filter
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'Pengguna']
        ];
        $page = (object) [
            'title' => 'Daftar Pengguna'
        ];
        $activeMenu = 'pengguna';

        $pengguna = Pengguna::all();     // ambil data kategori untuk filter kategori

        $level = Pengguna::select('peran')->distinct()->get(); // Mengambil data peran sebagai filter

        return view('pengguna.index', compact('breadcrumb', 'page', 'level', 'activeMenu', 'pengguna'));
    }

    public function list(Request $request)
    {
        $pengguna = Pengguna::select(
            'id_pengguna',
            'username',
            'nama_lengkap',
            'peran'
        );

        // Filter berdasarkan peran pengguna jika diberikan
        if ($request->peran) {
            $pengguna->where('peran', $request->peran);
        }

        return DataTables::of($pengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                $btn = '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function exportExcel()
    {
        $filename = 'Pengguna_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PenggunaExport, $filename);
    }

    public function exportPdf()
    {
        $pengguna = Pengguna::all();

        $pdf = Pdf::loadView('pengguna.export_pdf', compact('pengguna'));
        return $pdf->download('Pengguna_' . now()->format('Ymd_His') . '.pdf');
    }
    // Create Pengguna via AJAX
    public function create_ajax()
    {
        return view('pengguna.create_ajax');
    }

    // Store Pengguna via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Define validation rules
            $rules = [
                'username' => 'required|string|unique:pengguna,username|max:50',
                'password' => 'required|string|min:8',
                'nama_lengkap' => 'required|string|max:100',
                'nip' => 'required|string|max:20',
                'tempat_lahir' => 'nullable|string|max:50',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'required|in:L,P',
                'no_telepon' => 'nullable|string|max:20',
                'nik' => 'nullable|string|max:20',
                'nidn' => 'nullable|string|max:20',
                'agama' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
                'email' => 'required|string|email|max:100|unique:pengguna,email',
                'peran' => 'required|in:Admin,Dosen,Pimpinan',
                'photo_profile' => 'nullable|string|max:255'
            ];

            try {
                // Validate the request data
                $validated = $request->validate($rules);

                // Add hashed password to the validated data
                $validated['password'] = bcrypt($validated['password']);

                // Store the validated data in the database
                Pengguna::create($validated);

                return response()->json([
                    'status' => true,
                    'message' => 'Data pengguna berhasil disimpan'
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



    // Show Pengguna details via AJAX
    public function show_ajax($id)
    {
        $pengguna = Pengguna::find($id);
        return view('pengguna.show_ajax', ['pengguna' => $pengguna]);
    }

    // Edit Pengguna via AJAX
    public function edit_ajax($id)
    {
        $pengguna = Pengguna::find($id);
        return view('pengguna.edit_ajax', ['pengguna' => $pengguna]);
    }

    // Update Pengguna data via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username' => 'required|string|max:50|unique:penggunas,username,' . $id . ',id_pengguna',
                'nama_lengkap' => 'required|string|max:100',
                'peran' => 'required|string|max:50',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $pengguna = Pengguna::find($id);
            $pengguna->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data pengguna berhasil diubah'
            ]);
        }
        return redirect('/');
    }

    // Delete Pengguna via AJAX
    public function delete_ajax($id)
    {
        try {
            Pengguna::destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Data pengguna berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengguna gagal dihapus: ' . $e->getMessage()
            ]);
        }
    }
}