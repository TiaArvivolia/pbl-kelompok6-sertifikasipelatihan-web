<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel; // Make sure to create this model
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KelolaBidangMinatController extends Controller
{
    // Show the initial page for bidang minat
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Bidang Minat',
            'list' => ['Home', 'Bidang Minat']
        ];

        $page = (object) [
            'title' => 'Daftar bidang minat yang terdaftar dalam sistem'
        ];

        $activeMenu = 'bidang_minat'; // Set the active menu

        $bidang_minat = BidangMinatModel::all(); // Get all bidang minat for filtering

        return view('bidang_minat.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'bidang_minat' => $bidang_minat, 'activeMenu' => $activeMenu]);
    }

    // Get bidang minat data in JSON format for DataTables
    public function list(Request $request)
    {
        $bidang_minat = BidangMinatModel::select('id_bidang_minat', 'kode_bidang_minat', 'nama_bidang_minat');

        // Apply filter if id_bidang_minat is provided
        if ($request->id_bidang_minat) {
            $bidang_minat->where('id_bidang_minat', $request->id_bidang_minat);
        }

        return DataTables::of($bidang_minat)
            ->addIndexColumn() // This will add the DT_RowIndex
            ->addColumn('aksi', function ($bidang_minat) {
                $btn = '<button onclick="modalAction(\'' . url('/bidang_minat/' . $bidang_minat->id_bidang_minat . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/bidang_minat/' . $bidang_minat->id_bidang_minat . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/bidang_minat/' . $bidang_minat->id_bidang_minat . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Show the form for creating a new bidang minat via AJAX
    public function create_ajax()
    {
        return view('bidang_minat.create_ajax'); // Adjust this to point to your AJAX create view
    }

    // Store a new bidang minat via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_bidang_minat' => 'required|string|max:20|unique:bidang_minat,kode_bidang_minat',
                'nama_bidang_minat' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            BidangMinatModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data bidang minat berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    // Show bidang minat details via AJAX
    public function show_ajax(string $id)
    {
        $bidang_minat = BidangMinatModel::find($id);
        return view('bidang_minat.show_ajax', ['bidang_minat' => $bidang_minat]);
    }

    // Show the form for editing a bidang minat via AJAX
    public function edit_ajax(string $id)
    {
        $bidang_minat = BidangMinatModel::find($id);
        return view('bidang_minat.edit_ajax', ['bidang_minat' => $bidang_minat]);
    }

    // Update bidang minat data via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_bidang_minat' => 'required|string|max:20|unique:bidang_minat,kode_bidang_minat,' . $id . ',id_bidang_minat',
                'nama_bidang_minat' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $bidang_minat = BidangMinatModel::find($id);
            $bidang_minat->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data bidang minat berhasil diperbarui',
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $bidang_minat = BidangMinatModel::find($id);

        return view('bidang_minat.confirm_ajax', ['bidang_minat' => $bidang_minat]);
    }

    // Delete bidang minat data via AJAX
    public function delete_ajax(string $id)
    {
        if (request()->ajax()) {
            $bidang_minat = BidangMinatModel::find($id);
            if (!$bidang_minat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data bidang minat tidak ditemukan.',
                ]);
            }

            try {
                $bidang_minat->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data bidang minat berhasil dihapus.',
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data bidang minat gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.',
                ]);
            }
        }

        return redirect('/');
    }
}