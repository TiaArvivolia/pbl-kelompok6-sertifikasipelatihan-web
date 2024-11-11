<?php

namespace App\Http\Controllers;

use App\Models\SertifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class InputSertifikasiController extends Controller
{
    // Display the sertifikasi management page
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Riwayat Sertifikasi',
            'list' => ['Home', 'Riwayat Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Daftar riwayat sertifikasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'inputSertifikasi'; // Set the active menu

        $riwayat_sertifikasi = SertifikasiModel::all(); // Get all sertifikasi records

        // Update the key to match the variable used in the view
        return view('input_sertifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'riwayat_sertifikasi' => $riwayat_sertifikasi, // Ensure this matches the variable name in the view
            'activeMenu' => $activeMenu
        ]);
    }

    public function create()
    {
        return view('input_sertifikasi.create_ajax');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dosen' => 'required|string|max:255',
            'id_sertifikasi' => 'required|string|max:255',
            'nama_sertifikasi' => 'required|string|max:255',
            'no_sertifikat' => 'required|string|max:255',
            'jenis_sertifikasi' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'penyelenggara' => 'required|string|max:255',
            'dokumen_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $riwayat_sertifikasi = new SertifikasiModel();
        $riwayat_sertifikasi->id_dosen = $request->id_dosen;
        $riwayat_sertifikasi->id_sertifikasi = $request->id_sertifikasi;
        $riwayat_sertifikasi->nama_sertifikasi = $request->nama_sertifikasi;
        $riwayat_sertifikasi->no_sertifikat = $request->no_sertifikat;
        $riwayat_sertifikasi->jenis_sertifikasi = $request->jenis_sertifikasi;
        $riwayat_sertifikasi->tanggal_terbit = $request->tanggal_terbit;
        $riwayat_sertifikasi->masa_berlaku = $request->masa_berlaku;
        $riwayat_sertifikasi->penyelenggara = $request->penyelenggara;

        if ($request->hasFile('dokumen_sertifikat')) {
            $filePath = $request->file('dokumen_sertifikat')->store('sertifikasi', 'public');
            $riwayat_sertifikasi->dokumen_sertifikat = $filePath;
        }

        $riwayat_sertifikasi->save();

        return redirect()->route('inputSertifikasi.index')->with('success', 'Sertifikasi berhasil ditambahkan.');
    }
}

