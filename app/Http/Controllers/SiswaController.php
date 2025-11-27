<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index()
    {
        // Ambil semua kelas dari tabel kelas
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        // Ambil semua siswa untuk ditampilkan di tabel bawah
        $data = Siswa::orderBy('kelas_siswa')->orderBy('nama_siswa')->get();

        return view('siswa', compact('kelas', 'data'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string',
            'tahunAjar' => 'required|string',
            'jurusan_siswa' => 'required|string',
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // 🔹 Ambil ID user (login guru/admin)
            $idUser = Auth::id() ?? 1; // fallback 1 jika belum login

            // 🔹 Cek apakah kombinasi kelas & tahun ajar sudah ada
            $kelas = Kelas::where('nama_kelas', $request->nama_kelas)
                ->where('tahunAjar', $request->tahunAjar)
                ->first();

            // 🔹 Kalau belum ada, buat entri baru di tabel kelas
            if (!$kelas) {
                $kelas = Kelas::create([
                    'nama_kelas' => $request->nama_kelas,
                    'tahunAjar' => $request->tahunAjar,
                    'id_user' => $idUser, // 🔥 penting untuk hindari error
                ]);
            }

            // 🔹 Baca file Excel pakai PhpSpreadsheet
            $path = $request->file('file')->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // 🔹 Lewati header (baris pertama)
            foreach (array_slice($sheetData, 1) as $row) {
                $NIS = $row['A'] ?? null;
                $nama = $row['B'] ?? null;

                if (!$NIS || !$nama) continue; // skip baris kosong

                Siswa::updateOrCreate(
                    ['NIS' => $NIS],
                    [
                        'nama_siswa' => $nama,
                        'kelas_siswa' => $request->nama_kelas,
                        'jurusan_siswa' => $request->jurusan_siswa,
                        'tahunAjar' => $request->tahunAjar,
                        'id_kelas' => $kelas->id_kelas,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            return back()->with('success', '✅ Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($NIS)
    {
        Siswa::where('NIS', $NIS)->delete();
        return back()->with('success', '🗑️ Siswa berhasil dihapus!');
    }

    public function edit($NIS)
    {
        $siswa = Siswa::where('NIS', $NIS)->firstOrFail();
        $kelas = Kelas::all();
        return view('siswa_edit', compact('siswa', 'kelas'));
    }
    // =====================================================
// 🔽 DOWNLOAD FORMAT EXCEL UNTUK IMPORT DATA SISWA
// =====================================================
public function downloadFormat()
{
    $filename = "Format_Import_Siswa.xlsx";

    $header = ["NIS", "NAMA"];

    // Buat spreadsheet baru
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Isi header
    $col = 'A';
    foreach ($header as $h) {
        $sheet->setCellValue($col . '1', $h);
        $col++;
    }

    // AUTO WIDEN kolom
    foreach (range('A', 'B') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Simpan file ke storage sementara
    $tempFile = tempnam(sys_get_temp_dir(), 'excel');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($tempFile);

    // Download file dan hapus setelah dikirim
    return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
}

}
