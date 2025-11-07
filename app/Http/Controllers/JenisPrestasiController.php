<?php

namespace App\Http\Controllers;

use App\Models\JenisPrestasi;
use App\Models\KategoriPrestasi;
use Illuminate\Http\Request;

class JenisPrestasiController extends Controller
{
    public function index()
    {
        $kategori = KategoriPrestasi::all();
        $jenis = JenisPrestasi::with('kategori')->get();
        return view('jenisprestasi', compact('jenis', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategoriprestasi' => 'required',
            'nama_jenis' => 'required'
        ]);
        JenisPrestasi::create($request->only(['id_kategoriprestasi', 'nama_jenis']));
        return back()->with('success', 'Jenis prestasi berhasil ditambahkan!');
    }
}
