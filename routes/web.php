<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RekapAbsensiController;
use App\Http\Controllers\RekapBimbinganController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\PemantauanController;
use App\Models\Siswa;

/*
|--------------------------------------------------------------------------
| KEBUTUHAN BK
|--------------------------------------------------------------------------
*/
Route::get('/kebutuhanbk', function () {
    $role = session('role');
    if (!in_array($role, ['Guru BK', 'Kepala Sekolah', 'Wakil Kepala Sekolah', 'Admin'])) {
        return redirect()->route('login')->with('error', 'Akses ditolak!');
    }
    return view('pilihanmenubk');
})->name('kebutuhanbk');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');
Route::get('/regis', [AuthController::class, 'showRegisterForm'])->name('regis');
Route::post('/regis', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| KELAS ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');

/*
|--------------------------------------------------------------------------
| SISWA ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])->name('index');
    Route::post('/import', [SiswaController::class, 'import'])->name('import');
    Route::get('/edit/{nis}', [SiswaController::class, 'edit'])->name('edit');
    Route::put('/update/{nis}', [SiswaController::class, 'update'])->name('update');
    Route::delete('/delete/{nis}', [SiswaController::class, 'delete'])->name('delete');
});

/*
|--------------------------------------------------------------------------
| BIMBINGAN, ABSENSI, DLL
|--------------------------------------------------------------------------
*/
Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan');
Route::post('/bimbingan', [BimbinganController::class, 'store'])->name('bimbingan.store');
Route::put('/bimbingan/{id}', [BimbinganController::class, 'update'])->name('bimbingan.update');
Route::delete('/bimbingan/{id}', [BimbinganController::class, 'destroy'])->name('bimbingan.destroy');
Route::get('/rekapbimbingan', [RekapBimbinganController::class, 'index'])->name('rekapbimbingan.index');

// 🟢 Rekap Absensi Guru BK
Route::get('/bimbingan/rekapabsensi', [BimbinganController::class, 'index'])
    ->name('bimbingan.rekapabsensi')
    ->defaults('mode', 'rekapbk');

/*
|--------------------------------------------------------------------------
| ABSENSI ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

// 🟡 REKAP ABSENSI
Route::get('/absensi/rekap', [RekapAbsensiController::class, 'index'])->name('absensi.rekap');
Route::post('/absensi/rekap/update', [RekapAbsensiController::class, 'update'])->name('absensi.update');

/*
|--------------------------------------------------------------------------
| PELANGGARAN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
Route::post('/pelanggaran', [PelanggaranController::class, 'store'])->name('pelanggaran.store');
Route::get('/pelanggaran/rekap', [PelanggaranController::class, 'rekap'])->name('pelanggaran.rekap');

Route::get('/pelanggaran/{id}/edit', [PelanggaranController::class, 'edit'])->name('pelanggaran.edit');
Route::put('/pelanggaran/{id}', [PelanggaranController::class, 'update'])->name('pelanggaran.update');
Route::delete('/pelanggaran/{id}', [PelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');

/*
|--------------------------------------------------------------------------
| PRESTASI ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('prestasi')->group(function () {
    Route::get('/', [PrestasiController::class, 'index'])->name('prestasi.index');
    Route::get('/kategori', [PrestasiController::class, 'kategori'])->name('prestasi.kategori');
    Route::get('/jenis', [PrestasiController::class, 'jenis'])->name('prestasi.jenis');
    Route::get('/input', [PrestasiController::class, 'input'])->name('prestasi.input');
    Route::get('/rekap', [PrestasiController::class, 'rekap'])->name('prestasi.rekap');
    Route::get('/edit/{id}', [PrestasiController::class, 'edit'])->name('prestasi.edit');
    Route::put('/update/{id}', [PrestasiController::class, 'update'])->name('prestasi.update');
    Route::delete('/{id}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');
    
    Route::post('/kategori', [PrestasiController::class, 'storeKategori'])->name('kategori.store');
    Route::post('/jenis', [PrestasiController::class, 'storeJenis'])->name('jenis.store');
    Route::post('/simpan', [PrestasiController::class, 'store'])->name('prestasi.store');

    Route::delete('/kategori/{id}', [PrestasiController::class, 'destroyKategori'])->name('kategori.destroy');
    Route::delete('/jenis/{id}', [PrestasiController::class, 'destroyJenis'])->name('prestasi.jenis.destroy');
});

/*
|--------------------------------------------------------------------------
| PEMANTAUAN ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/pemantauan', [PemantauanController::class, 'index'])->name('pemantauan.index');

/*
|--------------------------------------------------------------------------
| AJAX GET-SISWA (untuk prestasi & pelanggaran)
|--------------------------------------------------------------------------
*/
Route::get('/get-siswa', function (Request $request) {
    $kelas = $request->get('kelas');
    $jurusan = $request->get('jurusan');

    $siswa = Siswa::where('kelas_siswa', $kelas)
        ->where('jurusan_siswa', $jurusan)
        ->orderBy('nama_siswa')
        ->get(['NIS', 'nama_siswa']);

    return response()->json($siswa);
});
