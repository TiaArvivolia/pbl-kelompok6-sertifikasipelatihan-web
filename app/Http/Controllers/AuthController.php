<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\LevelModel;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home 
            return redirect('/');
        }
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
        return redirect('login');
    }

    public function register()
    {
        $level = pengguna::select('peran')->get();
        return view('auth.register')->with('peran', $level);
    }
    public function postRegister(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:5'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            // Hash password sebelum disimpan
            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            // Simpan data user
            Pengguna::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
                'redirect' => url('login') // Redirect ke halaman login
            ]);
        }
        // Jika bukan AJAX, arahkan ke halaman login
        return redirect('login')->with('success', 'Registrasi berhasil!');
    }
}
