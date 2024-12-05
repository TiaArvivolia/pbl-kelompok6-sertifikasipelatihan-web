<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validasi input
        $rules = [
            'username' => 'required|string|min:3|unique:pengguna,username',
            'nama_lengkap' => 'required|string|max:100',
            'password' => 'required|string|min:5',
            'jenis_pengguna' => 'required|exists:jenis_pengguna,id_jenis_pengguna',
        ];

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan data pengguna
        $pengguna = Pengguna::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
            'id_jenis_pengguna' => $request->jenis_pengguna, // Pastikan kolom ini ada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengembalikan response JSON jika pengguna berhasil dibuat
        if ($pengguna) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dibuat.',
                'pengguna' => $pengguna,
            ], 201);
        }

        // Mengembalikan response JSON jika proses penyimpanan gagal
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan, pengguna tidak dapat dibuat.',
        ], 409);
    }
}
