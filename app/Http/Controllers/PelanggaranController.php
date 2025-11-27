<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranController extends Controller
{
    /**
     * 🔹 Tampilkan halaman input pelanggaran
     */
    public function index(Request $request)
    {
        // ✅ Hanya Guru BK yang bisa input
        if (session('role') !== 'Guru BK') {
            return redirect()->route('login')->with('error', 'Akses ditolak! Hanya Guru BK yang dapat membuka halaman ini.');
        }

        // Ambil filter dari form
        $tahunAjar = $request->get('tahunAjar');
        $kelas = $request->get('kelas');
        $jurusan = $request->get('jurusan');

        // 🔹 Ambil data kelas dan tahun ajaran dari tabel kelas
        $daftar_kelas = Kelas::orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        // 🔹 Ambil data siswa berdasarkan filter
        $siswa = collect();
        if ($tahunAjar && $kelas && $jurusan) {
            $siswa = Siswa::where('kelas_siswa', $kelas)
                ->where('jurusan_siswa', $jurusan)
                ->orderBy('nama_siswa')
                ->get();
        }

        $jenispelanggaran = JenisPelanggaran::with('kategori')->get();

        return view('pelanggaran', compact(
            'kelas',
            'jurusan',
            'tahunAjar',
            'daftar_kelas',
            'daftar_tahunAjar',
            'siswa',
            'jenispelanggaran'
        ));
    }

    /**
     * 🔹 Simpan data pelanggaran
     */
    public function store(Request $request)
    {
        if (session('role') !== 'Guru BK') {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'tahunAjar' => 'required|string',
            'data' => 'required|array',
        ]);

        $userId = Auth::id() ?? session('id_user');
        $tanggal = $request->tanggal;
        $tahunAjar = $request->tahunAjar;
        $count = 0;

        foreach ($request->data as $item) {
            if (empty($item['id_jenispelanggaran']) || empty($item['NIS'])) continue;

            Pelanggaran::create([
                'NIS' => $item['NIS'],
                'id_user' => $userId,
                'id_jenispelanggaran' => $item['id_jenispelanggaran'],
                'tanggal' => $tanggal,
                'tahunAjar' => $tahunAjar,
                'jumlah' => $item['jumlah'] ?? 1,
                'notes' => $item['notes'] ?? null,
            ]);

            $count++;
        }

        return back()->with('success', "✅ {$count} data pelanggaran berhasil disimpan!");
    }

    /**
     * 🔹 Rekap pelanggaran (Guru BK, Kepala, Wakil)
     */
    public function rekap(Request $request)
{
    if (!in_array(session('role'), ['Guru BK', 'Kepala Sekolah', 'Wakil Kepala Sekolah'])) {
        return redirect()->route('login')->with('error', 'Akses ditolak!');
    }

    $tanggal_awal = $request->get('tanggal_awal');
    $tanggal_akhir = $request->get('tanggal_akhir');
    $kelas = $request->get('kelas');
    $jurusan = $request->get('jurusan');
    $tahunAjar = $request->get('tahunAjar');

    $rekap = collect();

    // ⚠️ Hanya jalankan query kalau filter lengkap
    if ($tanggal_awal && $tanggal_akhir && $kelas && $jurusan && $tahunAjar) {
        $rekap = Pelanggaran::with(['siswa', 'jenis.kategori'])
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
            ->where('tahunAjar', $tahunAjar)
            ->whereHas('siswa', function ($query) use ($kelas, $jurusan) {
                $query->where('kelas_siswa', $kelas)
                      ->where('jurusan_siswa', $jurusan);
            })
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    // Tahun ajaran dan kelas
    $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');
    $daftar_kelas = Kelas::orderBy('nama_kelas')->get();

    return view('rekappelanggaran', compact(
        'rekap',
        'tanggal_awal',
        'tanggal_akhir',
        'kelas',
        'jurusan',
        'tahunAjar',
        'daftar_kelas',
        'daftar_tahunAjar'
    ));
}

    // Form Edit Pelanggaran
public function edit($id)
{
    $pelanggaran = Pelanggaran::with(['siswa', 'jenis.kategori'])->findOrFail($id);
    $jenispelanggaran = JenisPelanggaran::with('kategori')->get();

    return view('editpelanggaran', compact('pelanggaran', 'jenispelanggaran'));
}

// 💾 Update data pelanggaran
public function update(Request $request, $id)
{
    $request->validate([
        'id_jenispelanggaran' => 'required|integer',
        'jumlah' => 'nullable|integer|min:1',
        'notes' => 'nullable|string|max:600',
    ]);

    $pelanggaran = Pelanggaran::findOrFail($id);
    $pelanggaran->update([
        'id_jenispelanggaran' => $request->id_jenispelanggaran,
        'jumlah' => $request->jumlah ?? 1,
        'notes' => $request->notes,
    ]);

    return redirect()->route('pelanggaran.rekap')->with('success', '✅ Data pelanggaran berhasil diperbarui!');
}

// 🗑️ Hapus pelanggaran
public function destroy($id)
{
    $pelanggaran = Pelanggaran::findOrFail($id);
    $pelanggaran->delete();

    return back()->with('success', 'Data pelanggaran berhasil dihapus.');
}

}
