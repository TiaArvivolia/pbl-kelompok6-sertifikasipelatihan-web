<?php

namespace App\Http\Controllers;

use App\Models\JenisPenggunaModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class KelolaPenggunaController extends Controller
{
    public function index()
    {
        // Page details
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'Pengguna']
        ];
        $page = (object) [
            'title' => 'Daftar Pengguna'
        ];
        $activeMenu = 'pengguna';

        // Fetch users and distinct roles for filtering
        $pengguna = Pengguna::all();
        $jenis_pengguna = Pengguna::select('id_jenis_pengguna')->distinct()->get();

        return view('pengguna.index', compact('breadcrumb', 'page', 'jenis_pengguna', 'activeMenu', 'pengguna'));
    }

    public function list(Request $request)
    {
        $query = Pengguna::select('pengguna.id_pengguna', 'pengguna.username', 'jenis_pengguna.id_jenis_pengguna', 'jenis_pengguna.nama_jenis_pengguna')
            ->join('jenis_pengguna', 'pengguna.id_jenis_pengguna', '=', 'jenis_pengguna.id_jenis_pengguna');

        // Filter by jenis_pengguna if provided
        if ($request->filled('jenis_pengguna_filter')) {
            $query->where('pengguna.id_jenis_pengguna', $request->input('jenis_pengguna_filter'));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                return '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ' .
                    '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ' .
                    '<button onclick="modalAction(\'' . url('/pengguna/' . $pengguna->id_pengguna . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }



    // Show form to create a user
    public function create_ajax()
    {
        $jenis_pengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();

        return view('pengguna.create_ajax')
            ->with('jenis_pengguna', $jenis_pengguna);
    }

    // Store a new user
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username' => 'required|string|unique:pengguna,username|max:50',
                'password' => 'required|string|min:8',
                'id_jenis_pengguna' => 'required|exists:jenis_pengguna,id_jenis_pengguna'
            ];

            try {
                $validated = $request->validate($rules);
                $validated['password'] = Hash::make($validated['password']);
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

    // Show user details
    public function show_ajax($id)
    {
        $pengguna = Pengguna::find($id);
        return view('pengguna.show_ajax', ['pengguna' => $pengguna]);
    }

    // Show edit form
    public function edit_ajax($id)
    {
        $pengguna = Pengguna::find($id);
        $jenis_pengguna = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();

        return view('pengguna.edit_ajax', ['pengguna' => $pengguna, 'jenis_pengguna' => $jenis_pengguna,]);
    }

    // Update user data
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Define the validation rules
            $rules = [
                'username' => 'required|string|max:50|unique:pengguna,username,' . $id . ',id_pengguna',
                'password' => 'required|string|min:3',
                'id_jenis_pengguna' => 'required|exists:jenis_pengguna,id_jenis_pengguna'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Find the user
            $pengguna = Pengguna::find($id);

            // Update the user data
            $pengguna->username = $request->input('username');
            $pengguna->password = $request->input('password');
            $pengguna->id_jenis_pengguna = $request->input('id_jenis_pengguna');

            // Check if a new password is provided, hash it, and update it
            if ($request->filled('password')) {
                $pengguna->password = bcrypt($request->input('password'));
            }

            // Save changes
            $pengguna->save();

            return response()->json([
                'status' => true,
                'message' => 'Data pengguna berhasil diubah'
            ]);
        }

        return redirect('/');
    }


    // Show delete confirmation
    public function confirm_ajax(string $id)
    {
        $pengguna = Pengguna::find($id);
        return view('pengguna.confirm_ajax', ['pengguna' => $pengguna]);
    }

    // Delete a user
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
