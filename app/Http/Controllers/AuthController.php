<?php

namespace App\Http\Controllers;

use App\Models\JenisPenggunaModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\LevelModel;
    use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }
    public function logout(Request $request)
    {
        // Proses logout
        Auth::logout();

        // Menghapus sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Mengecek apakah permintaan Ajax
        if ($request->ajax()) {
            return response()->json(['message' => 'Logout successful']);
        }

        // Jika bukan permintaan Ajax, redirect ke halaman login
        return redirect('landing_page');
    }

    public function register()
    {
        // Mengambil data jenis pengguna untuk dropdown menggunakan Eloquent
        $roles = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();
    
        return view('auth.register', ['roles' => $roles]);
    }
    
    public function postRegister(Request $request)
    {
        // Validasi input
        $rules = [
            'username' => 'required|string|min:3|unique:pengguna,username',
            'nama_lengkap' => 'required|string|max:100',
            'password' => 'required|string|min:5',
            'jenis_pengguna' => 'required|exists:jenis_pengguna,id_jenis_pengguna',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // Simpan data pengguna
        Pengguna::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make($request->password),
            'id_jenis_pengguna' => $request->jenis_pengguna, // Pastikan kolom ini ada
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return redirect('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
    

}