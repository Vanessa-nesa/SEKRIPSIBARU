<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class WaliController extends Controller
{
    /**
     * 🔹 Menampilkan halaman rekap absensi (Wali Kelas)
     */
    public function rekapAbsensi(Request $request)
    {
        $kelas = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $tahunAjar = $request->get('tahunAjar');
        $tanggal = $request->get('tanggal');

        $rekapAbsensi = collect();
        $detailAbsensi = collect();

        // 🔸 Data dropdown untuk filter
        $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderByDesc('tahunAjar')->pluck('tahunAjar');

        // 🔹 Jalankan query hanya jika semua filter diisi
        if ($kelas && $jurusan && $tahunAjar && $tanggal) {

            // 📊 Rekap total absensi per hari
            $rekapAbsensi = DB::table('absensi')
                ->join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
                ->select(
                    DB::raw('COUNT(absensi.id_absensi) as total'),
                    DB::raw('SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir'),
                    DB::raw('SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit'),
                    DB::raw('SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin'),
                    DB::raw('SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alpha'),
                    'absensi.tanggal',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa',
                    'absensi.tahunAjar'
                )
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->where('absensi.tahunAjar', $tahunAjar)
                ->where('absensi.tanggal', $tanggal) // ✅ varchar, bukan date
                ->groupBy('absensi.tanggal', 'siswa.kelas_siswa', 'siswa.jurusan_siswa', 'absensi.tahunAjar')
                ->orderBy('absensi.tanggal', 'desc')
                ->get();

// 👥 Detail daftar siswa per status absensi
$detailAbsensi = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
    ->select(
        'absensi.id_absensi',
        'absensi.tanggal',
        'absensi.status',
        'absensi.keterangan',
        'siswa.nama_siswa',
        'siswa.kelas_siswa',
        'siswa.jurusan_siswa'
    )
    ->where('siswa.kelas_siswa', $kelas)
    ->where('siswa.jurusan_siswa', $jurusan)
    ->where('absensi.tahunAjar', $tahunAjar)
    ->where('absensi.tanggal', $tanggal)
    ->orderBy('siswa.nama_siswa')
    ->get();   // ❗ TANPA groupBy !!!
        }

        return view('wali.rekapabsensi', compact(
            'kelas',
            'jurusan',
            'tahunAjar',
            'tanggal',
            'daftar_kelas',
            'daftar_tahunAjar',
            'rekapAbsensi',
            'detailAbsensi'
        ));

    }

    /**
     * ✏️ Edit Absensi (Opsional)
     */
    public function editAbsensi($id)
    {
        $absensi = Absensi::join('siswa', 'siswa.NIS', '=', 'absensi.NIS')
    ->select('absensi.*', 'siswa.nama_siswa', 'siswa.kelas_siswa', 'siswa.jurusan_siswa')
    ->where('id_absensi', $id)
    ->firstOrFail();

        return view('wali.editabsensi', compact('absensi'));
    }

    /**
     * 💾 Update Absensi
     */
    public function updateAbsensi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('wali.rekapabsensi')
            ->with('success', '✅ Data absensi berhasil diperbarui!');
    }

    /**
     * 🗑️ Hapus Absensi
     */
    public function deleteAbsensi($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('wali.rekapabsensi')
            ->with('success', '🗑️ Data absensi berhasil dihapus!');
    }
}
