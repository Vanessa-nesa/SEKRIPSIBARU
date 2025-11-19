<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;

class RekapBimbinganController extends Controller
{
    public function index(Request $request)
    {
        $role = session('role');

        $kelas = $request->kelas;
        $jurusan = $request->jurusan;
        $tahunAjar = $request->tahunAjar;
        $tanggal_riwayat = $request->tanggal_riwayat;

        // ============================
        // LIST DROPDOWN
        // ============================

        $daftar_kelas = Kelas::select('nama_kelas')
            ->distinct()
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $daftar_tahunAjar = Kelas::select('tahunAjar')
            ->distinct()
            ->orderByDesc('tahunAjar')
            ->pluck('tahunAjar');

        $daftar_tanggal = DB::table('bimbingan')
            ->select('tanggal')
            ->distinct()
            ->orderByDesc('tanggal')
            ->pluck('tanggal');

        $rekapBimbingan = collect();
        $riwayat = collect();



        // ==========================================================
        // 👨‍🏫 ROLE = GURU BK
        // ==========================================================
        if ($role === 'Guru BK') {

            if ($kelas && $jurusan && $tahunAjar) {

                // ===================== REKAP HARIAN =====================
                $rekapBimbingan = DB::table('bimbingan')
                    ->join('siswa', 'bimbingan.NIS', '=', 'siswa.NIS')
                    ->select(
                        'bimbingan.tanggal',
                        'siswa.kelas_siswa as kelas',
                        'bimbingan.tahunAjar',
                        DB::raw('COUNT(DISTINCT bimbingan.NIS) as total_siswa'),
                        DB::raw('COUNT(bimbingan.id_bimbingan) as total_bimbingan'),
                        DB::raw('GROUP_CONCAT(DISTINCT bimbingan.pelanggaran SEPARATOR ", ") as pelanggaran')
                    )
                    ->where('siswa.kelas_siswa', $kelas)
                    ->where('siswa.jurusan_siswa', $jurusan)
                    ->where('bimbingan.tahunAjar', $tahunAjar)
                    ->groupBy('bimbingan.tanggal', 'siswa.kelas_siswa', 'bimbingan.tahunAjar')
                    ->orderByDesc('bimbingan.tanggal')
                    ->get();

                // ===================== DETAIL RIWAYAT =====================
                if ($tanggal_riwayat) {
                    $riwayat = DB::table('bimbingan')
                        ->join('siswa', 'bimbingan.NIS', '=', 'siswa.NIS')
                        ->select(
                            'bimbingan.id_bimbingan',        // WAJIB ADA UNTUK EDIT / HAPUS
                            'bimbingan.tanggal',
                            'siswa.nama_siswa as nama_siswa',
                            'siswa.kelas_siswa as kelas',
                            'bimbingan.pelanggaran',
                            'bimbingan.bimbingan_ke',
                            'bimbingan.notes',
                            'bimbingan.tahunAjar'
                        )
                        ->where('siswa.kelas_siswa', $kelas)
                        ->where('siswa.jurusan_siswa', $jurusan)
                        ->where('bimbingan.tahunAjar', $tahunAjar)
                        ->where('bimbingan.tanggal', $tanggal_riwayat)
                        ->orderBy('bimbingan.bimbingan_ke', 'ASC')
                        ->get();
                }
            }

            return view('rekapbimbingan', compact(
                'kelas',
                'jurusan',
                'tahunAjar',
                'rekapBimbingan',
                'daftar_tanggal',
                'riwayat',
                'tanggal_riwayat',
                'daftar_kelas',
                'daftar_tahunAjar'
            ));
        }



        // ==========================================================
        // 👨‍💼 ROLE = KEPALA SEKOLAH / WAKIL
        // ==========================================================
        if (in_array($role, ['Kepala Sekolah', 'Wakil Kepala Sekolah'])) {

            $rekapBimbingan = DB::table('bimbingan')
                ->join('siswa', 'bimbingan.NIS', '=', 'siswa.NIS')
                ->select(
                    'bimbingan.tanggal',
                    'siswa.nama_siswa as nama_siswa',
                    'siswa.kelas_siswa as kelas',
                    'bimbingan.bimbingan_ke',
                    'bimbingan.notes',
                    'bimbingan.tahunAjar'
                )
                ->when($kelas, fn($q) => $q->where('siswa.kelas_siswa', $kelas))
                ->when($jurusan, fn($q) => $q->where('siswa.jurusan_siswa', $jurusan))
                ->when($tahunAjar, fn($q) => $q->where('bimbingan.tahunAjar', $tahunAjar))
                ->when($tanggal_riwayat, fn($q) => $q->where('bimbingan.tanggal', $tanggal_riwayat))
                ->orderByDesc('bimbingan.tanggal')
                ->get();

            return view('rekapbimbingan', compact(
                'kelas',
                'jurusan',
                'tahunAjar',
                'rekapBimbingan',
                'daftar_tanggal',
                'daftar_kelas',
                'daftar_tahunAjar',
                'tanggal_riwayat'
            ));
        }

        return redirect()->route('login')->with('error', 'Akses ditolak!');
    }
}
