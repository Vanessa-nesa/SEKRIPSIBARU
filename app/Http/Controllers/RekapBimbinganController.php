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
        $kelas = $request->input('kelas');
        $jurusan = $request->input('jurusan');
        $tanggal_riwayat = $request->input('tanggal_riwayat');
        $tahunAjar = $request->input('tahunAjar');

        // 🔹 Ambil daftar kelas dari tabel Kelas
        $daftar_kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        // 🔹 Buat daftar tahun ajar dinamis (±1 dari tahun sekarang)
        $tahunSekarang = date('Y');
        $daftar_tahunAjar = [
            ($tahunSekarang - 1) . '/' . $tahunSekarang,
            "$tahunSekarang/" . ($tahunSekarang + 1),
            ($tahunSekarang + 1) . '/' . ($tahunSekarang + 2)
        ];

        // 🔹 Ambil semua tanggal unik dari tabel bimbingan (untuk referensi)
        $daftar_tanggal = DB::table('bimbingan')
            ->select('tanggal')
            ->distinct()
            ->orderByDesc('tanggal')
            ->pluck('tanggal');

        // 🔹 Variabel default kosong
        $rekapBimbingan = collect();
        $riwayat = collect();

        // ============================================
        // 🧑‍🏫 GURU BK — bisa filter & lihat detail
        // ============================================
        if ($role === 'Guru BK') {
            if ($kelas && $jurusan && $tahunAjar) {
                // Rekap umum per tanggal
                $rekapBimbingan = DB::table('bimbingan')
                    ->join('siswa', 'bimbingan.NIS', '=', 'siswa.NIS')
                    ->select(
                        'bimbingan.tanggal',
                        'siswa.kelas_siswa as kelas',
                        'siswa.jurusan_siswa as jurusan',
                        'bimbingan.tahunAjar',
                        DB::raw('COUNT(DISTINCT bimbingan.NIS) as total_siswa'),
                        DB::raw('COUNT(bimbingan.id_bimbingan) as total_bimbingan'),
                        DB::raw('GROUP_CONCAT(DISTINCT bimbingan.pelanggaran SEPARATOR ", ") as pelanggaran')
                    )
                    ->where('siswa.kelas_siswa', $kelas)
                    ->where('siswa.jurusan_siswa', $jurusan)
                    ->where('bimbingan.tahunAjar', $tahunAjar)
                    ->groupBy('bimbingan.tanggal', 'siswa.kelas_siswa', 'siswa.jurusan_siswa', 'bimbingan.tahunAjar')
                    ->orderByDesc('bimbingan.tanggal')
                    ->get();

                // 🔹 Jika tanggal dipilih → tampilkan riwayat detail siswa
                if ($tanggal_riwayat) {
                    $riwayat = DB::select("
                        SELECT 
                            bimbingan.tanggal,
                            siswa.nama_siswa,
                            siswa.kelas_siswa as kelas,
                            siswa.jurusan_siswa as jurusan,
                            bimbingan.pelanggaran,
                            bimbingan.bimbingan_ke,
                            bimbingan.notes,
                            bimbingan.tahunAjar
                        FROM bimbingan
                        INNER JOIN siswa ON bimbingan.NIS = siswa.NIS
                        WHERE siswa.kelas_siswa = ? 
                          AND siswa.jurusan_siswa = ? 
                          AND bimbingan.tahunAjar = ?
                          AND bimbingan.tanggal = ?
                        ORDER BY bimbingan.bimbingan_ke ASC
                    ", [$kelas, $jurusan, $tahunAjar, $tanggal_riwayat]);
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

        // ============================================
        // 🧑‍💼 KEPALA SEKOLAH / WAKIL — hanya lihat rekap
        // ============================================
        elseif (in_array($role, ['Kepala Sekolah', 'Wakil Kepala Sekolah'])) {
            $rekapBimbingan = DB::table('bimbingan')
                ->join('siswa', 'bimbingan.NIS', '=', 'siswa.NIS')
                ->select(
                    'bimbingan.tanggal',
                    'siswa.nama_siswa',
                    'siswa.kelas_siswa as kelas',
                    'siswa.jurusan_siswa as jurusan',
                    'bimbingan.tahunAjar',
                    'bimbingan.bimbingan_ke',
                    'bimbingan.pelanggaran',
                    'bimbingan.notes'
                )
                ->when($kelas, fn($q) => $q->where('siswa.kelas_siswa', $kelas))
                ->when($jurusan, fn($q) => $q->where('siswa.jurusan_siswa', $jurusan))
                ->when($tahunAjar, fn($q) => $q->where('bimbingan.tahunAjar', $tahunAjar))
                // Gunakan raw karena kolom tanggal bertipe VARCHAR
                ->when($tanggal_riwayat, fn($q) => $q->whereRaw('bimbingan.tanggal = ?', [$tanggal_riwayat]))
                ->orderByDesc('bimbingan.tanggal')
                ->get();
                
dd($kelas, $jurusan, $tahunAjar, $tanggal_riwayat);

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

        // ============================================
        // 🚫 Role tidak diizinkan
        // ============================================
        return redirect()->route('login')->with('error', 'Akses ditolak!');
    }
}
