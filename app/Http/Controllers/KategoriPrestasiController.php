<?php

namespace App\Http\Controllers;

use App\Models\KategoriPrestasi;
use Illuminate\Http\Request;

class KategoriPrestasiController extends Controller
{
    public function index()
    {
        $kategori = KategoriPrestasi::all();
        return view('kategoriprestasi', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required']);
        KategoriPrestasi::create($request->only(['nama_kategori', 'deskripsi']));
        return back()->with('success', 'Kategori prestasi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        KategoriPrestasi::destroy($id);
        return back()->with('success', 'Kategori prestasi dihapus!');
    }
}
