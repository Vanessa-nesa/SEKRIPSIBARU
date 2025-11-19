<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Siswa;
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
        $detailAbsensi = collect();

        // Dropdown data
        $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        if ($kelas && $jurusan && $tahunAjar && $tanggal) {

            // Rekap total
            $rekapAbsensi = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
    ->select(
        'absensi.tanggal',
        'siswa.kelas_siswa',
        'siswa.jurusan_siswa',
        'absensi.tahunAjar',
        'absensi.id_absensi',
        'siswa.nama_siswa'   // 🔥 WAJIB supaya tidak error!
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
                ->whereDate('absensi.tanggal', $tanggal)
                ->groupBy(
                    'absensi.tanggal',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa',
                    'absensi.tahunAjar',
                    'absensi.id_absensi'
                )
                ->orderByDesc('absensi.tanggal')
                ->get();

            // 👥 Detail per siswa (SUPPORT EDIT & DELETE)
            $detailAbsensi = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
                ->select(
                    'absensi.id_absensi',
                    'absensi.NIS',
                    'siswa.nama_siswa',
                    'siswa.kelas_siswa',
                    'siswa.jurusan_siswa',
                    'absensi.status',
                    'absensi.keterangan'
                )
                ->whereRaw('LOWER(siswa.kelas_siswa) = ?', [strtolower($kelas)])
                ->whereRaw('LOWER(siswa.jurusan_siswa) = ?', [strtolower($jurusan)])
                ->where('absensi.tahunAjar', $tahunAjar)
                ->whereDate('absensi.tanggal', $tanggal)
                ->orderBy('siswa.nama_siswa', 'asc')
                ->get();
        }

        return view('wali.rekapabsensi', compact(
            'rekapAbsensi',
            'detailAbsensi',
            'kelas',
            'jurusan',
            'tahunAjar',
            'tanggal',
            'daftar_kelas',
            'daftar_tahunAjar'
        ));
    }

    // =====================================================
    // 🔹 EDIT ABSENSI FORM
    // =====================================================
    public function edit($id)
    {
        $data = Absensi::join('siswa','siswa.NIS','=','absensi.NIS')
            ->select('absensi.*','siswa.nama_siswa','siswa.kelas_siswa','siswa.jurusan_siswa')
            ->where('id_absensi',$id)
            ->first();

        return view('wali.absensi_edit', compact('data'));
    }

    // =====================================================
    // 🔹 UPDATE ABSENSI
    // =====================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        Absensi::where('id_absensi', $id)->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('wali.rekapabsensi', [
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'tahunAjar' => $request->tahunAjar,
            'tanggal' => $request->tanggal
        ])->with('success','Data absensi berhasil diperbarui!');
    }

    // =====================================================
    // 🔹 DELETE ABSENSI
    // =====================================================
    public function delete($id)
    {
        Absensi::where('id_absensi', $id)->delete();

        return back()->with('success','Data absensi berhasil dihapus!');
    }
}
