<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemantauan Sekolah</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
    .section-title { border-radius: 6px 6px 0 0; padding: 8px 15px; color: white; font-weight: 600; }
  </style>
</head>
<body>

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center px-4">
      <a class="navbar-brand fw-bold" href="#">🏫 Pemantauan Sekolah</a>
      <span class="text-light fw-semibold">👋 Selamat Datang, {{ $namaUser }}</span>
    </div>
  </nav>

  <div class="container mt-4">
    <h3 class="text-center fw-bold mb-4">📋 Rekap Data Sekolah</h3>

    <!-- 🔹 Filter -->
    <form method="GET" action="{{ route('pemantauan.index') }}" class="row g-3 justify-content-center mb-4">

      <!-- Kelas -->
      <div class="col-md-3">
        <label class="form-label fw-semibold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">Pilih Kelas</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k }}" {{ ($kelas ?? '') == $k ? 'selected' : '' }}>
              {{ $k }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Jurusan -->
      <div class="col-md-3">
        <label class="form-label fw-semibold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">Pilih Jurusan</option>
          @foreach($daftar_jurusan as $j)
            <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>
              {{ $j }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Tahun Ajar -->
      <div class="col-md-3">
        <label class="form-label fw-semibold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
          <option value="">Pilih Tahun Ajar</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>
              {{ $t }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-funnel"></i> Tampilkan
        </button>
      </div>
    </form>

    <!-- ✅ Rekap Bimbingan -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-info">
        <i class="bi bi-chat-left-text"></i> Rekap Bimbingan Konseling
      </div>
      <div class="p-3">
        @if($bimbingan->count() > 0)
          <table class="table table-sm table-bordered">
            <thead class="table-info text-center">
              <tr><th>Nama</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
              @foreach($bimbingan as $b)
                <tr>
                  <td>{{ $b->nama_siswa ?? '-' }}</td>
                  <td>{{ $b->keterangan ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="alert alert-warning mb-0">Belum ada data bimbingan.</div>
        @endif
      </div>
    </div>

    <!-- ✅ Rekap Absensi -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-warning text-dark">
        <i class="bi bi-calendar2-check"></i> Rekap Absensi
      </div>
      <div class="p-3">
        @if($absensi->count() > 0)
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>Kelas</th><th>Jurusan</th><th>Total</th><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpa</th>
              </tr>
            </thead>
            <tbody>
              @foreach($absensi as $a)
                <tr>
                  <td>{{ $a->kelas_siswa ?? '-' }}</td>
                  <td>{{ $a->jurusan_siswa ?? '-' }}</td>
                  <td>{{ $a->total ?? 0 }}</td>
                  <td class="text-success">{{ $a->hadir ?? 0 }}</td>
                  <td class="text-warning">{{ $a->sakit ?? 0 }}</td>
                  <td class="text-primary">{{ $a->izin ?? 0 }}</td>
                  <td class="text-danger">{{ $a->alpa ?? 0 }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="alert alert-warning mb-0">Belum ada data absensi.</div>
        @endif
      </div>
    </div>

    <!-- ✅ Rekap Pelanggaran -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-danger">
        <i class="bi bi-exclamation-triangle"></i> Rekap Pelanggaran
      </div>
      <div class="p-3">
        @if($pelanggaran->count() > 0)
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr><th>Tanggal</th><th>Nama</th><th>Kelas</th><th>Jurusan</th><th>Jenis</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
              @foreach($pelanggaran as $p)
                <tr>
                  <td>{{ $p->tanggal }}</td>
                  <td>{{ $p->nama_siswa ?? '-' }}</td>
                  <td>{{ $p->kelas_siswa ?? '-' }}</td>
                  <td>{{ $p->jurusan_siswa ?? '-' }}</td>
                  <td>{{ $p->jenis ?? '-' }}</td>
                  <td>{{ $p->keterangan ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="alert alert-warning mb-0">Belum ada data pelanggaran.</div>
        @endif
      </div>
    </div>
  </div>

</body>
</html>
