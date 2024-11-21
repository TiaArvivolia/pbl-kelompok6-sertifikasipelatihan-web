<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables; // Ensure you have Yajra DataTables package installed

class PenggunaController extends Controller
{
    // Menampilkan halaman awal pengguna
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'Pengguna']
        ];

        $page = (object) [
            'title' => 'Daftar pengguna yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pengguna';

        return view('pengguna.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data pengguna dalam bentuk JSON untuk datatables
    public function list(Request $request)
    {
        $pengguna = Pengguna::select('id_pengguna', 'username', 'nama_lengkap', 'email', 'peran')->get();

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

    // Menampilkan halaman form tambah pengguna baru dengan AJAX
    public function create_ajax()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Pengguna',
            'list' => ['Home', 'Pengguna', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah pengguna baru'
        ];

        return view('pengguna.create', ['breadcrumb' => $breadcrumb, 'page' => $page]);
    }

    // Menyimpan data pengguna baru dengan AJAX
    public function store_ajax(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_pengguna,username',
            'password' => 'required|min:5',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:m_pengguna,email',
            'peran' => 'required|in:Admin,Dosen,Pimpinan',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        $pengguna = Pengguna::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telepon' => $request->no_telepon,
            'nik' => $request->nik,
            'nidn' => $request->nidn,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'peran' => $request->peran,
            'photo_profile' => $request->photo_profile,
        ]);

        return response()->json(['success' => true, 'message' => 'Data pengguna berhasil disimpan', 'data' => $pengguna]);
    }

    // Menampilkan detail pengguna dengan AJAX
    public function show_ajax(string $id)
    {
        $pengguna = Pengguna::find($id);
        if (!$pengguna) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan']);
        }

        return response()->json(['success' => true, 'data' => $pengguna]);
    }

    // Menampilkan halaman form edit pengguna dengan AJAX
    public function edit_ajax(string $id)
    {
        $pengguna = Pengguna::find($id);
        if (!$pengguna) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan']);
        }

        $breadcrumb = (object) [
            'title' => 'Edit Pengguna',
            'list' => ['Home', 'Pengguna', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit pengguna'
        ];

        return view('pengguna.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pengguna' => $pengguna]);
    }

    // Menyimpan perubahan data pengguna dengan AJAX
    public function update_ajax(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_pengguna,username,' . $id . ',id_pengguna',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:m_pengguna,email,' . $id . ',id_pengguna',
            'peran' => 'required|in:Admin,Dosen,Pimpinan',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);

        $pengguna = Pengguna::find($id);
        if (!$pengguna) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan']);
        }

        $pengguna->update([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'password' => $request->password ? bcrypt($request->password) : $pengguna->password,
            'nip' => $request->nip,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telepon' => $request->no_telepon,
            'nik' => $request->nik,
            'nidn' => $request->nidn,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'peran' => $request->peran,
            'photo_profile' => $request->photo_profile,
        ]);

        return response()->json(['success' => true, 'message' => 'Data pengguna berhasil diubah']);
    }

    // Menampilkan konfirmasi penghapusan pengguna dengan AJAX
    public function confirm_ajax(string $id)
    {
        $pengguna = Pengguna::find($id);
        if (!$pengguna) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan']);
        }

        return response()->json(['success' => true, 'data' => $pengguna]);
    }

    // Menghapus data pengguna dengan AJAX
    public function delete_ajax(string $id)
    {
        $check = Pengguna::find($id);
        if (!$check) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan']);
        }

        try {
            Pengguna::destroy($id);
            return response()->json(['success' => true, 'message' => 'Data pengguna berhasil dihapus']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Data pengguna gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini']);
        }
    }

    // Menampilkan form import excel
    public function import()
    {
        return view('pengguna.import');
    }

    // Mengimpor data pengguna dari excel
    public function import_ajax(Request $request)
    {
        // Logic untuk mengimpor data dari file excel
    }

    // Mengekspor data pengguna ke file excel
    public function export_excel()
    {
        // Logic untuk mengekspor data ke file excel
    }

    // Mengekspor data pengguna ke file PDF
    public function export_pdf()
    {
        // Logic untuk mengekspor data ke file PDF
    }


    // Menampilkan halaman profil
    public function showProfile()
    {
        $user = Auth::user(); // Mendapatkan user yang sedang login
        $activeMenu = 'profile'; // Set active menu untuk halaman profile

        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Home']
        ];

        return view('profile', compact('user', 'activeMenu', 'breadcrumb'));
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Hapus gambar profil lama jika ada
        if ($user->profile_picture) {
            Storage::delete($user->profile_picture);
        }

        // Simpan gambar baru
        $path = $request->file('profile_picture')->store('profile_pictures');

        // Update path di database
        $user->profile_picture = $path;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile picture updated successfully.');
    }

    // Update profile information
    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . Auth::id() . ',user_id',
            'nama'     => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $user->update([
            'username' => $request->username,
            'nama'     => $request->nama,
        ]);

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    // Change user password
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('profile')->with('success', 'Password changed successfully.');
    }
}