<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemantauan Sekolah</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- CSS Terpisah -->
  <link href="{{ asset('css/pemantauan.css') }}" rel="stylesheet">
</head>
<body>

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center px-4">
      <a class="navbar-brand fw-bold" href="#">Pemantauan Sekolah</a>

      <div class="d-flex align-items-center gap-3">
        <span class="text-light fw-semibold">{{ $namaUser }}</span>

        <!-- 🔹 Tombol Logout -->
        <form action="{{ route('logout') }}" method="GET">
          <button class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right"></i> Logout
          </button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h3 class="text-center fw-bold mb-4">Rekap Data Sekolah</h3>

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

    <!-- =============================
         REKAP BIMBINGAN
    =============================== -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-info">
        Rekap Bimbingan Konseling
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
                  <td>{{ $b->nama_siswa }}</td>
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

   <!-- =============================
     REKAP ABSENSI
=============================== -->
<div class="mb-4 border rounded">
  
  <!-- 🔹 HEADER WARNA (FULL WIDTH) -->
  <div class="section-title bg-warning text-dark">
      Rekap Absensi
  </div>

  <div class="p-3">
      <table class="table table-bordered table-absensi">
          <thead>
              <tr>
                  <th>Kelas</th>
                  <th>Jurusan</th>
                  <th>Total</th>
                  <th>Hadir</th>
                  <th>Sakit</th>
                  <th>Izin</th>
                  <th>Alpa</th>
                  <th>Aksi</th>
              </tr>
          </thead>
          <tbody>
              @foreach($absensi as $a)
              <tr>
                  <td>{{ $a->kelas_siswa }}</td>
                  <td>{{ $a->jurusan_siswa }}</td>
                  <td>{{ $a->total }}</td>

                  <td class="text-success fw-bold">{{ $a->hadir }}</td>
                  <td class="text-warning fw-bold">{{ $a->sakit }}</td>
                  <td class="text-primary fw-bold">{{ $a->izin }}</td>
                  <td class="text-danger fw-bold">{{ $a->alpa }}</td>

                  <td>
                      <a href="{{ route('pemantauan.absensi.detail', ['kelas'=>$kelas,'jurusan'=>$jurusan,'tahunAjar'=>$tahunAjar]) }}"
                          class="btn btn-dark btn-sm">
                          Detail
                      </a>
                  </td>
              </tr>
              @endforeach
          </tbody>
      </table>
  </div>

</div>


    <!-- =============================
         REKAP PELANGGARAN
    =============================== -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-danger">
        Rekap Pelanggaran
      </div>
      <div class="p-3">
        @if($pelanggaran->count() > 0)
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th><th>Nama</th><th>Kelas</th><th>Jurusan</th>
                <th>Jenis</th><th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pelanggaran as $p)
                <tr>
                  <td>{{ $p->tanggal }}</td>
                  <td>{{ $p->nama_siswa }}</td>
                  <td>{{ $p->kelas_siswa }}</td>
                  <td>{{ $p->jurusan_siswa }}</td>
                  <td>{{ $p->jenis }}</td>
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

    <!-- =============================
         REKAP PRESTASI (NEW)
    =============================== -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-success">
        Rekap Prestasi
      </div>

      <div class="p-3">
        @if($prestasi->count() > 0)
        <table class="table table-bordered align-middle">
          <thead class="table-success">
            <tr>
              <th>Nama</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Jenis Prestasi</th>
              <th>Tingkat</th>
              <th>Penyelenggara</th>
              <th>Keterangan</th>
            </tr>
          </thead>

          <tbody>
            @foreach($prestasi as $pr)
            <tr>
              <td>{{ $pr->nama_siswa }}</td>
              <td>{{ $pr->kelas_siswa }}</td>
              <td>{{ $pr->jurusan_siswa }}</td>
              <td>{{ $pr->jenis ?? '-' }}</td>
              <td>{{ $pr->tingkat ?? '-' }}</td>
              <td>{{ $pr->penyelenggara ?? '-' }}</td>
              <td>{{ $pr->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-warning mb-0">Belum ada data prestasi.</div>
        @endif
      </div>
    </div>

  </div>

</body>
</html>
