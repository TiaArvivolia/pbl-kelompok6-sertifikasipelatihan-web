<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\DaftarPelatihanModel;
use App\Models\PengajuanPelatihanModel;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class PengajuanPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pengajuan Pelatihan',
            'list' => ['Home', 'Pengajuan Pelatihan']
        ];

        $page = (object) [
            'title' => 'Daftar Pengajuan Pelatihan'
        ];

        $activeMenu = 'pengajuan_pelatihan';

        return view('pengajuan_pelatihan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $query = PengajuanPelatihanModel::with(['pengguna.dosen', 'pengguna.tendik', 'daftarPelatihan'])
            ->select('id_pengajuan', 'id_pengguna', 'id_pelatihan', 'tanggal_pengajuan', 'status', 'catatan', 'id_peserta'); // Menambahkan kolom id_peserta

        // Return DataTables response
        return DataTables::of($query)
            ->addIndexColumn()
            // ->addColumn('nama_lengkap', function ($pengajuan) {
            //     $pesertaIds = json_decode($pengajuan->id_peserta);
            //     $namaPeserta = [];
            //     if ($pesertaIds) {
            //         foreach ($pesertaIds as $index => $id) {
            //             $peserta = Pengguna::find($id);
            //             if ($peserta) {
            //                 $namaPeserta[] = ($index + 1) . ') ' .
            //                     ($peserta->dosen ? $peserta->dosen->nama_lengkap : ($peserta->tendik ? $peserta->tendik->nama_lengkap : 'Tidak Dikenal'));
            //             } else {
            //                 $namaPeserta[] = ($index + 1) . ') Tidak Ditemukan';
            //             }
            //         }
            //     }
            //     return implode('<br>', $namaPeserta);
            // })
            ->addColumn('jumlah_peserta', function ($pengajuan) {
                $pesertaIds = json_decode($pengajuan->id_peserta);
                if ($pesertaIds) {
                    return count($pesertaIds);
                }
                return 0; // If no participants, return 0
            })
            ->addColumn('nama_pelatihan', function ($pengajuan) {
                return $pengajuan->daftarPelatihan ? $pengajuan->daftarPelatihan->nama_pelatihan : '-';
            })
            ->addColumn('draft', function ($pengajuan) {
                $btn = '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/export_word') . '\')" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Detail</button> ';
                return $btn;
            })
            ->addColumn('aksi', function ($pengajuan) {
                $btn = '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/show_ajax') . '\')" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</button> ';
                // Admin/pimpinan  dapat melakukan operasi CRUD pada Pengajuan
                if (auth()->user()->id_jenis_pengguna == 1 || auth()->user()->id_jenis_pengguna == 4) {
                    // Menampilkan tombol Edit dan Hapus hanya untuk admin/pimpinan
                    $btn .= '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/edit_ajax') . '\')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/delete_ajax') . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>';
                }
                return $btn;
            })
            ->rawColumns(['aksi', 'nama_lengkap', 'draft']) // pastikan kolom nama_lengkap di rawColumns
            ->make(true);
    }

    public function create_ajax()
    {
        $pengguna = Pengguna::with(['dosen', 'tendik'])
            ->whereHas('dosen')
            ->orWhereHas('tendik')
            ->get();
        $daftarPelatihan = DaftarPelatihanModel::all();
        $periode = PeriodeModel::all(); // Mengambil semua periode pelatihan

        return view('pengajuan_pelatihan.create_ajax', compact('pengguna', 'daftarPelatihan', 'periode'));
    }

    public function store_ajax(Request $request)
    {
        $rules = [
            // 'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'id_pelatihan' => 'required|exists:daftar_pelatihan,id_pelatihan',
            'tanggal_pengajuan' => 'required|date',
            'status' => 'required|in:Menunggu,Disetujui,Ditolak',
            'catatan' => 'nullable|string',
            'id_peserta' => 'required|array', // id_peserta harus berupa array
            'id_peserta.*' => 'exists:pengguna,id_pengguna', // Setiap id dalam id_peserta harus ada di tabel pengguna
            'id_periode' => 'required|exists:periode,id_periode',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // Ambil semua data dari request
        $data = $request->all();

        // Ubah id_peserta menjadi JSON
        $data['id_peserta'] = json_encode($request->id_peserta);

        // Simpan data ke dalam database
        PengajuanPelatihanModel::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Pengajuan Pelatihan berhasil disimpan'
        ]);
    }

    public function show_ajax($id)
    {
        $pengajuan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan', 'periode'])->find($id);

        if (!$pengajuan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        // Decode the JSON for id_peserta and retrieve user names
        $participantNames = [];
        if ($pengajuan->id_peserta) {
            $idPesertaArray = json_decode($pengajuan->id_peserta);
            foreach ($idPesertaArray as $index => $idPeserta) {
                // Find the user (pengguna) by id_pengguna
                $user = Pengguna::find($idPeserta);
                if ($user) {
                    // Check if the user is a dosen or tendik and get the name accordingly
                    $participantNames[] = ($index + 1) . ') ' . ($user->dosen ? $user->dosen->nama_lengkap : ($user->tendik ? $user->tendik->nama_lengkap : 'Tidak Tersedia'));
                }
            }
        }

        return view('pengajuan_pelatihan.show_ajax', compact('pengajuan', 'participantNames'));
    }



    public function edit_ajax(string $id)
    {
        $pengajuan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan'])->find($id);

        if (!$pengajuan) {
            return response()->json(['error' => 'Data yang anda cari tidak ditemukan'], 404);
        }

        $pengguna = Pengguna::with(['dosen', 'tendik'])
            ->whereHas('dosen')
            ->orWhereHas('tendik')
            ->get();
        $daftarPelatihan = DaftarPelatihanModel::all();
        $periode = PeriodeModel::all();

        return view('pengajuan_pelatihan.edit_ajax', compact('pengajuan', 'pengguna', 'daftarPelatihan', 'periode'));
    }

    public function update_ajax(Request $request, $id)
    {
        $rules = [
            // 'id_pengguna' => 'exists:pengguna,id_pengguna',
            'id_pelatihan' => 'required|exists:daftar_pelatihan,id_pelatihan',
            'tanggal_pengajuan' => 'required|date',
            'status' => 'required|in:Menunggu,Disetujui,Ditolak',
            'catatan' => 'nullable|string',
            'id_peserta' => 'required|array', // id_peserta harus berupa array
            'id_peserta.*' => 'exists:pengguna,id_pengguna', // Setiap id dalam id_peserta harus ada di tabel pengguna
            'id_periode' => 'nullable|string|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $pengajuan = PengajuanPelatihanModel::find($id);
        if ($pengajuan) {
            // Perbarui data pengajuan
            $pengajuan->update($request->except('id_peserta')); // Update data selain id_peserta
            $pengajuan->id_peserta = json_encode($request->id_peserta); // Simpan id_peserta sebagai JSON
            $pengajuan->save();

            return response()->json([
                'status' => true,
                'message' => 'Pengajuan Pelatihan berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Pengajuan Pelatihan tidak ditemukan'
        ]);
    }

    public function confirm_ajax(string $id)
    {
        $pengajuan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan'])->find($id);
        return view('pengajuan_pelatihan.confirm_ajax', ['pengajuan' => $pengajuan]);
    }

    public function delete_ajax(string $id)
    {
        try {
            PengajuanPelatihanModel::destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Pengajuan Pelatihan berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Pengajuan Pelatihan gagal dihapus'
            ]);
        }
    }

    public function export_word()
    {
        $pengajuan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan'])
            ->orderBy('id_pengajuan')
            ->get();

        // Inisialisasi PhpWord
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Header
        $header = $section->addTable();
        $header->addRow();
        $header->addCell(1200)->addImage(asset('polinema-bw.png'), [
            'width' => 100,
            'height' => 80,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        ]);
        $cell = $header->addCell(9200);
        $cell->addText('KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI', ['size' => 11], ['alignment' => 'center']);
        $cell->addText('POLITEKNIK NEGERI MALANG', ['size' => 13, 'bold' => true], ['alignment' => 'center']);
        $cell->addText('Jl. Soekarno-Hatta No. 9 Malang 65141', ['size' => 10], ['alignment' => 'center']);
        $cell->addText('Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420', ['size' => 10], ['alignment' => 'center']);
        $cell->addText('Laman: www.polinema.ac.id', ['size' => 10], ['alignment' => 'center']);

        $section->addTextBreak();

        // Judul
        $section->addText('LAPORAN DATA PENGAJUAN PELATIHAN', ['size' => 14, 'bold' => true], ['alignment' => 'center']);
        $section->addTextBreak(2);

        // Tabel Data
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'alignment' => 'center', 'cellMargin' => 50];
        $table = $section->addTable($tableStyle);

        // Header Tabel
        $table->addRow();
        $table->addCell(500, ['bgColor' => 'cccccc'])->addText('No', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('Nama Pengguna', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('Nama Pelatihan', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('Tanggal Pengajuan', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('Status', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000, ['bgColor' => 'cccccc'])->addText('Daftar Peserta', ['bold' => true], ['alignment' => 'center']);

        // Isi Tabel
        foreach ($pengajuan as $key => $item) {
            $namaPelatihan = $item->daftarPelatihan->nama_pelatihan ?? '-';
            $pesertaIds = json_decode($item->id_peserta);
            $daftarPeserta = [];
            if (!empty($pesertaIds)) {
                foreach ($pesertaIds as $index => $id) {
                    $peserta = Pengguna::find($id);
                    $nama = $peserta->dosen->nama_lengkap ?? $peserta->tendik->nama_lengkap ?? 'Tidak Dikenal';
                    $daftarPeserta[] = ($index + 1) . ') ' . $nama;
                }
            }
            $daftarPesertaText = implode("\n", $daftarPeserta);

            // Tambahkan data ke tabel
            $table->addRow();
            $table->addCell(500)->addText($key + 1, [], ['alignment' => 'center']);
            $table->addCell(2000)->addText($item->pengguna->nama ?? '-', ['size' => 10]);
            $table->addCell(2000)->addText($namaPelatihan, ['size' => 10]);
            $table->addCell(2000)->addText($item->tanggal_pengajuan ?? '-', ['size' => 10]);
            $table->addCell(2000)->addText($item->status ?? '-', ['size' => 10]);

            // Untuk Daftar Peserta, menggunakan TextRun agar bisa tampilkan setiap peserta pada baris terpisah
            $cellPeserta = $table->addCell(3000);
            $textRun = $cellPeserta->addTextRun();
            $textRun->addText($daftarPesertaText, ['size' => 10]);
        }

        // Simpan ke memori
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        // Cek apakah file berhasil dibuat
        if (!file_exists($tempFile)) {
            return response()->json(['error' => 'Gagal membuat file'], 500);
        }

        // Pastikan tidak ada output buffer aktif
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Daftarkan penghapusan file setelah respons selesai
        register_shutdown_function(function () use ($tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        });

        // Set header untuk mendownload file dengan encoding UTF-8
        return response()->streamDownload(function () use ($tempFile) {
            // Pastikan file sementara valid sebelum dibaca
            if (file_exists($tempFile)) {
                readfile($tempFile);
            } else {
                echo "File tidak ditemukan.";
            }
        }, 'Laporan_Pengajuan_Pelatihan_' . date('Y-m-d_H-i-s') . '.doc', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . 'Laporan_Pengajuan_Pelatihan_' . date('Y-m-d_H-i-s') . '.doc' . '"',
            'Content-Transfer-Encoding' => 'binary',  // Ensure binary encoding for Word document
            'Cache-Control' => 'no-cache',  // Disable caching for download
        ]);
    }
}