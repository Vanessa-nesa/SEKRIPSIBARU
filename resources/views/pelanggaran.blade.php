<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Pelanggaran Siswa</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Ikon Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Memuat file CSS dan JS dari Vite -->
  @vite([
      'resources/css/pelanggaran.css',
      'resources/js/app.js'
  ])
</head>
<body>

  <!-- Navbar utama aplikasi -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

      <!-- Menampilkan judul aplikasi dan nama user -->
      <a class="navbar-brand d-flex align-items-center" href="#">
        Sistem Pelanggaran
        @if(session('nama'))
            <span class="ms-2 fw-bold text-white">| {{ session('nama') }}</span>
        @endif
      </a>

      <!-- Tombol menu responsif -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu navigasi di kanan navbar -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

          <!-- Link menuju halaman laporan pelanggaran -->
          <li class="nav-item">
            <a href="{{ route('pelanggaran.rekap') }}" class="nav-link">Laporan Pelanggaran</a>
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

<!-- Tombol kembali ke menu Guru BK -->
<div class="container mt-5 mb-4">
    <a href="{{ route('kebutuhanbk') }}" 
       class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
      <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Guru BK
    </a>
</div>

  <!-- Kontainer utama -->
  <div class="container py-5">

    <!-- Judul halaman -->
    <h3 class="text-center mb-4">Input Pelanggaran Siswa</h3>

    <!-- Menampilkan pesan sukses atau error jika ada -->
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <!-- Filter data siswa berdasarkan kelas, jurusan, dan tahun ajaran -->
    <form method="GET" action="{{ route('pelanggaran.index') }}" class="row justify-content-center g-2 mb-4 mt-4 pt-3">

      <!-- Filter kelas -->
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

      <!-- Filter jurusan -->
      <div class="col-md-3">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">-- Pilih Jurusan --</option>
          @foreach(['IPA','IPS'] as $j)
            <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>
              {{ $j }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filter tahun ajaran -->
      <div class="col-md-3">
        <label class="form-label fw-bold">Tahun Ajaran</label>
        <select name="tahunAjar" class="form-select" required>
          <option value="">-- Pilih Tahun Ajaran --</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>
              {{ $t }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Tombol untuk memproses filter -->
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>

    </form>

    <!-- Menampilkan form input pelanggaran hanya jika siswa ditemukan -->
    @if($siswa->count() > 0)

    <form method="POST" action="{{ route('pelanggaran.store') }}">
      @csrf

      <!-- Menyimpan tahun ajaran agar ikut terkirim -->
      <input type="hidden" name="tahunAjar" value="{{ request('tahunAjar') }}">

      <!-- Input tanggal pelanggaran -->
      <div class="row mb-3 justify-content-center">
        <div class="col-md-3 col-12">
          <label class="form-label fw-bold">Tanggal Pelanggaran <span class="text-danger">*</span></label>
          <input type="date" name="tanggal" class="form-control" required>
        </div>
      </div>

      <!-- Tabel input pelanggaran untuk setiap siswa -->
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm">
          <thead class="table-secondary">
            <tr>
              <th>Nama Siswa</th>
              <th>Jenis Pelanggaran</th>
              <th>Kategori</th>
              <th>Sudah Ke-</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>

            <!-- Loop untuk setiap siswa -->
            @foreach($siswa as $index => $s)
            <tr>

              <!-- Kirim NIS siswa -->
              <input type="hidden" name="data[{{ $index }}][NIS]" value="{{ $s->NIS }}">

              <!-- Nama siswa -->
              <td class="fw-semibold text-start ps-3">{{ $s->nama_siswa }}</td>

              <!-- Pilihan jenis pelanggaran, kategori akan terisi otomatis -->
              <td>
                <select 
                  name="data[{{ $index }}][id_jenispelanggaran]"
                  class="form-select form-select-sm jenis-select"
                  data-index="{{ $index }}"
                >
                  <option value="">-- Pilih Pelanggaran --</option>

                  @foreach($jenispelanggaran as $jp)
                    <option 
                      value="{{ $jp->id_jenispelanggaran }}" 
                      data-kategori="{{ $jp->kategori->nama_kategori ?? '-' }}"
                    >
                      {{ $jp->nama_pelanggaran }}
                    </option>
                  @endforeach
                </select>
              </td>

              <!-- Input kategori (readonly) -->
              <td>
                <input type="text" 
                       class="form-control form-control-sm kategori-input" 
                       readonly placeholder="-">
              </td>

              <!-- Jumlah pelanggaran -->
              <td>
                <input type="number" 
                       name="data[{{ $index }}][jumlah]"
                       class="form-control form-control-sm"
                       min="1"
                       placeholder="Ke-berapa">
              </td>

              <!-- Catatan tambahan -->
              <td>
                <input type="text" 
                       name="data[{{ $index }}][notes]" 
                       class="form-control form-control-sm"
                       placeholder="Catatan tambahan...">
              </td>

            </tr>
            @endforeach

          </tbody>
        </table>
      </div>

      <!-- Tombol simpan -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">Simpan</button>
      </div>

    </form>

    @else

      <!-- Pesan jika belum memilih filter -->
      <div class="alert alert-warning text-center">
        Pilih tahun ajaran, kelas, dan jurusan terlebih dahulu untuk menampilkan daftar siswa.
      </div>

    @endif

  </div>

  <!-- Script Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script untuk otomatis mengisi kategori berdasarkan jenis pelanggaran yg dipilih -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {

      // Mencari semua dropdown jenis pelanggaran
      document.querySelectorAll(".jenis-select").forEach(select => {

        // Ketika dropdown berubah
        select.addEventListener("change", e => {

          // Ambil nilai kategori dari attribute data-kategori
          const kategori = e.target.selectedOptions[0].getAttribute("data-kategori") || "-";

          // Cari baris tabel tempat dropdown berada
          const row = e.target.closest("tr");

          // Isi input kategori pada baris tersebut
          const kategoriInput = row.querySelector(".kategori-input");
          kategoriInput.value = kategori;
        });
      });

    });
  </script>

</body>
</html>
