<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rekap Pelanggaran Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: 600; }
.btn-kembali {
    background-color: #343a40;
    color: #fff;
    padding: 8px 16px;
    border-radius: 8px;
    transition: 0.2s;
}
.btn-kembali:hover {
    background-color: #23272b;
    color: #fff;
}

  </style>
</head>
<body>

{{-- Tampilkan NAV hanya jika halaman ini BUKAN dari BK Panel --}}
@if(!isset($from_bk))
<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Sistem Pelanggaran</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a href="{{ route('pelanggaran.index') }}" class="nav-link">Input Pelanggaran</a>
          </li>
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


  <!-- 🔹 Konten Utama -->
  <div class="container py-4">
    <!-- 🔙 Tombol Kembali ke Menu Guru BK -->
<div class="mb-4">
  <a href="{{ route('kebutuhanbk') }}" 
     class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
    <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Guru BK
  </a>
</div>


    <h3 class="text-center mb-4">
        Rekap Pelanggaran Siswa
    </h3>

    {{-- 🔹 FILTER --}}
    <form method="GET" action="{{ route('pelanggaran.rekap') }}" class="row justify-content-center g-3 mb-4">

        <div class="col-md-3">
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

        <div class="col-md-3">
            <label class="form-label fw-bold">Jurusan</label>
            <select name="jurusan" class="form-select" required>
                <option value="">-- Pilih Jurusan --</option>
                @foreach(['IPA','IPS'] as $j)
                    <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>
                        {{ $j }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Tahun Ajar</label>
            <select name="tahunAjar" class="form-select" required>
                <option value="">-- Pilih Tahun Ajar --</option>
                @foreach($daftar_tahunAjar as $t)
                    <option value="{{ $t }}" {{ request('tahunAjar') == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-bold">Tanggal</label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ request('tanggal') }}" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Tampilkan</button>
        </div>
    </form>

    {{-- 🔹 TABLE --}}
    @if(request()->has(['kelas','jurusan','tahunAjar','tanggal']))

        @if($rekap->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle shadow-sm">
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
                    @foreach($rekap as $r)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $r->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $r->siswa->kelas_siswa ?? '-' }}</td>
                        <td>{{ $r->siswa->jurusan_siswa ?? '-' }}</td>
                        <td>{{ $r->tahunAjar ?? '-' }}</td>
                        <td>{{ $r->jenis->nama_pelanggaran ?? '-' }}</td>
                        <td>{{ $r->jenis->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $r->jumlah ?? 1 }}</td>
                        <td>{{ $r->notes ?? '-' }}</td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ $r->id_pelanggaran ? route('pelanggaran.edit', $r->id_pelanggaran) : '#' }}"
                                class="btn btn-warning btn-sm {{ !$r->id_pelanggaran ? 'disabled' : '' }}">
                                Edit
                                </a>


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
            <div class="alert alert-warning text-center">
                ⚠️ Tidak ada data pelanggaran untuk filter ini.
            </div>
        @endif

    @else
        <div class="alert alert-info text-center">
            Pilih kelas, jurusan, tahun ajar, dan tanggal untuk menampilkan data.
        </div>
    @endif
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
