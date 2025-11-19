<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use Illuminate\Http\Request;

// 🔹 Import semua controller yang digunakan
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
use App\Http\Controllers\WaliController; 
use App\Models\Siswa;
=======
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');
>>>>>>> b4712e343ea9b1a684999026f127c6e091fd7427

use App\Http\Controllers\GuruBKController;


Route::get('/gurubk', [BimbinganController::class, 'index'])->name('bimbingan');



// Menyimpan data bimbingan (dari form POST)
Route::post('/gurubk/store', [BimbinganController::class, 'store'])->name('bimbingan.store');

// Guru BK
// ==========================================================
// 📘 GURU BK PANEL (semua tab dalam 1 halaman Bimbingan)
// ==========================================================
Route::post('/gurubk/store', [BimbinganController::class, 'store'])->name('bimbingan.store');

/*
|--------------------------------------------------------------------------
| 🔐 AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');
Route::get('/regis', [AuthController::class, 'showRegisterForm'])->name('regis');
Route::post('/regis', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 🧩 KEBUTUHAN BK & WALI KELAS
|--------------------------------------------------------------------------
*/
Route::get('/kebutuhanbk', function () {
    $role = session('role');
    if (!in_array($role, ['Guru BK', 'Kepala Sekolah', 'Wakil Kepala Sekolah', 'Admin'])) {
        return redirect()->route('login')->with('error', 'Akses ditolak!');
    }
    return view('pilihanmenubk');
})->name('kebutuhanbk');

Route::get('/kebutuhanwalikelas', function () {
    $role = session('role');
    if (!in_array($role, ['Wali Kelas', 'Kepala Sekolah', 'Wakil Kepala Sekolah', 'Admin'])) {
        return redirect()->route('login')->with('error', 'Akses ditolak!');
    }
    return view('menuwalikelas');
})->name('kebutuhanwalikelas');

/*
|--------------------------------------------------------------------------
| 🏫 KELAS ROUTES
|--------------------------------------------------------------------------
*/
Route::resource('kelas', KelasController::class)->except(['show', 'create']);

/*
|--------------------------------------------------------------------------
| 👨‍🎓 SISWA ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])->name('index');
    Route::post('/import', [SiswaController::class, 'import'])->name('import');
    Route::get('/edit/{nis}', [SiswaController::class, 'edit'])->name('edit');
    Route::put('/update/{nis}', [SiswaController::class, 'update'])->name('update');
    Route::delete('/delete/{nis}', [SiswaController::class, 'delete'])->name('delete');
});

<<<<<<< HEAD

// ===== GURU BK: BIMBINGAN =====
Route::get('/gurubk/bimbingan', [BimbinganController::class, 'index'])
    ->name('guru.bimbingan.index');


Route::get('/gurubk/rekap-bimbingan', [BimbinganController::class, 'rekap'])
    ->name('guru.bimbingan.rekap');

Route::get('/gurubk/rekap-bimbingan/edit/{id}', [BimbinganController::class, 'edit'])
    ->name('guru.bimbingan.edit');


Route::delete('/gurubk/rekap-bimbingan/delete/{id}', [BimbinganController::class, 'destroy'])
    ->name('guru.bimbingan.destroy');



/*
|--------------------------------------------------------------------------
| 📋 ABSENSI (INPUT GURU MAPEL)
|--------------------------------------------------------------------------
*/
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

// 🟡 Rekap absensi (versi admin)
Route::get('/absensi/rekap', [RekapAbsensiController::class, 'index'])->name('absensi.rekap');
Route::post('/absensi/rekap/update', [RekapAbsensiController::class, 'update'])->name('absensi.update');
Route::get('/rekapabsensi', [RekapAbsensiController::class, 'index'])->name('rekapabsensi');

/*
|--------------------------------------------------------------------------
| 🧑‍🏫 WALI KELAS PANEL (REKAP ABSENSI HARIAN)
|--------------------------------------------------------------------------
| Tambahan fitur edit & hapus data absensi wali kelas.
*/
/*
|-------------------------------------------------------------------------- 
| 🧑‍🏫 WALI KELAS PANEL (REKAP ABSENSI HARIAN)
|-------------------------------------------------------------------------- 
*/


Route::prefix('wali')->group(function () {
    Route::get('/rekapabsensi', [WaliController::class, 'rekapAbsensi'])->name('wali.rekapabsensi');
    Route::get('/rekapabsensi/edit/{id}', [WaliController::class, 'editAbsensi'])->name('wali.editAbsensi');
    Route::put('/rekapabsensi/update/{id}', [WaliController::class, 'updateAbsensi'])->name('wali.updateAbsensi');
    Route::delete('/rekapabsensi/delete/{id}', [WaliController::class, 'deleteAbsensi'])->name('wali.deleteAbsensi');
});


/*
|--------------------------------------------------------------------------
| ⚠️ PELANGGARAN ROUTES
|--------------------------------------------------------------------------
*/
// 🔹 Halaman utama (input pelanggaran)
Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');

// 🔹 Halaman rekap pelanggaran
Route::get('/pelanggaran/rekap', [PelanggaranController::class, 'rekap'])->name('pelanggaran.rekap');

// 🔹 Edit pelanggaran (form edit)
Route::get('/pelanggaran/{id}/edit', [PelanggaranController::class, 'edit'])->name('pelanggaran.edit');

// 🔹 Update pelanggaran
Route::put('/pelanggaran/{id}', [PelanggaranController::class, 'update'])->name('pelanggaran.update');

// 🔹 Hapus pelanggaran
Route::delete('/pelanggaran/{id}', [PelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');
Route::post('/pelanggaran', [PelanggaranController::class, 'store'])->name('pelanggaran.store');
/*
|--------------------------------------------------------------------------
| 🏆 PRESTASI ROUTES
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
    Route::put('/kategori/{id}', [PrestasiController::class, 'update'])->name('kategori.update');
    Route::put('/jenis/{id}', [PrestasiController::class, 'update'])->name('jenis.update');
    Route::delete('/jenis/{id}', [PrestasiController::class, 'destroy'])->name('jenis.destroy');

});



/*
|--------------------------------------------------------------------------
| 🔍 PEMANTAUAN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/pemantauan', [PemantauanController::class, 'index'])->name('pemantauan.index');

/*
|--------------------------------------------------------------------------
| ⚙️ AJAX: GET SISWA
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

     /*
|--------------------------------------------------------------------------
| 🎓 BIMBINGAN (GURU BK PANEL)
|--------------------------------------------------------------------------
*/
Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan');
Route::post('/bimbingan', [BimbinganController::class, 'store'])->name('bimbingan.store');
Route::put('/bimbingan/{id}', [BimbinganController::class, 'update'])->name('bimbingan.update');
Route::delete('/bimbingan/{id}', [BimbinganController::class, 'destroy'])->name('bimbingan.destroy');
Route::put('/bimbingan/update/{id}', [BimbinganController::class, 'update'])->name('bimbingan.update');
Route::delete('/bimbingan/destroy/{id}', [BimbinganController::class, 'destroy'])->name('bimbingan.destroy');

// 🟢 Rekap bimbingan
Route::get('/rekapbimbingan', [RekapBimbinganController::class, 'index'])->name('rekapbimbingan');


// Edit
Route::get('/bimbingan/edit/{id}', [BimbinganController::class, 'edit'])->name('bimbingan.edit');

// Update
Route::post('/bimbingan/update/{id}', [BimbinganController::class, 'update'])->name('bimbingan.update');

// Delete
Route::delete('/bimbingan/delete/{id}', [BimbinganController::class, 'delete'])->name('bimbingan.delete');

// WALI REKAP ABSENSI
Route::get('/wali/rekapabsensi', 
    [WaliController::class, 'rekapAbsensi']
)->name('wali.rekapabsensi');



// Wali Absensi (Edit/Hapus)
Route::get('/wali/absensi/{id}/edit', [WaliController::class, 'editAbsensi'])->name('wali.absensi.edit');
Route::delete('/wali/absensi/{id}/delete', [WaliController::class, 'deleteAbsensi'])->name('wali.absensi.delete');
Route::post('/wali/absensi/{id}/update', [WaliController::class, 'updateAbsensi'])->name('wali.absensi.update');

Route::put('/wali/rekapabsensi/update/{id}', 
    [WaliController::class, 'updateAbsensi']
)->name('wali.updateAbsensi');

Route::get('/pemantauan/absensi/detail',
    [PemantauanController::class, 'detailAbsensi']
)->name('pemantauan.absensi.detail');

=======
require __DIR__.'/settings.php';
>>>>>>> b4712e343ea9b1a684999026f127c6e091fd7427
