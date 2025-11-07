<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Bimbingan;
use App\Models\Absensi;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BimbinganController extends Controller
{
    public function index(Request $request)
    {
        $kelas = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $tahunAjar = $request->get('tahunAjar');
        $tanggal = $request->get('tanggal', Carbon::now()->toDateString());
        $mode = $request->get('mode', 'bimbingan');
        $tanggal_riwayat = $request->get('tanggal_riwayat');

        // Ambil daftar kelas dan tahun ajar untuk dropdown
        $daftar_kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        // Inisialisasi variabel kosong untuk mencegah undefined
        $siswa = collect();
        $bimbingan = collect();
        $absensi = collect();
        $rekap = collect();
        $rekapBimbingan = collect();
        $riwayat = collect();

        // Ambil daftar tanggal unik bimbingan
        $daftar_tanggal = Bimbingan::select('tanggal')
            ->distinct()
            ->orderByDesc('tanggal')
            ->pluck('tanggal');

        // ======================================================
        // 📊 MODE REKAP ABSENSI BK
        // ======================================================
        if ($mode === 'rekapbk') {
            if ($kelas && $jurusan && $tahunAjar) {
                $rekap = Absensi::join('siswa', 'absensi.NIS', '=', 'siswa.NIS')
                    ->select(
                        'absensi.tanggal',
                        'siswa.kelas_siswa as kelas',
                        'siswa.jurusan_siswa as jurusan',
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
                    ->groupBy('absensi.tanggal', 'siswa.kelas_siswa', 'siswa.jurusan_siswa', 'absensi.tahunAjar')
                    ->orderByDesc('absensi.tanggal')
                    ->get();
            }
        }

        // ======================================================
        // 📋 MODE REKAP BIMBINGAN (tanpa tanggal)
        // ======================================================
        elseif ($mode === 'rekapbimbingan' && $kelas && $jurusan && !$tanggal_riwayat) {
            $rekapBimbingan = DB::table('bimbingan')
                ->join('siswa', 'bimbingan.NIS', '=', 'siswa.NIS')
                ->select(
                    'bimbingan.tanggal',
                    'siswa.kelas_siswa as kelas',
                    'siswa.jurusan_siswa as jurusan',
                    DB::raw('COUNT(DISTINCT bimbingan.NIS) as total_siswa'),
                    DB::raw('COUNT(bimbingan.id_bimbingan) as total_bimbingan'),
                    DB::raw('GROUP_CONCAT(DISTINCT bimbingan.pelanggaran SEPARATOR ", ") as pelanggaran')
                )
                ->where('siswa.kelas_siswa', $kelas)
                ->where('siswa.jurusan_siswa', $jurusan)
                ->when($tahunAjar, function ($q) use ($tahunAjar) {
                    $q->where('bimbingan.tahunAjar', $tahunAjar);
                })
                ->groupBy('bimbingan.tanggal', 'siswa.kelas_siswa', 'siswa.jurusan_siswa')
                ->orderByDesc('bimbingan.tanggal')
                ->get();
        }

        // ======================================================
        // 🗓️ MODE RIWAYAT BIMBINGAN (berdasarkan tanggal)
        // ======================================================
        elseif ($mode === 'rekapbimbingan' && $kelas && $jurusan && $tanggal_riwayat) {
            $riwayat = Bimbingan::with('siswa')
                ->whereDate('tanggal', $tanggal_riwayat)
                ->whereHas('siswa', function ($q) use ($kelas, $jurusan) {
                    $q->where('kelas_siswa', $kelas)
                      ->where('jurusan_siswa', $jurusan);
                })
                ->when($tahunAjar, function ($q) use ($tahunAjar) {
                    $q->where('tahunAjar', $tahunAjar);
                })
                ->orderBy('bimbingan_ke', 'asc')
                ->get();
        }

        // ======================================================
        // ✍️ MODE INPUT BIMBINGAN (default)
        // ======================================================
        elseif ($kelas && $jurusan) {
            $siswa = Siswa::where('kelas_siswa', $kelas)
                ->where('jurusan_siswa', $jurusan)
                ->get();

            $absensi = Absensi::whereDate('tanggal', $tanggal)
                ->whereHas('siswa', function ($q) use ($kelas, $jurusan) {
                    $q->where('kelas_siswa', $kelas)
                      ->where('jurusan_siswa', $jurusan);
                })
                ->get();

            $bimbingan = Bimbingan::with('siswa')
                ->whereHas('siswa', function ($q) use ($kelas, $jurusan) {
                    $q->where('kelas_siswa', $kelas)
                      ->where('jurusan_siswa', $jurusan);
                })
                ->when($tahunAjar, function ($q) use ($tahunAjar) {
                    $q->where('tahunAjar', $tahunAjar);
                })
                ->get();
        }

        // ======================================================
        // 🚀 KIRIM DATA KE VIEW UTAMA
        // ======================================================
        return view('bimbingan', compact(
            'kelas',
            'jurusan',
            'tahunAjar',
            'tanggal',
            'siswa',
            'bimbingan',
            'absensi',
            'rekap',
            'rekapBimbingan',
            'riwayat',
            'daftar_tanggal',
            'tanggal_riwayat',
            'mode',
            'daftar_kelas',
            'daftar_tahunAjar'
        ));
    }

    // ============================================================
    // 🔸 SIMPAN DATA BIMBINGAN
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'data' => 'required|array',
        ]);

        $tanggal = $request->tanggal_bimbingan;
        $tahunAjar = $request->tahunAjar ?? date('Y') . '/' . (date('Y') + 1);
        $count = 0;
        $userId = Auth::id() ?? 1;

        foreach ($request->data as $item) {
            if (empty($item['pelanggaran']) && empty($item['bimbingan_ke']) && empty($item['notes'])) {
                continue;
            }

            $absensi = Absensi::where('NIS', $item['NIS'])
                ->whereDate('tanggal', $tanggal)
                ->first();

            $kehadiran = $absensi->status ?? 'Tidak Diketahui';

            Bimbingan::create([
                'NIS' => $item['NIS'],
                'id_user' => $userId,
                'tanggal' => $tanggal,
                'kehadiran' => $kehadiran,
                'pelanggaran' => $item['pelanggaran'] ?? '-',
                'bimbingan_ke' => $item['bimbingan_ke'] ?? null,
                'notes' => $item['notes'] ?? null,
                'tahunAjar' => $tahunAjar,
            ]);

            $count++;
        }

        return back()->with('success', "✅ {$count} data bimbingan berhasil disimpan untuk tahun ajar {$tahunAjar}!");
    }

    // ============================================================
    // 🔸 UPDATE DATA BIMBINGAN
    // ============================================================
    public function update(Request $request, $id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        $bimbingan->update($request->only(['kehadiran', 'pelanggaran', 'bimbingan_ke', 'notes']));
        return back()->with('success', '✅ Data bimbingan berhasil diperbarui!');
    }

    // ============================================================
    // 🔸 HAPUS DATA BIMBINGAN
    // ============================================================
    public function destroy($id)
    {
        Bimbingan::destroy($id);
        return back()->with('success', '🗑️ Data bimbingan dihapus!');
    }
}
