<?php

namespace App\Http\Controllers;

use App\Models\VendorPelatihanModel; // Make sure to create this model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VendorPelatihanController extends Controller
{
    // Show the initial page for vendor pelatihan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Vendor Pelatihan',
            'list' => ['Home', 'Vendor Pelatihan']
        ];

        $page = (object) [
            'title' => 'Daftar vendor pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'vendor_pelatihan';

        // $vendor_pelatihan = VendorPelatihanModel::all();

        return view('vendor_pelatihan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Get vendor data in JSON format for DataTables
    public function list(Request $request)
    {
        $vendors = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama', 'alamat', 'kota', 'no_telepon', 'website');

        return DataTables::of($vendors)
            ->addIndexColumn()
            ->addColumn('aksi', function ($vendor) {
                $btn = '<button onclick="modalAction(\'' . url('/vendor_pelatihan/' . $vendor->id_vendor_pelatihan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor_pelatihan/' . $vendor->id_vendor_pelatihan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendor_pelatihan/' . $vendor->id_vendor_pelatihan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>';                
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Show the form for creating a new vendor via AJAX
    public function create_ajax()
    {
        return view('vendor_pelatihan.create_ajax');
    }

    // Store a new vendor via AJAX
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

            VendorPelatihanModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data vendor pelatihan berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    // Show vendor details via AJAX
    public function show_ajax(string $id)
    {
        $vendor_pelatihan = VendorPelatihanModel::find($id);
        return view('vendor_pelatihan.show_ajax', ['vendor_pelatihan' => $vendor_pelatihan]);
    }

    // Show the form for editing a vendor via AJAX
    public function edit_ajax(string $id)
    {
        $vendor_pelatihan = VendorPelatihanModel::find($id);
        return view('vendor_pelatihan.edit_ajax', ['vendor_pelatihan' => $vendor_pelatihan]);
    }

    // Update vendor data via AJAX
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

            $vendor = VendorPelatihanModel::find($id);
            $vendor->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data vendor pelatihan berhasil diperbarui',
            ]);
        }

        return redirect('/');
    }

    // Confirm deletion of vendor via AJAX
    public function confirm_ajax(string $id)
    {
        $vendor_pelatihan = VendorPelatihanModel::find($id);

        return view('vendor_pelatihan.confirm_ajax', ['vendor_pelatihan' => $vendor_pelatihan]);
    }

    // Delete vendor data via AJAX
    public function delete_ajax(string $id)
    {
        if (request()->ajax()) {
            $vendor = VendorPelatihanModel::find($id);
            if (!$vendor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data vendor pelatihan tidak ditemukan.',
                ]);
            }

            try {
                $vendor->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data vendor pelatihan berhasil dihapus.',
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data vendor pelatihan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.',
                ]);
            }
        }

        return redirect('/');
    }
    public function export_pdf()
{
    $vendors = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama', 'alamat', 'kota', 'no_telepon', 'website')
        ->orderBy('nama', 'asc')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('vendor_pelatihan.export_pdf', compact('vendors'));
    $pdf->setPaper('a4', 'portrait'); // Atur ukuran dan orientasi kertas

    return $pdf->stream('Data_Vendor_Pelatihan_' . date('Y-m-d_H-i-s') . '.pdf');
}
public function export_excel()
{
    $vendors = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama', 'alamat', 'kota', 'no_telepon', 'website')
        ->orderBy('nama', 'asc')
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header Kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'ID Vendor Pelatihan');
    $sheet->setCellValue('C1', 'Nama');
    $sheet->setCellValue('D1', 'Alamat');
    $sheet->setCellValue('E1', 'Kota');
    $sheet->setCellValue('F1', 'No Telepon');
    $sheet->setCellValue('G1', 'Website');
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);

    // Isi Data
    $row = 2;
    foreach ($vendors as $index => $vendor) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $vendor->id_vendor_pelatihan);
        $sheet->setCellValue('C' . $row, $vendor->nama);
        $sheet->setCellValue('D' . $row, $vendor->alamat);
        $sheet->setCellValue('E' . $row, $vendor->kota);
        $sheet->setCellValue('F' . $row, $vendor->no_telepon);
        $sheet->setCellValue('G' . $row, $vendor->website);
        $row++;
    }

    // Auto size column
    foreach (range('A', 'G') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Save Excel ke output
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Data_Vendor_Pelatihan_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

}