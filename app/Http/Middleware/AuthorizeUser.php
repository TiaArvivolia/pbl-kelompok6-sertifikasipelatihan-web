<?php

// App/Http/Middleware/AuthorizeUser.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Mengambil pengguna yang terautentikasi
        $pengguna = $request->user();  // Di sini kita mengasumsikan bahwa 'user()' adalah instansi dari model Pengguna
        
        // Mengambil ID role pengguna yang terautentikasi
        $user_role_id = $pengguna->jenisPengguna->kode_jenis_pengguna;  // Mengakses ID jenis pengguna yang terkait dengan pengguna

        // Cek apakah ID role pengguna ada dalam array roles yang diteruskan
        if (in_array($user_role_id, $roles)) {
            return $next($request); // Jika ada, lanjutkan request
        }

        // Jika tidak sesuai, tampilkan error 403
        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
    }
}