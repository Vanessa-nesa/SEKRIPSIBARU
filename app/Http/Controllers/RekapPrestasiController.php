<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestasi;
use App\Models\JenisPrestasi;
use App\Models\Kelas;

class RekapPrestasiController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 Ambil semua jenis prestasi untuk filter dropdown
        $jenis = JenisPrestasi::orderBy('nama_jenis')->get();

        // 🔹 Ambil daftar kelas & tahun ajar dari tabel kelas
        $daftar_kelas = Kelas::orderBy('nama_kelas')->get();
        $daftar_tahunAjar = Kelas::select('tahunAjar')->distinct()->orderBy('tahunAjar', 'desc')->pluck('tahunAjar');

        // 🔹 Ambil filter dari request
        $kelas = $request->kelas;
        $jurusan = $request->jurusan;
        $tahunAjar = $request->tahunAjar;
        $id_jenisprestasi = $request->id_jenisprestasi;

        // 🔹 Query utama
        $query = Prestasi::with(['jenis', 'siswa']);

        // Terapkan filter dinamis
        if (!empty($kelas)) {
            $query->where('kelas', $kelas);
        }

        if (!empty($jurusan)) {
            $query->where('jurusan', $jurusan);
        }

        if (!empty($tahunAjar)) {
            $query->where('tahunAjar', 'LIKE', "%$tahunAjar%");
        }

        if (!empty($id_jenisprestasi)) {
            $query->where('id_jenisprestasi', $id_jenisprestasi);
        }

        // 🔹 Eksekusi query
        $prestasi = $query->orderByDesc('tanggal')->get();

        // 🔹 Kirim ke view
        return view('rekapprestasi', compact(
            'prestasi',
            'jenis',
            'kelas',
            'jurusan',
            'tahunAjar',
            'id_jenisprestasi',
            'daftar_kelas',
            'daftar_tahunAjar'
        ));
    }
}
