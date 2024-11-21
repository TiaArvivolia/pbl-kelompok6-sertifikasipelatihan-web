<?php

namespace App\Http\Controllers;

use App\Models\MataKuliahModel; // Make sure to create this model
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KelolaMataKuliahController extends Controller
{
    // Show the initial page for mata kuliah
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Mata Kuliah',
            'list' => ['Home', 'Mata Kuliah']
        ];

        $page = (object) [
            'title' => 'Daftar mata kuliah yang terdaftar dalam sistem'
        ];

        $activeMenu = 'mata_kuliah'; // Set the active menu

        $mata_kuliah = MataKuliahModel::all(); // Get all mata kuliah for filtering

        return view('mata_kuliah.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'mata_kuliah' => $mata_kuliah, 'activeMenu' => $activeMenu]);
    }

    // Get mata kuliah data in JSON format for DataTables
    public function list(Request $request)
    {
        $mata_kuliah = MataKuliahModel::select('id_mata_kuliah', 'kode_mk', 'nama_mk');

        // Apply filter if id_mata_kuliah is provided
        if ($request->id_mata_kuliah) {
            $mata_kuliah->where('id_mata_kuliah', $request->id_mata_kuliah);
        }

        return DataTables::of($mata_kuliah)
            ->addIndexColumn() // This will add the DT_RowIndex
            ->addColumn('aksi', function ($mata_kuliah) {
                $btn = '<button onclick="modalAction(\'' . url('/mata_kuliah/' . $mata_kuliah->id_mata_kuliah . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mata_kuliah/' . $mata_kuliah->id_mata_kuliah . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/mata_kuliah/' . $mata_kuliah->id_mata_kuliah . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';                
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Show the form for creating a new mata kuliah via AJAX
    public function create_ajax()
    {
        return view('mata_kuliah.create_ajax'); // Adjust this to point to your AJAX create view
    }

    // Store a new mata kuliah via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk',
                'nama_mk' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            MataKuliahModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data mata kuliah berhasil disimpan',
            ]);
        }

        return redirect('/');
    }

    // Show mata kuliah details via AJAX
    public function show_ajax(string $id)
    {
        $mata_kuliah = MataKuliahModel::find($id);
        return view('mata_kuliah.show_ajax', ['mata_kuliah' => $mata_kuliah]);
    }

    // Show the form for editing a mata kuliah via AJAX
    public function edit_ajax(string $id)
    {
        $mata_kuliah = MataKuliahModel::find($id);
        return view('mata_kuliah.edit_ajax', ['mata_kuliah' => $mata_kuliah]);
    }

    // Update mata kuliah data via AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk,' . $id . ',id_mata_kuliah',
                'nama_mk' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $mata_kuliah = MataKuliahModel::find($id);
            $mata_kuliah->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data mata kuliah berhasil diperbarui',
            ]);
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $mata_kuliah = MataKuliahModel::find($id);

        return view('mata_kuliah.confirm_ajax', ['mata_kuliah' => $mata_kuliah]);
    }

    // Delete mata kuliah data via AJAX
    public function delete_ajax(string $id)
    {
        if (request()->ajax()) {
            $mata_kuliah = MataKuliahModel::find($id);
            if (!$mata_kuliah) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data mata kuliah tidak ditemukan.',
                ]);
            }

            try {
                $mata_kuliah->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data mata kuliah berhasil dihapus.',
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data mata kuliah gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.',
                ]);
            }
        }

        return redirect('/');
    }
    public function export_pdf()
{
    $mata_kuliah = MataKuliahModel::select('id_mata_kuliah', 'kode_mk', 'nama_mk')
        ->orderBy('nama_mk', 'asc')
        ->get();

    $pdf = Pdf::loadView('mata_kuliah.export_pdf', compact('mata_kuliah'));
    $pdf->setPaper('a4', 'portrait'); // Set paper size and orientation

    return $pdf->stream('Data_Mata_Kuliah_' . date('Y-m-d_H-i-s') . '.pdf');
}
public function export_excel()
{
    $mata_kuliah = MataKuliahModel::select('id_mata_kuliah', 'kode_mk', 'nama_mk')
        ->orderBy('nama_mk', 'asc')
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header columns
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'ID Mata Kuliah');
    $sheet->setCellValue('C1', 'Kode Mata Kuliah');
    $sheet->setCellValue('D1', 'Nama Mata Kuliah');
    $sheet->getStyle('A1:D1')->getFont()->setBold(true);

    // Data rows
    $row = 2;
    foreach ($mata_kuliah as $index => $data) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $data->id_mata_kuliah);
        $sheet->setCellValue('C' . $row, $data->kode_mk);
        $sheet->setCellValue('D' . $row, $data->nama_mk);
        $row++;
    }

    // Auto size columns
    foreach (range('A', 'D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Save Excel file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Data_Mata_Kuliah_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

}