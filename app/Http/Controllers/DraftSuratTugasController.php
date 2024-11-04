<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DraftSuratTugasController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Draft Permohonan Surat Tugas',  
            'list' => ['Home', 'Draft Permohonan Surat Tugas']
        ];

        $page = (object) [
            'title' => 'Draft Permohonan Surat Tugas'
        ];
    
        $activeMenu = ' ';  
    
        return view('draft-surat-tugas.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
}
