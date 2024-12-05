<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        return response()->json(Pengguna::all());
    }

    // Menyimpan data pengguna baru
    public function store(Request $request)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|unique:m_pengguna,username',
            'password' => 'required|min:5',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:m_pengguna,email',
            'peran' => 'required|in:Admin,Dosen,Pimpinan',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);
        
        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create new Pengguna
        $pengguna = Pengguna::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'peran' => $request->peran,
            'nip' => $request->nip,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telepon' => $request->no_telepon,
            'nik' => $request->nik,
            'nidn' => $request->nidn,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'photo_profile' => $request->photo_profile,
        ]);

        return response()->json(['success' => true, 'message' => 'Data pengguna berhasil disimpan', 'data' => $pengguna], 201);
    }

    // Menampilkan detail pengguna
    public function show(string $id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $pengguna]);
    }

    // Mengupdate data pengguna
    public function update(Request $request, string $id)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|unique:m_pengguna,username,' . $id . ',id_pengguna',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:m_pengguna,email,' . $id . ',id_pengguna',
            'peran' => 'required|in:Admin,Dosen,Pimpinan',
            // Tambahkan validasi lainnya sesuai kebutuhan
        ]);
        
        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan'], 404);
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

    // Menghapus data pengguna
    public function destroy(string $id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(['success' => false, 'message' => 'Data pengguna tidak ditemukan'], 404);
        }

        try {
            $pengguna->delete();
            return response()->json(['success' => true, 'message' => 'Data pengguna berhasil dihapus']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Data pengguna gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'], 500);
        }
    }
}