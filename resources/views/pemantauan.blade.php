<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Mengatur charset dan tampilan responsif -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Judul halaman -->
  <title>Pemantauan Sekolah</title>

  <!-- Import Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Import Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- CSS eksternal untuk styling tambahan -->
  <link href="{{ asset('css/pemantauan.css') }}" rel="stylesheet">
</head>
<body>

<!-- ============================================================
     NAVBAR
     Menampilkan judul aplikasi, nama user, dan tombol logout
============================================================ -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center px-4">

        <!-- Bagian kiri navbar: judul dan nama user -->
        <div class="d-flex align-items-center gap-3">

            <!-- Judul aplikasi -->
            <a class="navbar-brand fw-bold mb-0" href="#">Pemantauan Sekolah</a>

            <!-- Nama user dari session -->
            <span class="text-light fw-semibold">|
              {{ session('nama') ?? 'User' }}
            </span>
        </div>

        <!-- Bagian kanan navbar: tombol logout -->
        <li class="nav-item ms-3 d-flex align-items-center">

            <!-- Tombol logout yang memunculkan konfirmasi -->
            <button type="button" id="logoutBtn" class="btn btn-outline-light btn-sm">
                Logout
            </button>

            <!-- Form logout tersembunyi yang dikirim lewat JavaScript -->
            <form id="logoutForm" method="GET" action="{{ route('logout') }}" style="display: none;"></form>
        </li>

    </div>
</nav>

<!-- ============================================================
     KONTEN UTAMA
     berisi judul dan semua rekap laporan sekolah
============================================================ -->
<div class="container mt-4">

    <!-- Judul besar halaman -->
    <h3 class="text-center fw-bold mb-4">Laporan Data Sekolah</h3>

    <!-- ========================================================
         FORM FILTER
         Memilih kelas, jurusan, dan tahun ajar untuk menampilkan data
    ========================================================= -->
    <form method="GET" action="{{ route('pemantauan.index') }}" class="row g-3 justify-content-center mb-4">

      <!-- Dropdown kelas -->
      <div class="col-md-3">
        <label class="form-label fw-semibold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">Pilih Kelas</option>

          <!-- Loop daftar kelas dari controller -->
          @foreach($daftar_kelas as $k)
            <option value="{{ $k }}" {{ ($kelas ?? '') == $k ? 'selected' : '' }}>
              {{ $k }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Dropdown jurusan -->
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

      <!-- Dropdown tahun ajar -->
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

      <!-- Tombol tampilkan data -->
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-funnel"></i> Tampilkan
        </button>
      </div>
    </form>

    <!-- ============================================================
         LAPORAN BIMBINGAN KONSELING
============================================================ -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-info">
        Laporan Bimbingan Konseling
      </div>

      <div class="p-3">

        <!-- Cek apakah ada data -->
        @if($bimbingan->count() > 0)

          <!-- Tabel bimbingan -->
          <table class="table table-sm table-bordered">
            <thead class="table-info text-center">
              <tr><th>Nama</th><th>Keterangan</th></tr>
            </thead>
            <tbody>

              <!-- Loop data bimbingan -->
              @foreach($bimbingan as $b)
                <tr>
                  <td>{{ $b->nama_siswa }}</td>
                  <td>{{ $b->keterangan ?? '-' }}</td>
                </tr>
              @endforeach

            </tbody>
          </table>

        <!-- Jika data kosong -->
        @else
          <div class="alert alert-warning mb-0">Belum ada data bimbingan.</div>
        @endif
      </div>
    </div>

<!-- ============================================================
     LAPORAN ABSENSI
============================================================ -->
<div class="mb-4 border rounded">

  <!-- Header dengan warna -->
  <div class="section-title bg-warning text-dark">
      Laporan Absensi
  </div>

  <div class="p-3">

      <!-- Tabel rekap absensi -->
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

              <!-- Loop data absensi -->
              @foreach($absensi as $a)
              <tr>
                  <td>{{ $a->kelas_siswa }}</td>
                  <td>{{ $a->jurusan_siswa }}</td>
                  <td>{{ $a->total }}</td>

                  <!-- Menampilkan total sesuai jenis status -->
                  <td class="text-success fw-bold">{{ $a->hadir }}</td>
                  <td class="text-warning fw-bold">{{ $a->sakit }}</td>
                  <td class="text-primary fw-bold">{{ $a->izin }}</td>
                  <td class="text-danger fw-bold">{{ $a->alpa }}</td>

                  <!-- Tombol detail -->
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

<!-- ============================================================
     LAPORAN PELANGGARAN
============================================================ -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-danger">
        Laporan Pelanggaran
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

<!-- ============================================================
     LAPORAN PRESTASI
============================================================ -->
    <div class="mb-4 border rounded">
      <div class="section-title bg-success">
        Laporan Prestasi
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

            <!-- Loop data prestasi -->
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

<!-- ============================================================
     SCRIPT LOGOUT
     Menampilkan pop-up browser lalu submit form logout
============================================================ -->
<script>
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (confirm("Yakin ingin logout?")) {
        document.getElementById('logoutForm').submit();
    }
});
</script>

</body>
</html>
