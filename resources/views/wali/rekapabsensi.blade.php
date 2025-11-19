<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rekap Absensi Wali Kelas</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  @vite(['resources/css/absensi.css'])
</head>

<body class="bg-light">

 {{-- 🔹 NAVBAR WALIKELAS: hanya tampil jika BUKAN dari BK --}}
@if(!isset($from_bk))
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a class="navbar-brand fw-bold mb-0 h5 text-white">Absensi Wali Kelas Panel</a>
            <span class="text-white ms-2">| {{ session('nama') ?? 'Guru Wali' }}</span>
        </div>

        <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}"
                 href="{{ route('absensi.index') }}">
                 Input Absensi
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('wali.rekapabsensi') ? 'active' : '' }}"
                 href="{{ route('wali.rekapabsensi') }}">
                 Rekap Absensi
              </a>
            </li>

            <li class="nav-item ms-3">
              <form method="GET" action="{{ route('logout') }}">
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
              </form>
            </li>
        </ul>
    </div>
</nav>

{{-- Tombol kembali --}}
<div class="mb-4 mt-3 ms-3">
  <a href="{{ route('kebutuhanwalikelas') }}" class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
    <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Wali Kelas
  </a>
</div>
@endif


  <!-- 🔹 Main Content -->
  <div class="container">

<h3 class="text-center fw-bold mb-4">Data Rekap Absensi</h3>

<div class="mx-auto" style="max-width: 1100px">

<form method="GET" action="{{ route('wali.rekapabsensi') }}" class="row g-3 mb-4">

    <div class="col-md-3">
        <label class="form-label fw-semibold">Kelas</label>
        <select name="kelas" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($daftar_kelas as $k)
                <option value="{{ $k->nama_kelas }}" {{ ($kelas ?? '') == $k->nama_kelas ? 'selected':'' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
            <option value="IPA" {{ ($jurusan ?? '')=='IPA'?'selected':'' }}>IPA</option>
            <option value="IPS" {{ ($jurusan ?? '')=='IPS'?'selected':'' }}>IPS</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
            @foreach($daftar_tahunAjar as $t)
                <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected':'' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Tanggal</label>
        <input type="date" name="tanggal" value="{{ $tanggal ?? '' }}" class="form-control" required>
    </div>

    <div class="col-12 text-center">
        <button class="btn btn-primary px-5 mt-2">Tampilkan</button>
    </div>

</form>

{{-- ======================================
      TABEL ABSENSI
====================================== --}}
@if(isset($detailAbsensi) && $detailAbsensi->count())
<div class="table-responsive">
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>NAMA SISWA</th>
                <th>KELAS</th>
                <th>JURUSAN</th>
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

                <td class="fw-bold 
                    @if($d->status=='Hadir') text-success
                    @elseif($d->status=='Sakit') text-warning
                    @elseif($d->status=='Izin') text-primary
                    @else text-danger @endif
                ">
                    {{ $d->status }}
                </td>

                <td>{{ $d->keterangan ?? '-' }}</td>

                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('wali.absensi.edit', $d->id_absensi) }}"
                           class="btn btn-warning btn-sm d-flex align-items-center">
                            Edit
                        </a>

                        <!--<form action="{{ route('wali.absensi.delete', $d->id_absensi) }}"
                              method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm d-flex align-items-center">
                                🗑️ Hapus
                            </button>
                        </form>-->
                        
                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>

@elseif(request()->has('kelas'))
    <div class="alert alert-warning text-center">
        ⚠ Belum ada data absensi untuk filter ini.
    </div>
@endif

</div> <!-- end max-width -->



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
