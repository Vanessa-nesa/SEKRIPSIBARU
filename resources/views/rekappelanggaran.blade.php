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
  </style>
</head>
<body>

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
            <a href="{{ route('pelanggaran.index') }}" class="nav-link">📝 Input Pelanggaran</a>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-light ms-2">🚪 Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- 🔹 Konten Utama -->
  <div class="container py-5">
    <h3 class="text-center mb-4">📑 Rekap Pelanggaran Siswa</h3>

    {{-- 🔹 Filter: Kelas, Jurusan, Tahun Ajar, Tanggal --}}
    <form method="GET" action="{{ route('pelanggaran.rekap') }}" class="row justify-content-center mb-4">

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
            <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label fw-bold">Tahun Ajaran</label>
        <select name="tahunAjar" class="form-select" required>
          <option value="">-- Pilih Tahun Ajaran --</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ request('tahunAjar') == $t ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label fw-bold">Tanggal</label>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control" required>
      </div>

      <div class="col-md-2 d-flex align-items-end mt-4">
  <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
</div>

    </form>

    {{-- 🔹 Tabel hasil rekap --}}
    @if(request()->has(['tanggal','kelas','jurusan','tahunAjar']))
      @if($rekap->count() > 0)
        <div class="table-responsive">
          <table class="table table-bordered text-center shadow-sm">
            <thead class="table-dark">
  <tr>
    <th>Tanggal</th>
    <th>Nama Siswa</th>
    <th>Kelas</th>
    <th>Jurusan</th>
    <th>Tahun Ajaran</th>
    <th>Jenis Pelanggaran</th>
    <th>Kategori</th>
    <th>Jumlah</th>
    <th>Catatan</th>
    <th>Aksi</th> {{-- ✅ Tambahan kolom aksi --}}
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
    {{-- ✏️ Tombol Edit --}}
    <a href="{{ route('pelanggaran.edit', $r->id_pelanggaran) }}" 
       class="btn btn-warning btn-sm px-3">✏️ Edit</a>

    {{-- 🗑️ Tombol Hapus --}}
    <form action="{{ route('pelanggaran.destroy', $r->id_pelanggaran) }}" 
          method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger btn-sm px-3">🗑️ Hapus</button>
    </form>
  </div>
</td>

                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="alert alert-warning text-center mt-4">
          ⚠️ Tidak ada data pelanggaran untuk pilihan ini.
        </div>
      @endif
    @else
      <div class="alert alert-info text-center mt-4">
        Pilih kelas, jurusan, tahun ajaran, dan tanggal terlebih dahulu untuk menampilkan data.
      </div>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
