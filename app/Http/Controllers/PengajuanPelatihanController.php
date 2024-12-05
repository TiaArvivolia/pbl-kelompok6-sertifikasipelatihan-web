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
                $url = url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/export_word');
                $btn = '<button onclick="window.location.href=\'' . $url . '\'" class="btn btn-primary btn-sm">
                            <i class="fa fa-download"></i> Detail
                        </button>';
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

    public function export_word($id)
    {
        $pengajuan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan'])->find($id);

        if (!$pengajuan) {
            return response()->json(['error' => 'Data pengajuan tidak ditemukan'], 404);
        }

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Tambahkan section ke dokumen
        $section = $phpWord->addSection();

        // Header
        $headerTable = $section->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
        ]);
        $headerTable->addRow();

        // Logo (align to left)
        $logoCell = $headerTable->addCell(1000, ['valign' => 'center', 'marginTop' => 0, 'marginBottom' => 0]);
        $logoCell->addImage(
            asset('logo-poltek.png'),
            [
                // 'width' => 80,   // Optional: Set width if needed
                // 'height' => 80,  // Optional: Set height if needed
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT
            ]
        );


        // Text Header
        $headerCell = $headerTable->addCell(6400, ['valign' => 'center', 'marginTop' => 0, 'marginBottom' => 0]);
        $textRun = $headerCell->addTextRun([
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0
        ]);
        $textRun->addText("KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN", ['size' => 12.5]);
        $textRun->addTextBreak();
        $textRun->addText("POLITEKNIK NEGERI MALANG", ['size' => 14, 'bold' => true]);
        $textRun->addTextBreak();
        $textRun->addText("JURUSAN TEKNOLOGI INFORMASI", ['size' => 14, 'bold' => true]);
        $textRun->addTextBreak();
        $textRun->addText("Jl. Soekarno-Hatta No.9 Malang 65141", ['size' => 11]);
        $textRun->addTextBreak();
        $textRun->addText("Telp (0341) 404424 – 404425 Fax (0341) 404420", ['size' => 11]);
        $textRun->addTextBreak();
        $textRun->addText("Laman://www.polinema.ac.id Email:cs@polinema.ac.id", ['size' => 11]);

        // Garis Bawah (Opsional)
        $lineStyle = ['weight' => 3, 'width' => 450, 'height' => 0, 'color' => '#000'];
        $section->addLine($lineStyle);


        // Garis Hitam Tebal di Bawah Header
        // $lineStyle = [
        //     'weight' => 4, // Ketebalan garis
        //     'width' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(15), // Lebar garis (15 cm)
        //     'height' => 0,
        //     'color' => '000000',
        //     'align' => 'center',
        // ];
        // $section->addLine($lineStyle);

        // // Tambahkan Spasi Setelah Garis
        $section->addTextBreak();



        // Informasi Surat
        $section->addText("Nomor   : 92/PL2.TI/KP/2024", ['size' => 12]);
        $section->addText("Malang, " . now()->format('d F Y'), ['size' => 12]);
        $section->addText("Lampiran: -", ['size' => 12]);
        $section->addText("Perihal : Data Pengajuan Pelatihan", ['size' => 12]);
        $section->addTextBreak(2);

        // Penerima Surat
        $section->addText("Kepada\nYth. Ketua Jurusan Teknologi Informasi\nPoliteknik Negeri Malang", ['size' => 12]);
        $section->addText("Dengan Hormat,", ['size' => 12]);
        $section->addTextBreak();

        // Isi Surat
        $section->addText(
            "Sehubungan dengan pelaksanaan kegiatan pengajuan pelatihan oleh " .
                $pengajuan->pengguna->nama_lengkap .
                " dengan rincian sebagai berikut:",
            ['size' => 12]
        );
        $section->addTextBreak();

        // Tabel Data
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'alignment' => 'center'];
        $table = $section->addTable($tableStyle);

        // Header Tabel
        $table->addRow();
        $table->addCell(500, ['bgColor' => 'cccccc'])->addText('No', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000, ['bgColor' => 'cccccc'])->addText('Nama Pelatihan', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('Tanggal Pengajuan', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('Status', ['bold' => true], ['alignment' => 'center']);

        // Isi Tabel
        $table->addRow();
        $table->addCell(500)->addText(1, [], ['alignment' => 'center']);
        $table->addCell(3000)->addText($pengajuan->daftarPelatihan->nama_pelatihan ?? '-', ['size' => 12]);
        $table->addCell(2000)->addText($pengajuan->tanggal_pengajuan ?? '-', ['size' => 12]);
        $table->addCell(2000)->addText($pengajuan->status ?? '-', ['size' => 12]);

        $section->addTextBreak();

        // Penutup Surat
        $section->addText("Demikian surat ini dibuat. Atas perhatian dan kerja sama Anda, kami sampaikan terima kasih.", ['size' => 12]);
        $section->addTextBreak(3);

        $section->addText("Ketua Jurusan,", ['size' => 12]);
        $section->addTextBreak(3);

        $section->addText("Dr. Eng. Rosa Andrie Asmara, ST, MT", ['size' => 12]);
        $section->addText("NIP. 198010102005011001", ['size' => 12]);

        // Simpan Dokumen
        $fileName = 'Surat_Pengajuan_Pelatihan_' . now()->format('Ymd_His') . '.docx';
        $tempFile = storage_path('app/public/' . $fileName);
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }



    // public function exportWord($id)
    // {
    //     $kegiatan = KegiatanModel::find($id);
    //     $anggota = AnggotaModel::where('id_kegiatan', $id)->with('user', 'jabatan')->get();

    //     $phpWord = new \PhpOffice\PhpWord\PhpWord();
    //     $phpWord->setDefaultFontName('Times New Roman');
    //     $phpWord->setDefaultFontSize(12);

    //     $section = $phpWord->addSection();

    //     // Add header information (Kementerian Pendidikan header)
    //     $headerTable = $section->addTable();
    //     $headerTable->addRow();
    //     $headerTable->addCell(1500)->addImage(asset('polinema.png'), ['width' => 60, 'height' => 60, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    //     $headerCell = $headerTable->addCell(8500);

    //     $textRun = $headerCell->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    //     $textRun->addText("KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI", ['size' => 11]);
    //     $textRun->addTextBreak();
    //     $textRun->addText("POLITEKNIK NEGERI MALANG", ['bold' => true, 'size' => 13]);
    //     $textRun->addTextBreak();
    //     $textRun->addText("JL, Soekarno-Hatta No.9 Malang 65141", ['size' => 10]);
    //     $textRun->addTextBreak();
    //     $textRun->addText("Telepon (0341) 404424 Pes. 101-105 0341-404420, Fax. (0341) 404420", ['size' => 10]);
    //     $textRun->addTextBreak();
    //     $textRun->addText("https://www.polinema.ac.id", ['size' => 10]);

    //     // Add a line break
    //     $section->addTextBreak(0);

    //     // Add a horizontal line
    //     $lineStyle = ['weight' => 1, 'width' => 500, 'height' => 0, 'color' => '000000'];
    //     $section->addLine($lineStyle);

    //     // Add a line break
    //     $section->addTextBreak(0);

    //     // Add title and document number
    //     $titleRun = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    //     $titleRun->addText("SURAT TUGAS", ['bold' => true, 'size' => 14]);
    //     $titleRun->addTextBreak();
    //     $titleRun->addText("Nomor : 31464/PL2.1/KP/2024");
    //     $section->addTextBreak();

    //     // Introduction text
    //     $section->addText("Wakil Direktur I memberikan tugas kepada :", ['size' => 12]);
    //     $section->addTextBreak();

    //     // Add table for members (Anggota)
    //     $table = $section->addTable(['width' => 100 * 50]);
    //     $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
    //     $table->addRow();
    //     $table->addCell(1000)->addText("NO", ['bold' => true]);
    //     $table->addCell(4000)->addText("NAMA", ['bold' => true]);
    //     $table->addCell(4000)->addText("NIP", ['bold' => true]);
    //     $table->addCell(4000)->addText("JABATAN", ['bold' => true]);

    //     // Populate table with anggota data
    //     $no = 1;
    //     foreach ($anggota as $member) {
    //         $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'width' => 100 * 50]);
    //         $table->addRow();
    //         $table->addCell(1000)->addText($no);
    //         $table->addCell(4000)->addText($member->user->nama);
    //         $table->addCell(4000)->addText($member->user->NIP);
    //         $table->addCell(4000)->addText($member->jabatan->jabatan_nama);
    //         $no++;
    //     }

    //     // Add kegiatan details in a paragraph
    //     $section->addTextBreak();
    //     $section->addText("Untuk menjadi narasumber kegiatan " . $kegiatan->nama_kegiatan . " yang diselenggarakan oleh " . $kegiatan->deskripsi_kegiatan . " pada tanggal " . date('d F Y', strtotime($kegiatan->tanggal_acara)) . " bertempat di " . $kegiatan->tempat_kegiatan . ".", ['size' => 12]);

    //     $section->addText("Selesai melaksanakan tugas harap melaporkan hasilnya kepada Wakil Direktur I.", ['size' => 12]);
    //     $section->addText("Demikian untuk dilaksanakan sebaik-baiknya.", ['size' => 12]);
    //     $section->addTextBreak();

    //     // Add signature
    //     $signatureTable = $section->addTable();
    //     $signatureTable->addRow();
    //     $signatureCell = $signatureTable->addCell(10000, ['border' => 0]);
    //     $signatureCell->addText("28 Oktober 2024", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
    //     $signatureCell->addText("Direktur,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
    //     $signatureCell->addTextBreak(3);
    //     $signatureCell->addText("Dr. Kurnia Ekasari, SE., M.M., Ak.", ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
    //     $signatureCell->addText("NIP. 196602141990032002", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

    //     // Save the document
    //     $fileName = 'Surat_Tugas_' . $kegiatan->nama_kegiatan . '.docx';
    //     $filePath = storage_path('app/public/' . $fileName);
    //     $phpWord->save($filePath, 'Word2007');

    //     return response()->download($filePath)->deleteFileAfterSend(true);
    // }
}