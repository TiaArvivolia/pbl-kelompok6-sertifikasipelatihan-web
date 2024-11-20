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

class KelolaTendikController extends Controller
{
    // Menampilkan halaman utama Kelola Tendik
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Tendik',
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
        $tendik = KelolaTendikModel::select('id_tendik', 'id_pengguna', 'nama_lengkap', 'nip', 'no_telepon', 'email', 'gambar_profil', 'tag_bidang_minat')
            ->with('pengguna', 'bidangMinat');

        return DataTables::of($tendik)
            ->addIndexColumn()
            ->addColumn('aksi', function ($tendik) {
                $btn = '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/tendik/' . $tendik->id_tendik . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi']) // Ensure action column supports HTML
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
            'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat', // Validates bidang minat tag
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
            'tag_bidang_minat' => $request->input('tag_bidang_minat'),
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

        return view('tendik.show_ajax', compact('tendik'));
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
            $rules = [
                'nama_lengkap' => 'required|string|max:100',
                'nip'  => 'required|string|max:20',
                'no_telepon'  => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'tag_bidang_minat' => 'required|exists:bidang_minat,id_bidang_minat',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $tendik = KelolaTendikModel::find($id);
            if ($tendik) {
                $tendik->bidangMinat()->associate(BidangMinatModel::find($request->tag_bidang_minat));
                $tendik->update($request->except(['tag_bidang_minat'])); // Update other fields except the relation

                return response()->json([
                    'status'  => true,
                    'message' => 'Data tendik berhasil diperbarui.'
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
        $sheet->setCellValue('H' . $row, optional($data->bidangMinat)->nama_bidang_minat); // Assuming you have a `nama_bidang_minat` field in `BidangMinatModel`
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