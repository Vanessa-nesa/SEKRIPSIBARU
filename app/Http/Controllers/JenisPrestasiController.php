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
    public function update(Request $request, $id)
{
    $request->validate([
        'id_kategoriprestasi' => 'required',
        'nama_jenis' => 'required|string|max:255',
    ]);

    $jenis = JenisPrestasi::findOrFail($id);
    $jenis->id_kategoriprestasi = $request->id_kategoriprestasi;
    $jenis->nama_jenis = $request->nama_jenis;
    $jenis->save();

    return redirect()->back()->with('success', 'Jenis prestasi berhasil diperbarui!');
}
public function destroyJenis($id)
{
    $jenis = JenisPrestasi::findOrFail($id);
    $jenis->delete();

    return back()->with('success', 'Jenis prestasi berhasil dihapus!');
}


}
