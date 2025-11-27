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
    $kelas = $request->kelas;
    $jurusan = $request->jurusan;
    $tahunAjar = $request->tahunAjar;
    $mode = $request->mode ?? 'bimbingan';
    // Rekap Absensi
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    // Rekap Bimbingan
    $tanggal_awal_bimbingan = $request->tanggal_awal_bimbingan;
    $tanggal_akhir_bimbingan = $request->tanggal_akhir_bimbingan;

    // Rekap Pelanggaran
    $tanggal_awal_pelanggaran = $request->tanggal_awal_pelanggaran;
    $tanggal_akhir_pelanggaran = $request->tanggal_akhir_pelanggaran;



    // Dropdown
    $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();
    $daftar_jurusan = ['IPA','IPS'];
    $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar','desc')->pluck('tahunAjar');

    // =====================
    // MODE: INPUT BIMBINGAN
    // =====================
    $siswa = collect();
    if ($mode == 'bimbingan' && $kelas && $jurusan && $tahunAjar) {
        $siswa = Siswa::where('kelas_siswa',$kelas)
            ->where('jurusan_siswa',$jurusan)
            ->where('tahunAjar',$tahunAjar)
            ->orderBy('nama_siswa')
            ->get();
    }

    // =====================
    // MODE: REKAP ABSENSI
    // =====================
    $rekapAbsensi = collect();
if ($mode == 'rekapbk' && $kelas && $jurusan && $tahunAjar) {
    $rekapAbsensi = Absensi::join('siswa','siswa.NIS','=','absensi.NIS')
        ->select('siswa.nama_siswa','absensi.status','absensi.tanggal')
        ->where('siswa.kelas_siswa',$kelas)
        ->where('siswa.jurusan_siswa',$jurusan)
        ->where('absensi.tahunAjar',$tahunAjar)
        ->when($tanggal_awal && $tanggal_akhir, function($q) use ($tanggal_awal, $tanggal_akhir) {
            $q->whereBetween('absensi.tanggal', [$tanggal_awal, $tanggal_akhir]);
        })
        ->orderBy('absensi.tanggal','desc')
        ->get();
}


    // =====================
    // MODE: REKAP BIMBINGAN
    // =====================
    $riwayat = collect();
if ($mode == 'rekapbimbingan' && $kelas && $jurusan && $tahunAjar) {

    $riwayat = Bimbingan::join('siswa','siswa.NIS','=','bimbingan.NIS')
        ->select('bimbingan.*','siswa.nama_siswa','siswa.kelas_siswa','siswa.jurusan_siswa')
        ->where('siswa.kelas_siswa',$kelas)
        ->where('siswa.jurusan_siswa',$jurusan)
        ->where('bimbingan.tahunAjar',$tahunAjar)
        ->when($tanggal_awal_bimbingan && $tanggal_akhir_bimbingan, function($q) use ($tanggal_awal_bimbingan, $tanggal_akhir_bimbingan) {
            $q->whereBetween('bimbingan.tanggal', [$tanggal_awal_bimbingan, $tanggal_akhir_bimbingan]);
        })
        ->orderBy('bimbingan.tanggal','desc')
        ->get();
}


  // =====================
// MODE: REKAP PELANGGARAN
// =====================
$rekap_pelanggaran = collect();
if ($mode == 'rekappelanggaran' && $kelas && $jurusan && $tahunAjar) {
    $rekap_pelanggaran = DB::table('pelanggaran')
        ->join('siswa','siswa.NIS','=','pelanggaran.NIS')
        ->join('jenispelanggaran','jenispelanggaran.id_jenispelanggaran','=','pelanggaran.id_jenispelanggaran')
        ->select('pelanggaran.tanggal','siswa.nama_siswa','jenispelanggaran.nama_pelanggaran','pelanggaran.notes')
        ->where('siswa.kelas_siswa',$kelas)
        ->where('siswa.jurusan_siswa',$jurusan)
        ->where('pelanggaran.tahunAjar',$tahunAjar)
        ->when($tanggal_awal_pelanggaran && $tanggal_akhir_pelanggaran, function($q) use ($tanggal_awal_pelanggaran, $tanggal_akhir_pelanggaran) {
            $q->whereBetween('pelanggaran.tanggal', [$tanggal_awal_pelanggaran, $tanggal_akhir_pelanggaran]);
        })
        ->orderBy('pelanggaran.tanggal','desc')
        ->get();
}



$rekap = $rekapAbsensi;

return view('bimbingan', compact(
    'kelas','jurusan','tahunAjar','mode',
    'daftar_kelas','daftar_jurusan','daftar_tahunAjar',
    'siswa','riwayat','rekap','rekap_pelanggaran',
    'tanggal_awal','tanggal_akhir',
    'tanggal_awal_bimbingan','tanggal_akhir_bimbingan',
    'tanggal_awal_pelanggaran','tanggal_akhir_pelanggaran'
));



}



    // ============================================================
    // 🔸 SIMPAN DATA BIMBINGAN
    // ============================================================
    public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'data' => 'required|array',
    ]);

    $tanggal = $request->tanggal;
    $tahunAjar = $request->tahunAjar ?? date('Y') . '/' . (date('Y') + 1);
    $count = 0;
    $userId = Auth::id() ?? 1;

    foreach ($request->data as $item) {
        if (empty($item['bimbingan_ke']) && empty($item['notes'])) {
            continue;
        }

        Bimbingan::create([
            'NIS'           => $item['NIS'],
            'id_user'       => $userId,
            'tanggal'       => $tanggal,
            'tahunAjar'     => $tahunAjar,
            'bimbingan_ke'  => $item['bimbingan_ke'] ?? null,
            'notes'         => $item['notes'] ?? null,
        ]);

        $count++;
    }

    return back()->with('success', "✓ {$count} data bimbingan berhasil disimpan!");
}


    public function edit($id)
{
    $data = Bimbingan::join('siswa','siswa.NIS','=','bimbingan.NIS')
            ->select('bimbingan.*','siswa.nama_siswa')
            ->where('id_bimbingan',$id)
            ->first();

    return view('bimbingan_edit', compact('data'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'pelanggaran' => 'nullable|string',
        'bimbingan_ke' => 'nullable|integer',
        'notes' => 'nullable|string',
    ]);

    Bimbingan::where('id_bimbingan', $id)->update([
        'pelanggaran' => $request->pelanggaran,
        'bimbingan_ke' => $request->bimbingan_ke,
        'notes'       => $request->notes,
    ]);

    return redirect()->route('bimbingan', [
        'mode' => 'rekapbimbingan',
        'kelas' => $request->kelas,
        'jurusan' => $request->jurusan,
        'tahunAjar' => $request->tahunAjar,
        'tanggal_riwayat' => $request->tanggal
    ])->with('success','Data bimbingan berhasil diperbarui!');
}

public function delete($id)
{
    Bimbingan::where('id_bimbingan', $id)->delete();
    return back()->with('success','Data bimbingan berhasil dihapus!');
}

public function rekap(Request $request)
{
    $kelas = $request->kelas;
    $jurusan = $request->jurusan;
    $tahunAjar = $request->tahunAjar;
    $tanggal = $request->tanggal;

    // Dropdown
    $daftar_kelas = Kelas::select('nama_kelas')->distinct()->orderBy('nama_kelas')->get();
    $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar','desc')->pluck('tahunAjar');

    // Ambil data bimbingan berdasarkan filter
    $bimbingan = collect();

    if ($kelas && $jurusan && $tahunAjar && $tanggal) {
        $bimbingan = Bimbingan::join('siswa', 'siswa.NIS', '=', 'bimbingan.NIS')
            ->select('bimbingan.*', 'siswa.nama_siswa', 'siswa.kelas_siswa', 'siswa.jurusan_siswa')
            ->where('siswa.kelas_siswa', $kelas)
            ->where('siswa.jurusan_siswa', $jurusan)
            ->where('bimbingan.tahunAjar', $tahunAjar)
            ->whereDate('bimbingan.tanggal_bimbingan', $tanggal)
            ->orderBy('bimbingan.tanggal_bimbingan', 'desc')
            ->get();
    }

    // Ambil absensi hari itu
    $absensi = Absensi::whereDate('tanggal', $tanggal)->get();

    // Ambil pelanggaran hari itu
    $pelanggaran = DB::table('pelanggaran')->whereDate('tanggal', $tanggal)->get();

    return view('bimbingan', compact(
        'kelas',
        'jurusan',
        'tahunAjar',
        'tanggal',
        'daftar_kelas',
        'daftar_tahunAjar',
        'bimbingan',
        'absensi',
        'pelanggaran'
    ));
}


} 