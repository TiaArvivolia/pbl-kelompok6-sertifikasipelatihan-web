<?php

namespace App\Http\Controllers;

use App\Models\PelatihanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class InputPelatihanController extends Controller
{
    // Display the Pelatihan management page
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Riwayat Pelatihan',
            'list' => ['Home', 'Riwayat Pelatihan']
        ];

        $page = (object) [
            'title' => 'Daftar riwayat pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'inputPelatihan'; // Set the active menu

        $riwayat_pelatihan = PelatihanModel::all(); // Get all pelatihan records

        // Update the key to match the variable used in the view
        return view('input_pelatihan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'riwayat_pelatihan' => $riwayat_pelatihan, // Ensure this matches the variable name in the view
            'activeMenu' => $activeMenu
        ]);
    }

    public function create()
    {
        return view('input_pelatihan.create_ajax');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dosen' => 'required|string|max:255',
            'id_pelatihan' => 'required|string|max:255',
            'nama_pelatihan' => 'required|string|max:255',
            'no_sertifikat' => 'required|string|max:255',
            'jenis_pelatihan' => 'required|string|max:255',
            'tanggal_terbit' => 'required|date',
            'masa_berlaku' => 'nullable|date',
            'penyelenggara' => 'required|string|max:255',
            'dokumen_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $riwayat_pelatihan = new PelatihanModel();
        $riwayat_pelatihan->id_dosen = $request->id_dosen;
        $riwayat_pelatihan->id_pelatihan = $request->id_pelatihan;
        $riwayat_pelatihan->nama_pelatihan = $request->nama_pelatihan;
        $riwayat_pelatihan->no_sertifikat = $request->no_sertifikat;
        $riwayat_pelatihan->jenis_pelatihan = $request->jenis_pelatihan;
        $riwayat_pelatihan->tanggal_terbit = $request->tanggal_terbit;
        $riwayat_pelatihan->masa_berlaku = $request->masa_berlaku;
        $riwayat_pelatihan->penyelenggara = $request->penyelenggara;

        if ($request->hasFile('dokumen_sertifikat')) {
            $filePath = $request->file('dokumen_sertifikat')->store('pelatihan', 'public');
            $riwayat_pelatihan->dokumen_sertifikat = $filePath;
        }

        $riwayat_pelatihan->save();

        return redirect()->route('input_pelatihan.index')->with('success', 'pelatihan berhasil ditambahkan.');
    }
}

