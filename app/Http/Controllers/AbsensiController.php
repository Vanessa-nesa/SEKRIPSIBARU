<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $tanggal = $request->get('tanggal');
        $tahunAjar = $request->get('tahunAjar');
        $siswa = collect();
        $absensi = collect();

        // 🔹 Ambil semua kelas unik
        $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();

        // 🔹 Ambil semua tahun ajar unik (default)
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        // 🔹 Filter tahun ajar berdasarkan kelas yang dipilih
        if ($kelas) {
            $daftar_tahunAjar = Kelas::where('nama_kelas', $kelas)
                ->select('tahunAjar')
                ->distinct()
                ->orderBy('tahunAjar', 'desc')
                ->pluck('tahunAjar');
        }

        // 🔹 Jika kelas, jurusan, dan tahun ajar dipilih → ambil data siswa
        if ($kelas && $jurusan && $tahunAjar) {
            $siswa = Siswa::where('kelas_siswa', $kelas)
                ->where('jurusan_siswa', $jurusan)
                ->where('tahunAjar', $tahunAjar)
                ->get();

            // 🔹 Jika tanggal dipilih → ambil data absensi untuk tanggal tsb
            if ($tanggal) {
                $absensi = Absensi::where('tanggal', $tanggal)
                    ->where('tahunAjar', $tahunAjar)
                    ->whereIn('NIS', $siswa->pluck('NIS'))
                    ->get();
            }
        }

        return view('absensi', compact(
            'kelas', 'jurusan', 'tanggal', 'tahunAjar',
            'siswa', 'absensi', 'daftar_kelas', 'daftar_tahunAjar'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'NIS' => 'required|array',
            'status' => 'required|array',
            'kelas' => 'required',
            'tahunAjar' => 'required',
        ]);

        $idUser = Auth::id() ?? 1;
        $tahunAjar = $request->tahunAjar;

        foreach ($request->NIS as $index => $nis) {
            Absensi::updateOrCreate(
                [
                    'NIS' => $nis,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'id_user' => $idUser,
                    'status' => $request->status[$index],
                    'keterangan' => $request->keterangan[$index] ?? null,
                    'tahunAjar' => $tahunAjar,
                ]
            );
        }

        return back()->with('success', '✅ Data absensi berhasil disimpan!');
    }

    // ======================================================
    // 🔹 FITUR REKAP ABSENSI (Filter Kelas, Jurusan, Tahun Ajar)
    // ======================================================
    public function rekap(Request $request)
    {
        $kelas = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $tahunAjar = $request->get('tahunAjar');
        $rekapAbsensi = collect();

        // 🔹 Ambil daftar kelas dari tabel kelas
        $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();

        // 🔹 Ambil daftar tahun ajar berdasarkan kelas yang dipilih
        $daftar_tahunAjar = collect();
        if ($kelas) {
            $daftar_tahunAjar = Kelas::where('nama_kelas', $kelas)
                ->select('tahunAjar')
                ->distinct()
                ->orderBy('tahunAjar', 'desc')
                ->pluck('tahunAjar');
        }

        // 🔹 Jalankan rekap jika semua filter terisi
        if ($kelas && $jurusan && $tahunAjar) {
            $rekapAbsensi = DB::table('absensi')
                ->join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
                ->select(
                    'absensi.tanggal',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa',
                    'absensi.tahunAjar',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir'),
                    DB::raw('SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit'),
                    DB::raw('SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin'),
                    DB::raw('SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alpa')
                )
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->where('absensi.tahunAjar', $tahunAjar)
                ->groupBy('absensi.tanggal', 'siswa.kelas_siswa', 'siswa.jurusan_siswa', 'absensi.tahunAjar')
                ->orderByDesc('absensi.tanggal')
                ->get();
        }

        return view('rekapabsensi', compact(
            'kelas', 'jurusan', 'tahunAjar',
            'rekapAbsensi', 'daftar_kelas', 'daftar_tahunAjar'
        ));
    }
}
