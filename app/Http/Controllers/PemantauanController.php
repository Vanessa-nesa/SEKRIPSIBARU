<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Absensi;
use App\Models\Pelanggaran;
use App\Models\Bimbingan;
use App\Models\Prestasi;

class PemantauanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data dari filter dropdown
        $kelas = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $tahunAjar = $request->get('tahunAjar');

        // 🔹 Ambil daftar kelas dan jurusan dari tabel siswa
        $daftar_kelas = DB::table('siswa')
            ->select('kelas_siswa')
            ->distinct()
            ->orderBy('kelas_siswa', 'asc')
            ->pluck('kelas_siswa');

        $daftar_jurusan = DB::table('siswa')
            ->select('jurusan_siswa')
            ->distinct()
            ->orderBy('jurusan_siswa', 'asc')
            ->pluck('jurusan_siswa');

        // 🔹 Ambil daftar tahun ajar dari tabel absensi
        $daftar_tahunAjar = DB::table('absensi')
            ->select('tahunAjar')
            ->distinct()
            ->orderBy('tahunAjar', 'desc')
            ->pluck('tahunAjar');

        // 🔹 Ambil nama user dari session
        $namaUser = session('nama') ?? 'Pengguna';

        // 🔹 Siapkan variabel kosong
        $absensi = collect();
        $pelanggaran = collect();
        $bimbingan = collect();
        $prestasi = collect();

        // Jalankan query jika semua filter terisi
        if ($kelas && $jurusan && $tahunAjar) {

            // ==========================
            // 📘 DATA ABSENSI
            // ==========================
            $absensi = DB::table('absensi')
                ->join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->where('absensi.tahunAjar', $tahunAjar)
                ->select(
                    'absensi.*',
                    'siswa.nama_siswa',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa'
                )
                ->get();

            // ==========================
            // ⚠️ DATA PELANGGARAN
            // ==========================
            $pelanggaran = DB::table('pelanggaran')
                ->join('siswa', 'pelanggaran.NIS', '=', 'siswa.NIS')
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->select(
                    'pelanggaran.*',
                    'siswa.nama_siswa',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa'
                )
                ->get();

            // ==========================
            // 💬 DATA BIMBINGAN
            // ==========================
            $bimbingan = DB::table('bimbingan')
                ->join('siswa', 'bimbingan.NIS', '=', 'siswa.NIS')
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->select(
                    'bimbingan.*',
                    'siswa.nama_siswa',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa'
                )
                ->get();

            // ==========================
            // 🏅 DATA PRESTASI
            // ==========================
            $prestasi = DB::table('prestasi')
                ->join('siswa', 'prestasi.NIS', '=', 'siswa.NIS')
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->where('prestasi.tahunAjar', $tahunAjar)
                ->select(
                    'prestasi.*',
                    'siswa.nama_siswa',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa'
                )
                ->get();
        }

        // Kirim data ke view
        return view('pemantauan', compact(
            'daftar_kelas',
            'daftar_jurusan',
            'daftar_tahunAjar',
            'kelas',
            'jurusan',
            'tahunAjar',
            'absensi',
            'pelanggaran',
            'bimbingan',
            'prestasi',
            'namaUser'
        ));
    }
}
