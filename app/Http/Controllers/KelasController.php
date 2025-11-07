<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\User;

class KelasController extends Controller
{
    /** 🔹 Tampilkan semua kelas */
    public function index()
    {
        // Ambil semua kelas + relasi user pembuat
        $data = Kelas::with('user')->orderBy('nama_kelas')->get();

        // Ambil data user dari session untuk ditampilkan di navbar
        $user = User::where('id_user', session('user_id'))->first();

        return view('kelas', compact('data', 'user'));
    }

    /** 🔹 Simpan kelas baru */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:30',
            'tahunAjar'  => 'required|string|max:20',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tahunAjar'  => $request->tahunAjar,
            'id_user'    => session('user_id') ?? 1, // default 1 kalau belum login
        ]);

        return redirect()->route('kelas.index')
                         ->with('success', '✅ Kelas baru berhasil ditambahkan!');
    }

    /** 🔹 Edit kelas (ambil data 1 kelas untuk form) */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $data = Kelas::with('user')->orderBy('nama_kelas')->get();

        return view('kelas', compact('kelas', 'data'))
            ->with('editMode', true);
    }

    /** 🔹 Update kelas */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:30',
            'tahunAjar'  => 'required|string|max:20',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tahunAjar'  => $request->tahunAjar,
        ]);

        return redirect()->route('kelas.index')
                         ->with('success', '📝 Data kelas berhasil diperbarui!');
    }

    /** 🔹 Hapus kelas */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')
                         ->with('success', '❌ Kelas berhasil dihapus.');
    }
}
