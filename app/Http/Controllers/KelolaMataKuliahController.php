<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use DataTables;

class KelolaMataKuliahController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Mata Kuliah',  
            'list' => ['Home', 'Mata Kuliah']
        ];

        $page = (object) [
            'title' => 'Daftar Mata Kuliah'
        ];
    
        $activeMenu = 'mataKuliah'; 
    
        return view('mata_kuliah.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
    

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = MataKuliah::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($row){
                    return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Edit</a> 
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger">Delete</a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

}
