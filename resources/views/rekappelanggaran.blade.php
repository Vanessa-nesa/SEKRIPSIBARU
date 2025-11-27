<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Pelanggaran Siswa</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- File CSS dan JS dari Laravel Vite -->
  @vite([
      'resources/css/pelanggaran.css',
      'resources/js/app.js'
  ])
</head>
<body>

<!-- Menampilkan navbar hanya jika halaman bukan berasal dari BK Panel -->
@if(!isset($from_bk))

<!-- Navbar utama -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

      <!-- Judul halaman dan nama user yang sedang login -->
      <a class="navbar-brand d-flex align-items-center" href="#">
        Laporan Pelanggaran
        @if(session('nama'))
            <span class="ms-2 fw-bold text-white">| {{ session('nama') }}</span>
        @endif
      </a>

      <!-- Tombol menu responsif -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu navigasi kanan navbar -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

          <!-- Navigasi menuju halaman input pelanggaran -->
          <li class="nav-item">
            <a href="{{ route('pelanggaran.index') }}" class="nav-link">Input Pelanggaran</a>
          </li>

          <!-- Form logout -->
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-light ms-2">Logout</button>
            </form>
          </li>

        </ul>
      </div>

    </div>
</nav>

@endif

<!-- Konten utama halaman -->
<div class="container py-4">

    <!-- Tombol kembali ke menu Guru BK -->
    <div class="mb-4">
      <a href="{{ route('kebutuhanbk') }}" 
         class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
        <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Guru BK
      </a>
    </div>

    <!-- Judul halaman -->
    <h3 class="text-center mb-4">
        Laporan Pelanggaran Siswa
    </h3>

<!-- Filter laporan pelanggaran -->
<form method="GET" action="{{ route('pelanggaran.rekap') }}" class="row g-3 mb-4">

    <!-- Filter kelas -->
    <div class="col-md-2">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($daftar_kelas as $k)
                <option value="{{ $k->nama_kelas }}" {{ request('kelas') == $k->nama_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Filter jurusan -->
    <div class="col-md-2">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
            @foreach(['IPA','IPS'] as $j)
                <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
    </div>

    <!-- Filter tahun ajar -->
    <div class="col-md-2">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
            @foreach($daftar_tahunAjar as $t)
                <option value="{{ $t }}" {{ request('tahunAjar') == $t ? 'selected' : '' }}>
                  {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Filter tanggal awal -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}" required>
    </div>

    <!-- Filter tanggal akhir -->
    <div class="col-md-2">
        <label class="form-label fw-bold">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}" required>
    </div>

    <!-- Tombol tampilkan data -->
    <div class="col-md-1 d-flex align-items-end">
        <button class="btn btn-primary w-100">Tampil</button>
    </div>

</form>

<!-- Menampilkan tabel hanya jika seluruh filter terisi -->
@if(request()->has(['kelas','jurusan','tahunAjar','tanggal_awal','tanggal_akhir']))

    <!-- Jika data pelanggaran ditemukan -->
    @if($rekap->count() > 0)

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle shadow-sm">

            <!-- Header tabel -->
            <thead class="table-dark">
              <tr>
                  <th>Tanggal</th>
                  <th>Nama Siswa</th>
                  <th>Kelas</th>
                  <th>Jurusan</th>
                  <th>Tahun Ajar</th>
                  <th>Jenis Pelanggaran</th>
                  <th>Kategori</th>
                  <th>Jumlah</th>
                  <th>Catatan</th>
                  <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
                <!-- Loop setiap data pelanggaran -->
                @foreach($rekap as $r)
                <tr>
                    <!-- Menampilkan tanggal dalam format d-m-Y -->
                    <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>

                    <!-- Data siswa -->
                    <td>{{ $r->siswa->nama_siswa ?? '-' }}</td>
                    <td>{{ $r->siswa->kelas_siswa ?? '-' }}</td>
                    <td>{{ $r->siswa->jurusan_siswa ?? '-' }}</td>

                    <!-- Tahun ajar -->
                    <td>{{ $r->tahunAjar ?? '-' }}</td>

                    <!-- Jenis pelanggaran dan kategori -->
                    <td>{{ $r->jenis->nama_pelanggaran ?? '-' }}</td>
                    <td>{{ $r->jenis->kategori->nama_kategori ?? '-' }}</td>

                    <!-- Jumlah pelanggaran dan catatan -->
                    <td>{{ $r->jumlah ?? 1 }}</td>
                    <td>{{ $r->notes ?? '-' }}</td>

                    <!-- Aksi edit dan hapus -->
                    <td>
                        <div class="d-flex justify-content-center gap-2">

                            <!-- Tombol edit -->
                            <a href="{{ $r->id_pelanggaran ? route('pelanggaran.edit', $r->id_pelanggaran) : '#' }}"
                               class="btn btn-warning btn-sm {{ !$r->id_pelanggaran ? 'disabled' : '' }}">
                               Edit
                            </a>

                            <!-- Form hapus -->
                            <form method="POST"
                                  action="{{ $r->id_pelanggaran ? route('pelanggaran.destroy', $r->id_pelanggaran) : '#' }}"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    @else

    <!-- Ditampilkan jika tidak ada data -->
    <div class="alert alert-warning text-center">
        Tidak ada data pelanggaran untuk filter ini.
    </div>

    @endif

@else

<!-- Ditampilkan jika user belum memilih filter -->
<div class="alert alert-info text-center">
    Pilih kelas, jurusan, tahun ajar, dan tanggal untuk menampilkan data.
</div>

@endif

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
