<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\JenisPrestasi;
use App\Models\KategoriPrestasi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PrestasiController extends Controller
{
    // ================================
    // 🏠 Halaman Utama → redirect ke kategori
    // ================================
    public function index()
    {
        return redirect()->route('prestasi.kategori');
    }

    // ================================
    // 🟦 Halaman Kategori (GET)
    // ================================
    public function kategori()
    {
        $kategori = KategoriPrestasi::orderBy('nama_kategori')->get();
        return view('kategoriprestasi', [
            'kategori' => $kategori,
            'page' => 'kategori'
        ]);
    }

    // ✅ Tambah kategori (POST)
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:200',
        ]);

        KategoriPrestasi::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return back()->with('success', 'Kategori prestasi berhasil ditambahkan!');
    }

    // ================================
    // 🟨 Halaman Jenis Prestasi (GET)
    // ================================
    public function jenis()
    {
        $kategori = KategoriPrestasi::all();
        $jenis = JenisPrestasi::with('kategori')->orderBy('nama_jenis')->get();

        return view('jenisprestasi', [
            'kategori' => $kategori,
            'jenis' => $jenis,
            'page' => 'jenis'
        ]);
    }

    // ✅ Tambah jenis prestasi (POST)
    public function storeJenis(Request $request)
    {
        $request->validate([
            'id_kategoriprestasi' => 'required|integer',
            'nama_jenis' => 'required|string|max:200',
        ]);

        JenisPrestasi::create([
            'id_kategoriprestasi' => $request->id_kategoriprestasi,
            'nama_jenis' => $request->nama_jenis,
        ]);

        return back()->with('success', 'Jenis prestasi berhasil ditambahkan!');
    }

    // ================================
    // 🟩 Halaman Input Prestasi (GET)
    // ================================
    public function input(Request $request)
    {
        $kelasDipilih = $request->get('kelas');
        $jurusanDipilih = $request->get('jurusan');

        $daftar_kelas = Kelas::orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        $siswa = collect();
        if ($kelasDipilih && $jurusanDipilih) {
            $siswa = Siswa::where('kelas_siswa', $kelasDipilih)
                ->where('jurusan_siswa', $jurusanDipilih)
                ->orderBy('nama_siswa')
                ->get();
        }

        $jenis = JenisPrestasi::with('kategori')->get();
        $prestasi = Prestasi::with('jenis')->orderBy('tanggal', 'desc')->get();

        return view('prestasi', [
            'jenis' => $jenis,
            'prestasi' => $prestasi,
            'daftar_kelas' => $daftar_kelas,
            'daftar_tahunAjar' => $daftar_tahunAjar,
            'siswa' => $siswa,
            'kelasDipilih' => $kelasDipilih,
            'jurusanDipilih' => $jurusanDipilih,
            'page' => 'input'
        ]);
    }

    // ✅ Simpan data prestasi siswa (POST)
    public function store(Request $request)
    {
        $request->validate([
            'NIS' => 'required',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
            'id_jenisprestasi' => 'required|integer',
            'tanggal' => 'required|date',
            'tahunAjar' => 'required|string|max:20',
            'file_prestasi' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $uploadPath = public_path('uploads/prestasi');
        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $fileName = null;
        if ($request->hasFile('file_prestasi')) {
            $file = $request->file('file_prestasi');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);
        }

        Prestasi::create([
            'NIS' => $request->NIS,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'id_user' => auth()->id() ?? 1,
            'id_jenisprestasi' => $request->id_jenisprestasi,
            'tanggal' => $request->tanggal,
            'tahunAjar' => $request->tahunAjar,
            'file_prestasi' => $fileName,
        ]);

        return back()->with('success', '✅ Prestasi siswa berhasil disimpan!');
    }

    // ================================
    // 🟪 Halaman Rekap Prestasi (GET)
    // ================================
    public function rekap(Request $request)
    {
        $jenis = JenisPrestasi::with('kategori')->get();
        $query = Prestasi::with(['jenis', 'jenis.kategori', 'siswa']);

        if ($request->filled('kelas')) $query->where('kelas', $request->kelas);
        if ($request->filled('jurusan')) $query->where('jurusan', $request->jurusan);
        if ($request->filled('tahunAjar')) $query->where('tahunAjar', $request->tahunAjar);
        if ($request->filled('id_jenisprestasi')) $query->where('id_jenisprestasi', $request->id_jenisprestasi);

        $prestasi = $query->orderBy('tanggal', 'desc')->get();

        $daftar_kelas = Kelas::orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        return view('rekapprestasi', [
            'jenis' => $jenis,
            'prestasi' => $prestasi,
            'daftar_kelas' => $daftar_kelas,
            'daftar_tahunAjar' => $daftar_tahunAjar,
            'page' => 'rekap'
        ]);
    }

    // ================================
    // ✏️ Edit Prestasi
    // ================================
    public function edit($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $jenis = JenisPrestasi::all();
        $daftar_kelas = Kelas::all();
        $daftar_tahunAjar = ['2024/2025', '2025/2026', '2026/2027'];

        return view('editprestasi', [
            'prestasi' => $prestasi,
            'jenis' => $jenis,
            'daftar_kelas' => $daftar_kelas,
            'daftar_tahunAjar' => $daftar_tahunAjar,
            'page' => 'rekap'
        ]);
    }

    // ✅ Update Prestasi
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_jenisprestasi' => 'required',
            'tanggal' => 'required|date',
            'tahunAjar' => 'required|string',
            'file_prestasi' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $prestasi = Prestasi::findOrFail($id);
        $prestasi->id_jenisprestasi = $request->id_jenisprestasi;
        $prestasi->tanggal = $request->tanggal;
        $prestasi->tahunAjar = $request->tahunAjar;

        if ($request->hasFile('file_prestasi')) {
            $file = $request->file('file_prestasi');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/prestasi'), $namaFile);
            $prestasi->file_prestasi = $namaFile;
        }

        $prestasi->save();

        return redirect()->route('prestasi.rekap')->with('success', 'Data prestasi berhasil diperbarui!');
    }

    // 🗑️ Hapus Prestasi
    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        if ($prestasi->file_prestasi && file_exists(public_path('uploads/prestasi/' . $prestasi->file_prestasi))) {
            unlink(public_path('uploads/prestasi/' . $prestasi->file_prestasi));
        }
        $prestasi->delete();

        return redirect()->route('prestasi.rekap')->with('success', 'Data prestasi berhasil dihapus!');
    }
    public function destroyKategori($id)
{
    $kategori = KategoriPrestasi::findOrFail($id);

    // Hapus semua jenis prestasi yang terkait kategori ini
    JenisPrestasi::where('id_kategoriprestasi', $id)->delete();

    // Hapus kategori
    $kategori->delete();

    return back()->with('success', 'Kategori prestasi berhasil dihapus!');
}


}

