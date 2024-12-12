<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\DaftarPelatihanModel;
use App\Models\PengajuanPelatihanModel;
use App\Models\PeriodeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF facade
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Import Spreadsheet class
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Import Xlsx writer class

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
                if ($pengajuan->status === 'Disetujui') {
                    $url = url('/pengajuan_pelatihan/' . $pengajuan->id_pengajuan . '/export_word');
                    $btn = '<button onclick="window.location.href=\'' . $url . '\'" class="btn btn-primary btn-sm">
                                <i class="fa fa-download"></i> Download
                            </button>';
                } else {
                    $btn = '<button class="btn btn-secondary btn-sm" disabled>
                                <i class="fa fa-download"></i> Download
                            </button>';
                }
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

        // Mengambil semua pelatihan
        $allPelatihan = DaftarPelatihanModel::all();

        // Mengambil ID pelatihan yang sudah pernah diajukan
        $alreadySubmittedPelatihanIds = PengajuanPelatihanModel::pluck('id_pelatihan')->toArray();

        // Filter pelatihan yang belum pernah diajukan
        $daftarPelatihan = $allPelatihan->filter(function ($pelatihan) use ($alreadySubmittedPelatihanIds) {
            return !in_array($pelatihan->id_pelatihan, $alreadySubmittedPelatihanIds);
        });

        $periode = PeriodeModel::all(); // Mengambil semua periode pelatihan

        return view('pengajuan_pelatihan.create_ajax', compact('pengguna', 'daftarPelatihan', 'periode'));
    }

    public function store_ajax(Request $request)
    {
        $rules = [
            // 'id_pengguna' => 'required|exists:pengguna,id_pengguna',
            'id_pelatihan' => 'required|exists:daftar_pelatihan,id_pelatihan',
            'tanggal_pengajuan' => 'required|date',
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

        // Tetapkan status default
        $data['status'] = 'Menunggu';

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
        $pengajuan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan', 'daftarPelatihan.vendorPelatihan'])->find($id);

        // Format the start and end dates in "d F Y" format (day month year)
        $startDate = Carbon::parse($pengajuan->daftarPelatihan->tanggal_mulai)->locale('id')->translatedFormat('d F Y');
        $endDate = Carbon::parse($pengajuan->daftarPelatihan->tanggal_selesai)->locale('id')->translatedFormat('d F Y');

        if (!$pengajuan) {
            return response()->json(['error' => 'Data pengajuan tidak ditemukan'], 404);
        }

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Tambahkan section ke dokumen
        $section = $phpWord->addSection();

        // Header Section
        $headerTable = $section->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
        ]);

        // Add Row for Header
        $headerTable->addRow();

        // Logo Cell (Align Left)
        $logoCell = $headerTable->addCell(2000, [
            'valign' => 'center',
            'marginTop' => 0,
            'marginBottom' => 0,
            'align' => 'left' // Make sure the cell content is aligned left
        ]);

        $logoCell->addImage(
            asset('polinema-bw.png'),
            [
                'width' => 80,  // Set width if needed
                'height' => 80, // Set height if needed
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT // Image alignment left
            ]
        );

        // Text Header Cell
        $headerCell = $headerTable->addCell(8000, [
            'valign' => 'center',
            'marginTop' => 0,
            'marginBottom' => 0
        ]);
        $textRun = $headerCell->addTextRun([
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0
        ]);

        // Add Text to Header
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

        // $section->addText('', [], ['spaceAfter' => 5]); // Menambahkan spasi sangat kecil sebelum garis

        // Add Bottom Line
        $lineStyle = [
            'weight' => 2,
            'width' => 440,
            'height' => 0,
            'color' => '#000'
        ];
        $section->addLine($lineStyle);

        // $section->addTextBreak();

        // Tambahkan tabel dengan dua kolom
        $table = $section->addTable();

        // Baris pertama tabel
        $table->addRow();

        // Kolom pertama (No)
        $table->addCell(7500)->addText("No\t:   /PL2.TI/KP/20  ", ['size' => 12]);

        // Mendapatkan tanggal sekarang dan mengubah formatnya dengan nama bulan dalam bahasa Indonesia
        $formattedDate = Carbon::now()->locale('id')->translatedFormat('d F Y');

        // Kolom kedua (Tanggal) dengan pengaturan alignment ke kanan dan ukuran yang cukup besar untuk memastikan posisi paling kanan
        $table->addCell(2500, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT])
            ->addText($formattedDate, ['size' => 12]);

        // Membuat Perihal dan teksnya dalam satu baris
        $textRun = $section->addTextRun();
        $textRun->addText("Perihal\t: ", ['size' => 12]);
        $textRun->addText("Permohonan Pembuatan Surat Tugas", ['size' => 12, 'bold' => true]);

        $section->addTextBreak(1);

        // Penerima Surat
        $section->addText("Yth.\t Pembantu Direktur I", ['size' => 12]);
        $section->addText("\t Politeknik Negeri Malang", ['size' => 12]);
        $section->addText("\t di Tempat", ['size' => 12]);
        $section->addTextBreak();


        // Isi Surat
        // Menambahkan teks dengan alignment rata kiri-kanan
        $textRun = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]);
        // Add the formatted text to the document
        $textRun->addText(
            "\t Sehubungan dengan pelaksanaan kegiatan pelatihan " .
                $pengajuan->daftarPelatihan->nama_pelatihan .
                " yang diselenggarakan oleh " . $pengajuan->daftarPelatihan->vendorPelatihan->nama . " pada tanggal " .
                $startDate . " hingga " . $endDate . " di " .
                $pengajuan->daftarPelatihan->lokasi . ". Dengan ini kami mohon dapat diterbitkan surat tugas kepada dosen di bawah ini untuk melaksanakan kegiatan yang dimaksud. Adapun nama-nama dosen dan atau tendik tersebut terlampir.",
            ['size' => 12]
        );

        $section->addTextBreak();
        $section->addText("Atas kerjasama dan perhatiannya, kami ucapkan terima kasih.", ['size' => 12]);
        $section->addTextBreak(3);

        // Add signature table with two cells
        $signatureTable = $section->addTable();
        $signatureTable->addRow();

        // First cell (empty or you can add something here)
        $signatureTable->addCell(5000, ['border' => 0]);

        // Second cell (for the signature)
        $signatureCell = $signatureTable->addCell(5000, ['border' => 0]);

        // Add "Ketua Jurusan" text with left alignment
        $signatureCell->addText("Ketua Jurusan,", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);

        // Add line breaks for spacing
        $signatureCell->addTextBreak(3);

        // Add the name with bold styling and left alignment
        $signatureCell->addText("Dr. Eng. Rosa Andrie Asmara, ST, MT", ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);

        // Add NIP with left alignment
        $signatureCell->addText("NIP. 198010102005011001", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);

        // Add Page Break
        $section->addPageBreak();

        // Header Section
        $headerTable = $section->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
        ]);

        // Add Row for Header
        $headerTable->addRow();

        // Logo Cell (Align Left)
        $logoCell = $headerTable->addCell(2000, [
            'valign' => 'center',
            'marginTop' => 0,
            'marginBottom' => 0,
            'align' => 'left' // Make sure the cell content is aligned left
        ]);

        $logoCell->addImage(
            asset('polinema-bw.png'),
            [
                'width' => 80,  // Set width if needed
                'height' => 80, // Set height if needed
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT // Image alignment left
            ]
        );

        // Text Header Cell
        $headerCell = $headerTable->addCell(8000, [
            'valign' => 'center',
            'marginTop' => 0,
            'marginBottom' => 0
        ]);
        $textRun = $headerCell->addTextRun([
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0
        ]);

        // Add Text to Header
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

        // $section->addText('', [], ['spaceAfter' => 5]); // Menambahkan spasi sangat kecil sebelum garis

        // Add Bottom Line
        $lineStyle = [
            'weight' => 2,
            'width' => 440,
            'height' => 0,
            'color' => '#000'
        ];
        $section->addLine($lineStyle);

        $section->addText("DAFTAR PESERTA", ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addText("Kegiatan Pelatihan " . $pengajuan->daftarPelatihan->nama_pelatihan, ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addText($startDate . ' sampai ' . $endDate, ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Tabel Data
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'alignment' => 'center'];
        $table = $section->addTable($tableStyle);

        // Header Tabel
        $table->addRow();
        $table->addCell(500, ['bgColor' => 'cccccc'])->addText('No', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(3000, ['bgColor' => 'cccccc'])->addText('Nama', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('NIP', ['bold' => true], ['alignment' => 'center']);
        $table->addCell(2000, ['bgColor' => 'cccccc'])->addText('Status', ['bold' => true], ['alignment' => 'center']);

        // Isi Tabel
        $pesertaIds = json_decode($pengajuan->id_peserta);
        $daftarPeserta = [];
        $nipPeserta = [];
        $jenisPeserta = []; // Array untuk menyimpan jenis peserta
        if (!empty($pesertaIds)) {
            foreach ($pesertaIds as $index => $id) {
                $peserta = Pengguna::find($id);
                $nama = $peserta->dosen->nama_lengkap ?? $peserta->tendik->nama_lengkap ?? 'Tidak Dikenal';

                // Get NIP based on whether the participant is 'dosen' or 'tendik'
                $nip = $peserta->dosen->nip ?? $peserta->tendik->nip ?? '-';
                $nipPeserta[] = $nip; // Store the NIP for later use

                // Determine participant type (Dosen/Tendik)
                $jenisPeserta[] = $peserta->dosen ? 'Dosen' : ($peserta->tendik ? 'Tendik' : 'Tidak Dikenal');

                // Add the participant name without numbering
                $daftarPeserta[] = $nama;
            }
        }

        // Add the rows with the participant's name, NIP, and type
        foreach ($pesertaIds as $index => $id) {
            $table->addRow();

            // Add No (Index)
            $table->addCell(1000)->addText($index + 1, [], ['alignment' => 'center']);

            // Add Daftar Peserta (name)
            $cellPeserta = $table->addCell(4000);
            $cellPeserta->addText("  " . $daftarPeserta[$index], ['size' => 10]);

            // Add NIP (from $nipPeserta array)
            $table->addCell(3000)->addText("  " . $nipPeserta[$index], ['size' => 10]);

            // Add Status (from $jenisPeserta array)
            $table->addCell(1500)->addText("  " . $jenisPeserta[$index], ['size' => 10]);
        }

        // Simpan Dokumen
        $fileName = 'Draft_Surat_Tugas_' . $pengajuan->id_pengajuan . "_" . now()->format('Ymd') . '.docx';
        $tempFile = storage_path('app/public/' . $fileName);
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
    public function export_pdf()
    {
        $pengajuan_pelatihan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan'])
            ->select('id_pengajuan', 'id_pengguna', 'id_pelatihan', 'tanggal_pengajuan', 'status', 'catatan', 'id_peserta')
            ->orderBy('tanggal_pengajuan', 'asc')
            ->get();

        $pdf = Pdf::loadView('pengajuan_pelatihan.export_pdf', compact('pengajuan_pelatihan'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Data_pengajuan_pelatihan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function export_excel()
    {
        // Fetch the data from the correct model
        $pengajuan_pelatihan = PengajuanPelatihanModel::with(['pengguna', 'daftarPelatihan'])
            ->select('id_pengajuan', 'id_pengguna', 'id_pelatihan', 'tanggal_pengajuan', 'status', 'catatan', 'id_peserta')
            ->orderBy('tanggal_pengajuan', 'asc') // Change to a valid column
            ->get();

        // Create a new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header columns
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Pelatihan');
        $sheet->setCellValue('C1', 'Tanggal Pengajuan');
        $sheet->setCellValue('D1', 'Status');
        $sheet->setCellValue('E1', 'Catatan');
        $sheet->setCellValue('F1', 'ID Peserta');
        $sheet->setCellValue('G1', 'Nama Pelatihan');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Fill data
        $row = 2;
        foreach ($pengajuan_pelatihan as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->id_pelatihan);
            $sheet->setCellValue('C' . $row, $data->tanggal_pengajuan);
            $sheet->setCellValue('D' . $row, $data->status);
            $sheet->setCellValue('E' . $row, $data->catatan);
            $sheet->setCellValue('F' . $row, $data->id_peserta);
            $sheet->setCellValue('G' . $row, $data->daftarPelatihan->nama_pelatihan ?? ''); // Assuming you want the name of the training
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Save Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data_Pengajuan_Pelatihan_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
