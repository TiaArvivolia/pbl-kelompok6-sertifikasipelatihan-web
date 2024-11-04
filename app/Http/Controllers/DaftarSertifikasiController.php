<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DaftarSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Sertifikasi',  
            'list' => ['Home', 'Daftar Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Daftar Sertifikasi'
        ];
    
        $activeMenu = ' ';  
    
        return view('daftar-sertifikasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
}
