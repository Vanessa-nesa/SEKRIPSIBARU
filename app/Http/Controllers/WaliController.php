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
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');


        $rekapAbsensi = collect();
        $detailAbsensi = collect();

        // 🔸 Data dropdown untuk filter
        $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderByDesc('tahunAjar')->pluck('tahunAjar');

        // 🔹 Jalankan query hanya jika semua filter diisi
       $tanggal_awal = $request->get('tanggal_awal');
$tanggal_akhir = $request->get('tanggal_akhir');

if ($kelas && $jurusan && $tahunAjar && $tanggal_awal && $tanggal_akhir) {

    $rekapAbsensi = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
        ->select(
            'absensi.tanggal',
            'siswa.kelas_siswa',
            'siswa.jurusan_siswa',
            'absensi.tahunAjar',
            'absensi.id_absensi',
    
        )
        ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "Hadir" THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN status = "Sakit" THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN status = "Izin" THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN status = "Alpha" THEN 1 ELSE 0 END) as alpha
        ')
        ->whereRaw('LOWER(siswa.kelas_siswa) = ?', [strtolower($kelas)])
        ->whereRaw('LOWER(siswa.jurusan_siswa) = ?', [strtolower($jurusan)])
        ->where('absensi.tahunAjar', $tahunAjar)
        ->whereBetween('absensi.tanggal', [$tanggal_awal, $tanggal_akhir])
        ->groupBy(
            'absensi.tanggal',
            'siswa.kelas_siswa',
            'siswa.jurusan_siswa',
            'absensi.tahunAjar',
            'absensi.id_absensi'
        )
        ->orderByDesc('absensi.tanggal')
        ->get();

    // DETAIL
    $detailAbsensi = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
        ->select(
            'absensi.id_absensi',
            'absensi.NIS',
            'siswa.nama_siswa',
            'siswa.kelas_siswa',
            'siswa.jurusan_siswa',
            'absensi.status',
            'absensi.keterangan',
            'absensi.tanggal'
        )
        ->whereRaw('LOWER(siswa.kelas_siswa) = ?', [strtolower($kelas)])
        ->whereRaw('LOWER(siswa.jurusan_siswa) = ?', [strtolower($jurusan)])
        ->where('absensi.tahunAjar', $tahunAjar)
        ->whereBetween('absensi.tanggal', [$tanggal_awal, $tanggal_akhir])
        ->orderBy('siswa.nama_siswa', 'asc')
        ->get();
}


        return view('wali.rekapabsensi', compact(
            'kelas',
            'jurusan',
            'tahunAjar',
            'tanggal_awal',
            'tanggal_akhir',
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
