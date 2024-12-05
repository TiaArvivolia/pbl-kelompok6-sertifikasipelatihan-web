<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KelolaDosenModel;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelolaDosenController extends Controller
{
    // List all dosen
    public function index()
    {
        return response()->json(KelolaDosenModel::all());
    }

    // Store a new dosen
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:pengguna,username', // Unique username
            'password' => 'required|string|min:8|confirmed', // Ensure password is confirmed
            'nama_lengkap' => 'required|string|max:100',
            'nip' => 'required|string|max:20',
            'nidn' => 'required|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Insert into pengguna table and get the ID
        $id_pengguna = Pengguna::create([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')), // Hash the password
            'id_jenis_pengguna' => 2, // For dosen
        ])->id;

        // Prepare data for the dosen table
        $dosen = KelolaDosenModel::create([
            'id_pengguna' => $id_pengguna,
            'nama_lengkap' => $request->input('nama_lengkap'),
            'nip' => $request->input('nip'),
            'nidn' => $request->input('nidn'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'no_telepon' => $request->input('no_telepon'),
            'email' => $request->input('email'),
            'gambar_profil' => $request->file('gambar_profil') ? $request->file('gambar_profil')->store('profile_pictures', 'public') : null,
        ]);

        return response()->json($dosen, 201);
    }

    // Show a specific dosen
    public function show($id)
    {
        $dosen = KelolaDosenModel::with(['pengguna'])->find($id);

        if (!$dosen) {
            return response()->json(['error' => 'Dosen not found'], 404);
        }

        return response()->json($dosen);
    }

    // Update an existing dosen
    public function update(Request $request, $id)
    {
        // Find the dosen by ID
        $dosen = KelolaDosenModel::find($id);

        if (!$dosen) {
            return response()->json(['error' => 'Dosen not found'], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'nip' => 'required|string|max:20',
            'nidn' => 'required|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
            'gambar_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the dosen data
        $dosen->update($request->all());

        // Optionally handle file upload for profile picture
        if ($request->hasFile('gambar_profil')) {
            $dosen->gambar_profil = $request->file('gambar_profil')->store('profile_pictures', 'public');
            $dosen->save();
        }

        return response()->json($dosen);
    }

    // Delete a specific dosen
    public function destroy($id)
    {
        $dosen = KelolaDosenModel::find($id);

        if (!$dosen) {
            return response()->json(['error' => 'Dosen not found'], 404);
        }

        $dosen->delete();

        return response()->json(['message' => 'Dosen deleted successfully']);
    }
}
