<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Absensi Wali Kelas</title>

  <!-- Bootstrap CSS untuk styling tampilan -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- CSS khusus halaman absensi -->
  @vite(['resources/css/absensi.css'])
</head>

<body class="bg-light">

{{-- 
    Navbar hanya ditampilkan ketika halaman ini diakses oleh wali kelas.
    Jika halaman ini diakses dari bagian BK (from_bk = true), navbar tidak ditampilkan.
--}}
@if(!isset($from_bk))
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Nama panel dan nama pengguna (wali kelas) -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand fw-bold mb-0 h5 text-white">Absensi Wali Kelas Panel</a>
            <span class="text-white ms-2">| {{ session('nama') ?? 'Guru Wali' }}</span>
        </div>

        <!-- Menu navigasi navbar -->
        <ul class="navbar-nav d-flex align-items-center">

            <!-- Menu ke halaman input absensi -->
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}"
                 href="{{ route('absensi.index') }}">
                 Input Absensi
              </a>
            </li>

            <!-- Menu ke halaman laporan absensi -->
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('wali.rekapabsensi') ? 'active' : '' }}"
                 href="{{ route('wali.rekapabsensi') }}">
                 Laporan Absensi
              </a>
            </li>

            <!-- Tombol untuk logout -->
            <li class="nav-item ms-3">
                <form id="logoutForm" method="GET" action="{{ route('logout') }}">
                    <button type="button" id="logoutBtn" class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<!-- Tombol kembali ke menu utama wali kelas -->
<div class="mb-4 mt-3 ms-3">
<a href="{{ route('kebutuhanwalikelas') }}" 
   class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
    <i class="bi bi-arrow-left-circle me-2"></i>
    Kembali ke Menu Wali Kelas
</a>

</div>
@endif


<div class="container mt-4">

<!-- Judul halaman -->
<h3 class="text-center fw-bold mb-4">Data Laporan Absensi</h3>

<div class="mx-auto" style="max-width: 1100px">

<!-- 
    Form filter laporan:
    User dapat memilih kelas, jurusan, tahun ajar, tanggal awal, tanggal akhir.
-->
<form method="GET" action="{{ route('wali.rekapabsensi') }}" class="row g-3 mb-4">

    <!-- Dropdown kelas -->
    <div class="col-md-2">
        <label class="form-label fw-semibold">Kelas</label>
        <select name="kelas" class="form-select" required>
            <option value="">-- Pilih --</option>
            @foreach($daftar_kelas as $k)
                <option value="{{ $k->nama_kelas }}" {{ ($kelas ?? '') == $k->nama_kelas ? 'selected':'' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Dropdown jurusan -->
    <div class="col-md-2">
        <label class="form-label fw-semibold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
            <option value="IPA" {{ ($jurusan ?? '')=='IPA'?'selected':'' }}>IPA</option>
            <option value="IPS" {{ ($jurusan ?? '')=='IPS'?'selected':'' }}>IPS</option>
        </select>
    </div>

    <!-- Dropdown tahun ajar -->
    <div class="col-md-2">
        <label class="form-label fw-semibold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
            @foreach($daftar_tahunAjar as $t)
                <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected':'' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Input tanggal awal -->
    <div class="col-md-2">
        <label class="form-label fw-semibold">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" class="form-control"
               value="{{ request('tanggal_awal') }}" required>
    </div>

    <!-- Input tanggal akhir -->
    <div class="col-md-2">
        <label class="form-label fw-semibold">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control"
               value="{{ request('tanggal_akhir') }}" required>
    </div>

    <!-- Tombol submit filter -->
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100">Tampilkan</button>
    </div>

</form>


<!-- 
    Tabel laporan absensi
    Ditampilkan hanya jika data ada setelah proses filter.
-->
@if(isset($detailAbsensi) && $detailAbsensi->count())
<div class="table-responsive">
    <table class="table table-bordered table-striped text-center align-middle">

        <!-- Header tabel -->
        <thead class="table-dark">
            <tr>
                <th>NAMA SISWA</th>
                <th>KELAS</th>
                <th>JURUSAN</th>
                <th>TANGGAL</th>
                <th>STATUS</th>
                <th>KETERANGAN</th>
                <th>AKSI</th>
            </tr>
        </thead>

        <tbody>
            @foreach($detailAbsensi as $d)
            <tr>
                <td class="fw-semibold">{{ $d->nama_siswa }}</td>
                <td>{{ $d->kelas_siswa }}</td>
                <td>{{ $d->jurusan_siswa }}</td>

                <!-- Format tanggal agar lebih mudah dibaca -->
                <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}</td>

                <!-- Status absensi dengan warna berbeda untuk membedakan kondisi -->
                <td class="fw-bold 
                    @if($d->status=='Hadir') text-success
                    @elseif($d->status=='Sakit') text-warning
                    @elseif($d->status=='Izin') text-primary
                    @else text-danger @endif
                ">
                    {{ $d->status }}
                </td>

                <!-- Tampilkan '-' jika kolom keterangan kosong -->
                <td>{{ $d->keterangan ?? '-' }}</td>

                <!-- Tombol edit untuk revisi absensi -->
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('wali.absensi.edit', $d->id_absensi) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>
                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<!-- Jika filter diterapkan tetapi tidak ada data ditemukan -->
@elseif(request()->has('kelas'))
    <div class="alert alert-warning text-center">
        Tidak ada data absensi untuk filter yang dipilih.
    </div>
@endif

</div> <!-- end max-width -->


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Script logout dengan konfirmasi bawaan browser -->
<script>
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (confirm("Yakin ingin logout?")) {
        document.getElementById('logoutForm').submit();
    }
});
</script>


</body>
</html>
