<?php

namespace App\Http\Controllers;

use App\Models\VendorPelatihan;
use Illuminate\Http\Request;
use DataTables;

class VendorSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Vendor Sertifikasi',  
            'list' => ['Home', 'Vendor Sertifikasi']
        ];

        $page = (object) [
            'title' => 'Daftar Vendor Sertifikasi'
        ];
    
        $activeMenu = ' ';  
    
        return view('vendorsertifikasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
    

     public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = vendorsertifikasi::all();
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
