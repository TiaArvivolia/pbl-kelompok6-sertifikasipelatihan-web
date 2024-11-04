<?php

namespace App\Http\Controllers;

use App\Models\VendorPelatihan;
use Illuminate\Http\Request;
use DataTables;

class VendorPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Vendor Pelatihan',  
            'list' => ['Home', 'Vendor Pelatihanh']
        ];

        $page = (object) [
            'title' => 'Daftar Vendor Pelatihan'
        ];
    
        $activeMenu = 'vendorpelatihan';  
    
        return view('vendorpelatihan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
    

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = vendorpelatihan::all();
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
