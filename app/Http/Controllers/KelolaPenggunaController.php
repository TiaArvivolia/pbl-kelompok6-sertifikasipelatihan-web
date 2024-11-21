<?php

namespace App\Http\Controllers;

use App\Models\JenisPenggunaModel;
use App\Models\KelolaAdminModel;
use App\Models\KelolaDosenModel;
use App\Models\KelolaPimpinanModel;
use App\Models\KelolaTendikModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    public function export_pdf()
    {
        // Fetch all users and their associated roles
        $pengguna = Pengguna::select('pengguna.username', 'jenis_pengguna.nama_jenis_pengguna')
            ->join('jenis_pengguna', 'pengguna.id_jenis_pengguna', '=', 'jenis_pengguna.id_jenis_pengguna')
            ->orderBy('pengguna.username', 'asc')
            ->get();

        // Generate PDF using DOMPDF
        $pdf = Pdf::loadView('pengguna.export_pdf', compact('pengguna'));
        $pdf->setPaper('a4', 'portrait'); // Set paper size and orientation

        // Stream the PDF to the browser
        return $pdf->stream('Data_Pengguna_' . date('Y-m-d_H-i-s') . '.pdf');
    }
    public function export_excel()
    {
        // Fetch users with their roles
        $pengguna = Pengguna::select('pengguna.username', 'jenis_pengguna.nama_jenis_pengguna')
            ->join('jenis_pengguna', 'pengguna.id_jenis_pengguna', '=', 'jenis_pengguna.id_jenis_pengguna')
            ->orderBy('pengguna.username', 'asc')
            ->get();

        // Create new Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Column headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Role');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Insert user data
        $row = 2;
        foreach ($pengguna as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->username);
            $sheet->setCellValue('C' . $row, $data->nama_jenis_pengguna);
            $row++;
        }

        // Auto resize columns
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Save the Excel file and serve it for download
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_Pengguna_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Send the file to the browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // Menampilkan halaman profil
    public function showProfile()
    {
        $pengguna = Auth::user();
        $pengguna->load(['admin', 'dosen', 'tendik', 'pimpinan', 'jenisPengguna']);

        $activeMenu = 'profile'; // Set active menu untuk halaman profile

        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Home']
        ];

        return view('profile', compact('pengguna', 'activeMenu', 'breadcrumb'));
    }

    public function uploadProfilePicture(Request $request)
    {
        // Validasi input untuk gambar profil
        $request->validate([
            'gambar_profil'  => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Temukan pengguna berdasarkan ID
        $pengguna = Pengguna::findOrFail(auth()->user()->id_pengguna);

        // Validasi id_jenis_pengguna untuk menentukan jenis pengguna
        switch ($pengguna->id_jenis_pengguna) {
            case 1: // Admin
                $userModel = KelolaAdminModel::class;
                break;
            case 2: // Dosen
                $userModel = KelolaDosenModel::class;
                break;
            case 3: // Tendik
                $userModel = KelolaTendikModel::class;
                break;
            case 4: // Pimpinan
                $userModel = KelolaPimpinanModel::class;
                break;
            default:
                return redirect()->route('profile')->with('error', 'Jenis pengguna tidak valid.');
        }

        // Temukan model pengguna terkait (Admin/Dosen/Tendik/Pimpinan)
        $user = $userModel::where('id_pengguna', $pengguna->id_pengguna)->firstOrFail();

        // Proses upload gambar profil
        if ($request->hasFile('gambar_profil')) {
            // Hapus gambar profil lama jika ada
            if ($user->gambar_profil && Storage::disk('public')->exists($user->gambar_profil)) {
                Storage::disk('public')->delete($user->gambar_profil);
            }

            // Simpan gambar baru
            $path = $request->file('gambar_profil')->store('profile_pictures', 'public');
            $user->gambar_profil = $path;
        }

        // Simpan perubahan gambar profil
        $user->save();

        return redirect()->route('profile')->with('success', 'Gambar profil berhasil diperbarui.');
    }


    // Update profile information
    public function updateProfile(Request $request)
    {
        // Validasi input untuk informasi pengguna
        $request->validate([
            'username'       => 'sometimes|required|string|min:3|unique:pengguna,username,' . auth()->user()->id_pengguna . ',id_pengguna',
        ]);

        // Temukan pengguna berdasarkan ID
        $pengguna = Pengguna::findOrFail(auth()->user()->id_pengguna);

        // Pembaruan informasi pengguna
        if ($request->filled('username')) {
            $pengguna->username = $request->username;
        }

        $pengguna->save();

        return redirect()->route('profile')->with('success', 'Informasi pengguna berhasil diperbarui.');
    }


    // Change user password
    public function changePassword(Request $request)
    {
        // Validasi input untuk password baru
        $request->validate([
            'password'       => 'required|confirmed|min:6',
        ]);

        // Temukan pengguna berdasarkan ID
        $pengguna = Pengguna::findOrFail(auth()->user()->id_pengguna);

        // Update password pengguna
        $pengguna->password = bcrypt($request->password);
        $pengguna->save();

        return redirect()->route('profile')->with('success', 'Password berhasil diubah.');
    }
}