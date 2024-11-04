<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DaftarPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pelatihan',  
            'list' => ['Home', 'Daftar Pelatihan']
        ];

        $page = (object) [
            'title' => 'Daftar Pelatihan'
        ];
    
        $activeMenu = ' ';  
    
        return view('daftar-pelatihan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
}
