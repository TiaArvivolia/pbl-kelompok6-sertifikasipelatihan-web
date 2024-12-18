<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\KelolaTendikModel; // Model untuk tendik
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KelolaTendikController extends Controller
{
    // Menampilkan halaman utama Kelola Tendik
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Tendik',
            'list' => ['Home', 'Tendik']
        ];

        $page = (object) [
            'title' => 'Daftar tendik yang terdaftar dalam sistem'
        ];

        $activeMenu = 'tendik'; // Set active menu

        return view('tendik.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data tendik dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $tendik = KelolaTendikModel::select('id_tendik', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil', 'tag_bidang_minat')
            ->with('pengguna', 'bidangMinat');

        // Filter by logged-in user's tendik data
        if ($user->id_jenis_pengguna == 3) { // If the user is a tendik
            $tendik->where('id_pengguna', $user->id_pengguna);
        }

        return DataTables::of($tendik)
            ->addIndexColumn()
            ->addColumn('gambar_profil', function ($tendik) {
                // Cek apakah ada gambar_profil
                if ($tendik->gambar_profil) {
                    $url = asset('storage/' . $tendik->gambar_profil); // Pastikan folder storage bisa diakses
                    return '<img src="' . $url . '"  width="150" height="150" class="img-thumbnail">';
                }
                return '<span class="text-muted">Tidak ada gambar</span>'; // Placeholder jika gambar kosong
            })
            ->addColumn('aksi', function ($tendik) {
                $user = auth()->user();
                $btn = '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                if ($user->id_jenis_pengguna == 1) {
                    $btn .= '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
                }
                return $btn;
            })
            ->rawColumns(['gambar_profil', 'aksi']) // Pastikan kolom 'gambar_profil' mendukung HTML
            ->make(true);
    }

    public function create_ajax()
    {
        $tendik = KelolaTendikModel::with(['pengguna', 'bidangMinat'])->get();
        if (!$tendik) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Ambil semua data pengguna dan bidang minat untuk pilihan dropdown
        $pengguna = Pengguna::all();
        $bidangMinat = BidangMinatModel::all();

        return view('tendik.create_ajax', compact('tendik', 'bidangMinat', 'pengguna'));
    }

    public function store_ajax(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'username' => 'required|string|max:50|unique:pengguna,username', // Unique username
            'password' => 'required|string|min:8|confirmed', // Ensure password is confirmed
            'password_confirmation' => 'required|string|min:8', // Confirmation password field
            'nama_lengkap' => 'required|string|max:100',
            'nip' => 'required|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
            'bidang_minat_list' => 'nullable|array',
            'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat',
            'gambar_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        // Insert into the pengguna table and get the ID
        $id_pengguna = DB::table('pengguna')->insertGetId([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')), // Hash the password before saving
            'id_jenis_pengguna' => 3, // Set as tendik (id_jenis_pengguna for tendik, adjust if needed)
        ]);

        // Save tendik details to the kelola_tendik table
        DB::table('tendik')->insert([
            'id_pengguna' => $id_pengguna,
            'nama_lengkap' => $request->input('nama_lengkap'),
            'nip' => $request->input('nip'),
            'no_telepon' => $request->input('no_telepon'),
            'email' => $request->input('email'),
            'bidang_minat_list' => $request->has('bidang_minat_list') ? json_encode($request->input('bidang_minat_list')) : null, // Store bidang_minat_list as JSON
            'gambar_profil' => $request->file('gambar_profil') ? $request->file('gambar_profil')->store('profile_pictures', 'public') : null, // Handle optional profile picture upload
        ]);

        // Return a successful response
        return response()->json([
            'status' => true,
            'message' => 'Data tendik berhasil disimpan'
        ]);

        return redirect('/');
    }


    public function show_ajax($id)
    {
        // Load the tendik record with pengguna and bidangMinat relationships
        $tendik = KelolaTendikModel::with(['pengguna', 'bidangMinat'])->find($id);

        if (!$tendik) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Decode the JSON for bidang_minat_list and retrieve bidang minat names
        $bidangMinatNames = [];
        if ($tendik->bidang_minat_list) {
            $bidangMinatListArray = json_decode($tendik->bidang_minat_list);
            foreach ($bidangMinatListArray as $idBidangMinat) {
                $bidangMinat = BidangMinatModel::find($idBidangMinat);
                if ($bidangMinat) {
                    $bidangMinatNames[] = $bidangMinat->nama_bidang_minat;
                }
            }
        }

        return view('tendik.show_ajax', compact('tendik', 'bidangMinatNames'));
    }

    public function edit_ajax(string $id)
    {
        $tendik = KelolaTendikModel::with(['pengguna', 'bidangMinat'])->find($id);
        if (!$tendik) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Ambil semua data bidang minat untuk pilihan dropdown
        $bidangMinat = BidangMinatModel::all();

        return view('tendik.edit_ajax', compact('tendik', 'bidangMinat'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validation rules
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'no_telepon'  => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'gambar_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
                'username' => 'nullable|string|max:100', // Validasi untuk username
                'password' => 'nullable|string|min:6', // Validasi untuk password
                'bidang_minat_list' => 'nullable|array', // Bidang minat list harus array jika diberikan
                'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat', // Validasi setiap id di bidang_minat_list
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            // Mencari tendik berdasarkan ID
            $tendik = KelolaTendikModel::find($id);
            if ($tendik) {
                // Update bidang minat list
                if ($request->filled('bidang_minat_list')) {
                    $tendik->bidang_minat_list = json_encode($request->bidang_minat_list); // Simpan sebagai JSON
                }

                // Tangani penggantian gambar profil
                if ($request->hasFile('gambar_profil')) {
                    if ($tendik->gambar_profil && Storage::disk('public')->exists($tendik->gambar_profil)) {
                        Storage::disk('public')->delete($tendik->gambar_profil);
                    }

                    $path = $request->file('gambar_profil')->store('profile_pictures', 'public');
                    $tendik->gambar_profil = $path;
                }

                // Update data tendik
                $tendik->update($request->except(['bidang_minat_list', 'gambar_profil', 'username', 'password']));

                // Cek dan update data pengguna (username dan password)
                if ($request->filled('username') || $request->filled('password')) {
                    $pengguna = Pengguna::where('id_pengguna', $tendik->id_pengguna)->first(); // Asumsikan id_pengguna adalah foreign key
                    if ($pengguna) {
                        if ($request->filled('username')) {
                            $pengguna->username = $request->username;
                        }

                        if ($request->filled('password')) {
                            // Enkripsi password sebelum disimpan
                            $pengguna->password = bcrypt($request->password);
                        }

                        $pengguna->save();
                    }
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Data tendik dan pengguna berhasil diperbarui.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Tendik tidak ditemukan'
            ]);
        }
    }




    public function confirm_ajax(string $id)
    {
        $tendik = KelolaTendikModel::find($id);
        return view('tendik.confirm_ajax', ['tendik' => $tendik]);
    }

    public function delete_ajax(string $id)
    {
        try {
            KelolaTendikModel::destroy($id);
            return response()->json([
                'status'  => true,
                'message' => 'Data tendik berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tendik gagal dihapus'
            ]);
        }
    }

    public function export_pdf()
    {
        // Fetch tendik data
        $tendik = KelolaTendikModel::select('id_tendik', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil', 'tag_bidang_minat')
            ->with('pengguna', 'bidangMinat')
            ->get();

        // Share data with the view
        $pdf = Pdf::loadView('tendik.export_pdf', ['tendik' => $tendik]);
        $pdf->setPaper('a4', 'portrait'); // Paper size and orientation

        return $pdf->stream('Data_Tendik_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function export_excel()
    {
        // Fetch tendik data
        $tendik = KelolaTendikModel::select('id_tendik', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil', 'tag_bidang_minat')
            ->with('pengguna', 'bidangMinat')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header columns
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Tendik');
        $sheet->setCellValue('C1', 'ID Pengguna');
        $sheet->setCellValue('D1', 'Nama Lengkap');
        $sheet->setCellValue('E1', 'NIP');
        $sheet->setCellValue('F1', 'No Telepon');
        $sheet->setCellValue('G1', 'Email');
        $sheet->setCellValue('H1', 'Tag Bidang Minat');
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Fill data
        $row = 2;
        foreach ($tendik as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->id_tendik);
            $sheet->setCellValue('C' . $row, $data->id_pengguna);
            $sheet->setCellValue('D' . $row, $data->nama_lengkap);
            $sheet->setCellValue('E' . $row, $data->nip);
            $sheet->setCellValue('F' . $row, $data->no_telepon);
            $sheet->setCellValue('G' . $row, $data->email);
            $sheet->setCellValue('H' . $row, optional($data->bidangMinat)->nama_bidang_minat); // Assuming you have a nama_bidang_minat field in BidangMinatModel
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Save file Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_Dosen_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}