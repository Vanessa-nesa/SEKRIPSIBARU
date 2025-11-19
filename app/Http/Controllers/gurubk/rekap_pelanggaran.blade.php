<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>📋 Rekap Pelanggaran Siswa - Guru BK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
    .navbar-brand { font-weight: 600; }
    .table th { background-color: #343a40; color: #fff; }
    .table td { vertical-align: middle; }
    .alert { border-radius: 8px; }
  </style>
</head>

<body>
  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#">Guru BK Panel</a>
      <div class="d-flex">
        <a href="{{ route('pelanggaran.index') }}" class="btn btn-outline-light btn-sm me-2">Input Pelanggaran</a>
        <a href="{{ route('logout') }}" class="btn btn-danger btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- 🔹 Konten Utama -->
  <div class="container py-5">
    <h3 class="text-center fw-bold mb-4">📄 Rekap Pelanggaran Siswa</h3>

    {{-- 🔹 Form Filter --}}
    <form method="GET" action="{{ route('gurubk.rekap.pelanggaran') }}" class="row justify-content-center mb-4">
      <div class="col-md-3 mb-2">
        <label class="form-label fw-semibold">Kelas</label>
        <select name="kelas" class="form-select">
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ request('kelas') == $k->nama_kelas ? 'selected' : '' }}>
              {{ $k->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3 mb-2">
        <label class="form-label fw-semibold">Jurusan</label>
        <select name="jurusan" class="form-select">
          <option value="">-- Pilih Jurusan --</option>
          <option value="IPA" {{ request('jurusan') == 'IPA' ? 'selected' : '' }}>IPA</option>
          <option value="IPS" {{ request('jurusan') == 'IPS' ? 'selected' : '' }}>IPS</option>
        </select>
      </div>

      <div class="col-md-3 mb-2">
        <label class="form-label fw-semibold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select">
          <option value="">-- Pilih Tahun Ajar --</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ request('tahunAjar') == $t ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2 mb-2">
        <label class="form-label fw-semibold">Tanggal Pelanggaran</label>
        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
      </div>

      <div class="col-md-1 d-flex align-items-end mb-2">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    {{-- 🔹 Hasil Rekap --}}
    @if(isset($rekap) && $rekap->count() > 0)
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle shadow-sm">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Jenis Pelanggaran</th>
              <th>Poin</th>
              <th>Tanggal</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rekap as $i => $r)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->nama_siswa }}</td>
                <td>{{ $r->kelas ?? '-' }}</td>
                <td>{{ $r->jurusan ?? '-' }}</td>
                <td>{{ $r->nama_jenis ?? '-' }}</td>
                <td class="fw-bold text-danger">{{ $r->poin ?? 0 }}</td>
                <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $r->notes ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @elseif(request()->has('kelas'))
      <div class="alert alert-warning text-center">⚠️ Belum ada data pelanggaran untuk filter yang dipilih.</div>
    @else
      <div class="alert alert-info text-center">Silakan pilih filter terlebih dahulu untuk menampilkan data.</div>
    @endif
  </div>
</body>
</html>
