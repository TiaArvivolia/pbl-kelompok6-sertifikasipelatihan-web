<?php

namespace App\Http\Controllers;

use App\Models\BidangMinat;
use Illuminate\Http\Request;
use DataTables;

class KelolaBidangMinatController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Bidang Minat',  
            'list' => ['Home', 'Bidang Minat']
        ];

        $page = (object) [
            'title' => 'Daftar Bidang Minat'
        ];
    
        $activeMenu = 'bidangMinat'; 
    
        return view('bidang_minat.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
    

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = BidangMinat::all();
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
