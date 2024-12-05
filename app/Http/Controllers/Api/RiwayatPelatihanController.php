<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatPelatihanController extends Controller
{
    public function index()
    {
        // Mengambil semua data riwayat pelatihan
        return RiwayatPelatihanModel::all();
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required|exists:m_pengguna,id_pengguna',
            'id_pelatihan' => 'required|exists:m_daftar_pelatihan,id_pelatihan',
            'level_pelatihan' => 'required|string|max:100',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'dokumen_pelatihan' => 'nullable|string|max:255',
            'mk_list' => 'nullable|string|max:255',
            'bidang_minat_list' => 'nullable|string|max:255',
            'id_periode' => 'required|exists:m_periode,id_periode'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Menyimpan data riwayat pelatihan
        $riwayat = RiwayatPelatihanModel::create([
            'id_pengguna' => $request->id_pengguna,
            'id_pelatihan' => $request->id_pelatihan,
            'level_pelatihan' => $request->level_pelatihan,
            'nama_pelatihan' => $request->nama_pelatihan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi' => $request->lokasi,
            'penyelenggara' => $request->penyelenggara,
            'dokumen_pelatihan' => $request->dokumen_pelatihan,
            'mk_list' => $request->mk_list,
            'bidang_minat_list' => $request->bidang_minat_list,
            'id_periode' => $request->id_periode,
        ]);

        return response()->json($riwayat, 201);
    }

    public function show(RiwayatPelatihanModel $riwayat)
    {
        // Menampilkan riwayat pelatihan berdasarkan ID
        return response()->json($riwayat);
    }

    public function update(Request $request, RiwayatPelatihanModel $riwayat)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required|exists:m_pengguna,id_pengguna',
            'id_pelatihan' => 'required|exists:m_daftar_pelatihan,id_pelatihan',
            'level_pelatihan' => 'required|string|max:100',
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'dokumen_pelatihan' => 'nullable|string|max:255',
            'mk_list' => 'nullable|string|max:255',
            'bidang_minat_list' => 'nullable|string|max:255',
            'id_periode' => 'required|exists:m_periode,id_periode'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Memperbarui data riwayat pelatihan
        $riwayat->update($request->all());

        return response()->json($riwayat);
    }

    public function destroy(RiwayatPelatihanModel $riwayat)
    {
        // Menghapus data riwayat pelatihan
        $riwayat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pelatihan terhapus',
        ]);
    }
}