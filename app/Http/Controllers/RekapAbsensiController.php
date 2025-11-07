<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Absensi;
use Carbon\Carbon;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $tahunAjar = $request->get('tahunAjar');
        $tanggal = $request->get('tanggal');
        $rekapAbsensi = collect();

        // 🔹 Ambil daftar kelas & tahun ajar untuk filter dropdown
        $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        // 🔹 Jalankan query hanya jika semua filter terisi
        if ($kelas && $jurusan && $tahunAjar && $tanggal) {
            $rekapAbsensi = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
                ->select(
                    'absensi.tanggal',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa',
                    'absensi.tahunAjar'
                )
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
                    SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alpha
                ')
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->where('absensi.tahunAjar', $tahunAjar)
                ->whereDate('absensi.tanggal', $tanggal)
                ->groupBy('absensi.tanggal', 'siswa.kelas_siswa', 'siswa.jurusan_siswa', 'absensi.tahunAjar')
                ->orderByDesc('absensi.tanggal')
                ->get();
        }

        return view('rekapabsensi', compact(
            'rekapAbsensi',
            'kelas',
            'jurusan',
            'tahunAjar',
            'tanggal',
            'daftar_kelas',
            'daftar_tahunAjar'
        ));
    }

    // 🔹 Update data absensi langsung dari modal
    public function update(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'NIS' => 'required|array',
            'status' => 'required|array',
            'kelas' => 'required',
            'tahunAjar' => 'required'
        ]);

        foreach ($request->NIS as $i => $nis) {
            Absensi::updateOrCreate(
                [
                    'NIS' => $nis,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'status' => $request->status[$i],
                    'keterangan' => $request->keterangan[$i] ?? null,
                    'tahunAjar' => $request->tahunAjar,
                ]
            );
        }

        return back()->with('success', '✅ Data absensi berhasil diperbarui!');
    }
}
