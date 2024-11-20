<?php

namespace App\Http\Controllers;

use App\Models\VendorSertifikasiModel; // Make sure to create this model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VendorSertifikasiController extends Controller
{
    // Show the initial page for vendor sertifikasi
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Vendor Sertifikasi',
            'list' => ['Home', 'Vendor Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Daftar vendor sertifikasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'vendor_sertifikasi'; // Set the active menu

        // $vendors = VendorSertifikasiModel::all(); // Get all vendors for filtering

        return view('vendor_sertifikasi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Get vendor sertifikasi data in JSON format for DataTables
    public function list(Request $request)
    {
        $vendors = VendorSertifikasiModel::select('id_vendor_sertifikasi', 'nama', 'alamat', 'kota', 'no_telepon', 'website');

        // Apply filter if id_vendor_sertifikasi is provided
        if ($request->id_vendor_sertifikasi) {
            $vendors->where('id_vendor_sertifikasi', $request->id_vendor_sertifikasi);
        }

        return DataTables::of($vendors)
            ->addIndexColumn() // This will add the DT_RowIndex
            ->addColumn('aksi', function ($vendor) {
                $btn = '<button onclick="modalAction(\'' . url('/vendor_sertifikasi/' . $vendor->id_vendor_sertifikasi . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor_sertifikasi/' . $vendor->id_vendor_sertifikasi . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor_sertifikasi/' . $vendor->id_vendor_sertifikasi . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    // Show the form for creating a new vendor sertifikasi via AJAX
    public function create_ajax()
    {
        return view('vendor_sertifikasi.create_ajax'); // Adjust this to point to your AJAX create view
    }

    // Store a new vendor sertifikasi via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama' => 'required|string|max:100',
                'alamat' => 'nullable|string',
                'kota' => 'nullable|string|max:50',
                'no_telepon' => 'nullable|string|max:20',
                'website' => 'nullable|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            VendorSertifikasiModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data vendor sertifikasi berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    // Show vendor sertifikasi details via AJAX
    public function show_ajax(string $id)
    {
        $vendor = VendorSertifikasiModel::find($id);
        return view('vendor_sertifikasi.show_ajax', ['vendor' => $vendor]);
    }

    // Show the form for editing a vendor sertifikasi via AJAX
    public function edit_ajax(string $id)
    {
        $vendor_sertifikasi = VendorSertifikasiModel::find($id);
        return view('vendor_sertifikasi.edit_ajax', ['vendor_sertifikasi' => $vendor_sertifikasi]);
    }

    // Update vendor sertifikasi data via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama' => 'required|string|max:100',
                'alamat' => 'nullable|string',
                'kota' => 'nullable|string|max:50',
                'no_telepon' => 'nullable|string|max:20',
                'website' => 'nullable|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $vendor = VendorSertifikasiModel::find($id);
            $vendor->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data vendor sertifikasi berhasil diperbarui',
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $vendor_sertifikasi = VendorSertifikasiModel::find($id);

        return view('vendor_sertifikasi.confirm_ajax', ['vendor_sertifikasi' => $vendor_sertifikasi]);
    }

    // Delete vendor sertifikasi data via AJAX
    public function delete_ajax(string $id)
    {
        if (request()->ajax()) {
            $vendor = VendorSertifikasiModel::find($id);
            if (!$vendor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data vendor sertifikasi tidak ditemukan.',
                ]);
            }

            try {
                $vendor->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data vendor sertifikasi berhasil dihapus.',
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data vendor sertifikasi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.',
                ]);
            }
        }

        return redirect('/');
    }
    public function export_pdf()
{
    $vendors = VendorSertifikasiModel::select('id_vendor_sertifikasi', 'nama', 'alamat', 'kota', 'no_telepon', 'website')
        ->orderBy('nama', 'asc')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('vendor_sertifikasi.export_pdf', compact('vendors'));
    $pdf->setPaper('a4', 'portrait'); // Atur ukuran kertas dan orientasi

    return $pdf->stream('Data_Vendor_Sertifikasi_' . date('Y-m-d_H-i-s') . '.pdf');
}

public function export_excel()
{
    $vendors = VendorSertifikasiModel::select('id_vendor_sertifikasi', 'nama', 'alamat', 'kota', 'no_telepon', 'website')
        ->orderBy('nama', 'asc')
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'ID Vendor Sertifikasi');
    $sheet->setCellValue('C1', 'Nama');
    $sheet->setCellValue('D1', 'Alamat');
    $sheet->setCellValue('E1', 'Kota');
    $sheet->setCellValue('F1', 'No Telepon');
    $sheet->setCellValue('G1', 'Website');
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);

    // Isi data
    $row = 2;
    foreach ($vendors as $index => $vendor) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $vendor->id_vendor_sertifikasi);
        $sheet->setCellValue('C' . $row, $vendor->nama);
        $sheet->setCellValue('D' . $row, $vendor->alamat);
        $sheet->setCellValue('E' . $row, $vendor->kota);
        $sheet->setCellValue('F' . $row, $vendor->no_telepon);
        $sheet->setCellValue('G' . $row, $vendor->website);
        $row++;
    }

    // Auto size kolom
    foreach (range('A', 'G') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Save file Excel
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Data_Vendor_Sertifikasi_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

}
