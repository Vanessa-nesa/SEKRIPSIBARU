<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;

class GuruBKController extends Controller
{
    public function index(Request $request)
    {
        $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        $kelas = $request->kelas;
        $jurusan = $request->jurusan;
        $tahunAjar = $request->tahunAjar;
        $tanggal = $request->tanggal;
        $mode = $request->mode;

        $siswa = collect();
        $rekapAbsensi = collect();

        // =============================
        // MODE INPUT BIMBINGAN
        // =============================
        if ($mode === 'bimbingan' && $kelas && $jurusan && $tahunAjar) {
            $siswa = Siswa::where('kelas_siswa', $kelas)
                ->where('jurusan_siswa', $jurusan)
                ->orderBy('nama_siswa')
                ->get();
        }

        // =============================
        // MODE REKAP ABSENSI (BK)
        // =============================
        if ($mode === 'rekapbk' && $kelas && $jurusan && $tahunAjar && $tanggal) {

            $rekapAbsensi = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
                ->select(
                    'absensi.tanggal',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status="Hadir" THEN 1 ELSE 0 END) AS hadir'),
                    DB::raw('SUM(CASE WHEN status="Sakit" THEN 1 ELSE 0 END) AS sakit'),
                    DB::raw('SUM(CASE WHEN status="Izin" THEN 1 ELSE 0 END) AS izin'),
                    DB::raw('SUM(CASE WHEN status="Alpha" THEN 1 ELSE 0 END) AS alpha')
                )
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->where('absensi.tahunAjar', $tahunAjar)
                ->whereDate('absensi.tanggal', $tanggal)
                ->groupBy('absensi.tanggal', 'siswa.kelas_siswa', 'siswa.jurusan_siswa')
                ->get();
        // handle rekap pelanggaran {
if ($mode === 'rekappel' && $kelas && $jurusan && $tahunAjar) {
    $rekapPelanggaran = DB::table('pelanggaran')
        ->join('siswa', 'pelanggaran.NIS', '=', 'siswa.NIS')
        ->where('kelas_siswa', $kelas)
        ->where('jurusan_siswa', $jurusan)
        ->where('pelanggaran.tahunAjar', $tahunAjar)
        ->orderByDesc('pelanggaran.tanggal')
        ->get();
}

        }

        return view('bimbingan', compact(
            'daftar_kelas',
            'daftar_tahunAjar',
            'kelas',
            'jurusan',
            'tahunAjar',
            'tanggal',
            'siswa',
            'rekapAbsensi',
            'mode'
        ));
    }
}
