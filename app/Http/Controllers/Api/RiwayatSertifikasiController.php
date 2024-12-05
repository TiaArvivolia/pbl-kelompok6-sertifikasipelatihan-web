<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatSertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatSertifikasiController extends Controller
{
    public function index()
    {
        // Return all Riwayat Sertifikasi records
        return RiwayatSertifikasiModel::all();
    }

    public function store(Request $request)
    {
        // Set validation rules
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'level_sertifikasi' => 'required|in:Nasional,Internasional',
            'jenis_sertifikasi' => 'required|in:Profesi,Keahlian',
            'no_sertifikat' => 'required|string|max:100',
            'tanggal_terbit' => 'required|date',
            'masa_berlaku' => 'nullable|date|after:tanggal_terbit',
            'dokumen_sertifikat' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,pdf,docx,xlsx|max:10240', // Max 10MB
            'mk_list' => 'nullable|array',
            'mk_list.*' => 'exists:mata_kuliah,id_mata_kuliah',
            'bidang_minat_list' => 'nullable|array',
            'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat',
            'id_periode' => 'required|exists:periode,id_periode',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'msgField' => $validator->errors(),
            ]);
        }

        // Handle file upload if it exists
        $dokumenPath = null;
        if ($request->hasFile('dokumen_sertifikat') && $request->file('dokumen_sertifikat')->isValid()) {
            $file = $request->file('dokumen_sertifikat');
            $dokumenPath = $file->store('dokumen', 'public');
        }

        // Create the new Riwayat Sertifikasi
        $sertifikasi = RiwayatSertifikasiModel::create([
            'id_pengguna' => $request->id_pengguna,
            'level_sertifikasi' => $request->level_sertifikasi,
            'jenis_sertifikasi' => $request->jenis_sertifikasi,
            'no_sertifikat' => $request->no_sertifikat,
            'tanggal_terbit' => $request->tanggal_terbit,
            'masa_berlaku' => $request->masa_berlaku,
            'dokumen_sertifikat' => $dokumenPath,
            'mk_list' => $request->has('mk_list') ? json_encode($request->mk_list) : null,
            'bidang_minat_list' => $request->has('bidang_minat_list') ? json_encode($request->bidang_minat_list) : null,
            'id_periode' => $request->id_periode,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Riwayat Sertifikasi successfully created',
            'data' => $sertifikasi
        ]);
    }

    public function show($id)
    {
        // Retrieve Riwayat Sertifikasi by ID
        $sertifikasi = RiwayatSertifikasiModel::find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json($sertifikasi);
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'level_sertifikasi' => 'required|in:Nasional,Internasional',
            'jenis_sertifikasi' => 'required|in:Profesi,Keahlian',
            'no_sertifikat' => 'required|string|max:100',
            'tanggal_terbit' => 'required|date',
            'masa_berlaku' => 'nullable|date|after:tanggal_terbit',
            'dokumen_sertifikat' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,pdf,docx,xlsx|max:10240',
            'mk_list' => 'nullable|array',
            'mk_list.*' => 'exists:mata_kuliah,id_mata_kuliah',
            'bidang_minat_list' => 'nullable|array',
            'bidang_minat_list.*' => 'exists:bidang_minat,id_bidang_minat',
            'id_periode' => 'required|exists:periode,id_periode',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'msgField' => $validator->errors(),
            ]);
        }

        // Find the existing Riwayat Sertifikasi record
        $sertifikasi = RiwayatSertifikasiModel::find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Handle file upload if it exists
        if ($request->hasFile('dokumen_sertifikat') && $request->file('dokumen_sertifikat')->isValid()) {
            $file = $request->file('dokumen_sertifikat');
            $dokumenPath = $file->store('dokumen', 'public');
            $sertifikasi->dokumen_sertifikat = $dokumenPath;
        }

        // Update the Riwayat Sertifikasi record
        $sertifikasi->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Riwayat Sertifikasi successfully updated',
            'data' => $sertifikasi
        ]);
    }

    public function destroy($id)
    {
        // Find the record to delete
        $sertifikasi = RiwayatSertifikasiModel::find($id);

        if (!$sertifikasi) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Delete the record
        $sertifikasi->delete();

        return response()->json([
            'status' => true,
            'message' => 'Riwayat Sertifikasi successfully deleted'
        ]);
    }
}