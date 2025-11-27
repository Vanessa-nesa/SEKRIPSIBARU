<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('regis');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 🔹 Ambil user berdasarkan username
        $user = Pengguna::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Simpan data user ke session
            $request->session()->put([
                'id_user' => $user->id_user,
                'username' => $user->username,
                'role' => $user->role,
                'nama' => $user->nama,
            ]);

            // 🔹 Arahkan sesuai role
            switch ($user->role) {
                case 'Guru BK':
                    return redirect()
                        ->route('kebutuhanbk')
                        ->with('success', 'Selamat datang, Guru BK ' . $user->nama . '!');

                case 'Wali Kelas':
                    return redirect()
                        ->route('kebutuhanwalikelas')
                        ->with('success', 'Selamat datang Wali Kelas ' . $user->nama . '!');

                case 'Kepala Sekolah':
    case 'Wakil Kepala Sekolah':
        return redirect()
            ->route('pemantauan.index')
            ->with('success', 'Selamat datang ' . $user->role . ' ' . $user->nama . '!');


                default:
                    return redirect()
                        ->route('pemantauan.index')
                        ->with('success', 'Login berhasil!');
            }
        }
        // Jika login gagal
        return back()->with('error', 'Username atau password salah!');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:user,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
        ]);

        // Simpan ke tabel user
        Pengguna::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
