<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rekap Bimbingan Konseling</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
  </style>
</head>

<body>
  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">📘 Guru BK Panel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="{{ route('bimbingan') }}">Input Bimbingan</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('rekap.absensi') }}">Rekap Absensi</a></li>
          <li class="nav-item"><a class="nav-link active" href="{{ route('rekapbimbingan.index') }}">Rekap Bimbingan</a></li>
          <li class="nav-item"><a class="nav-link text-danger fw-bold" href="{{ route('logout') }}">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- 🔹 Konten Utama -->
  <div class="container py-5">
    <h3 class="text-center mb-4">📅 Rekap Bimbingan Konseling</h3>

    {{-- 🔸 Filter Riwayat --}}
    <form method="GET" action="{{ route('rekapbimbingan.index') }}" class="row justify-content-center g-3 mb-4">
      {{-- Kelas --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas ?? '') == $k->nama_kelas ? 'selected' : '' }}>
              {{ $k->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Jurusan --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">-- Pilih Jurusan --</option>
          @foreach(['IPA','IPS'] as $j)
            <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>

      {{-- Tahun Ajar --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
          <option value="">-- Pilih Tahun Ajar --</option>
          @php
            $tahunSekarang = date('Y');
            $daftar_tahunAjar = [
              ($tahunSekarang - 1) . '/' . $tahunSekarang,
              "$tahunSekarang/" . ($tahunSekarang + 1),
              ($tahunSekarang + 1) . '/' . ($tahunSekarang + 2)
            ];
          @endphp
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>
              {{ $t }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Tanggal Bimbingan (date input) --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">Tanggal Bimbingan</label>
        <input type="date" name="tanggal_riwayat" class="form-control" value="{{ $tanggal_riwayat ?? '' }}">
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    {{-- 🔹 Tabel Hasil --}}
    @if(isset($riwayat) && count($riwayat) > 0)
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm">
          <thead class="table-secondary">
            <tr>
              <th>Tanggal</th>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Tahun Ajar</th>
              <th>Pelanggaran</th>
              <th>Bimbingan Ke-</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($riwayat as $r)
              <tr>
                <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $r->nama_siswa ?? '-' }}</td>
                <td>{{ $r->kelas ?? '-' }}</td>
                <td>{{ $r->jurusan ?? '-' }}</td>
                <td>{{ $r->tahunAjar ?? '-' }}</td>
                <td>{{ $r->pelanggaran ?? '-' }}</td>
                <td>{{ $r->bimbingan_ke ?? '-' }}</td>
                <td>{{ $r->notes ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    @elseif(isset($rekapBimbingan) && $rekapBimbingan->count() > 0)
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm">
          <thead class="table-info">
            <tr>
              <th>Tanggal</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Tahun Ajar</th>
              <th>Total Siswa Dibimbing</th>
              <th>Total Sesi Bimbingan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rekapBimbingan as $r)
              <tr>
                <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $r->kelas }}</td>
                <td>{{ $r->jurusan }}</td>
                <td>{{ $r->tahunAjar }}</td>
                <td>{{ $r->total_siswa ?? '-' }}</td>
                <td>{{ $r->total_bimbingan ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    @elseif(request()->has('kelas'))
      <div class="alert alert-warning text-center mt-4">
        Tidak ada data bimbingan untuk pilihan ini.
      </div>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
