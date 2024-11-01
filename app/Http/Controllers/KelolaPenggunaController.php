<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelolaPenggunaController extends Controller
{
    public function index() {
        // Contoh data pengguna (statis)
        $pengguna = [
            [
                'id' => 1,
                'nama_lengkap' => 'kelompok6',
                'jenis_pengguna' => 'admin',
                'email' => 'jkelompok6@egmail.com',
                'nomor_telepon' => '1234567890',
            ],
        
            
        ];

        $breadcrumb = (object) [
            'title' => 'Kelola Pengguna',
            'list' => ['Home', 'Kelola Pengguna']
        ];
        $activeMenu = 'kelola-pengguna';

        return view('kelola-pengguna.index', compact('pengguna', 'breadcrumb', 'activeMenu'));
    }
}
