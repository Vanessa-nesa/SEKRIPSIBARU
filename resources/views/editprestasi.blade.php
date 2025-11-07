<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Prestasi Siswa</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      border-radius: 12px;
    }
    .btn {
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
      <a class="navbar-brand fw-bold" href="#">🏅 Prestasi Siswa</a>
      <a href="#" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </nav>

  <!-- 🔹 Konten -->
  <div class="container mt-5">
    <div class="card p-4 shadow-sm">
      <h4 class="mb-4 fw-bold text-center">
        <i class="bi bi-pencil-square text-warning"></i> Edit Prestasi Siswa
      </h4>

      <form action="#" method="POST" enctype="multipart/form-data">
        <!-- Jenis Prestasi -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Jenis Prestasi</label>
          <select name="id_jenisprestasi" class="form-select" required>
            <option value="">Pilih Jenis Prestasi</option>
            <option value="1">Akademik</option>
            <option value="2">Non-Akademik</option>
            <option value="3">Seni</option>
          </select>
        </div>

        <!-- Tanggal -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Tanggal</label>
          <input type="date" name="tanggal" class="form-control" value="2025-11-06" required>
        </div>

        <!-- Tahun Ajar -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Tahun Ajar</label>
          <select name="tahunAjar" class="form-select" required>
            <option value="2024/2025">2024/2025</option>
            <option value="2025/2026" selected>2025/2026</option>
            <option value="2026/2027">2026/2027</option>
          </select>
        </div>

        <!-- Ganti Bukti -->
        <div class="mb-4">
          <label class="form-label fw-semibold">Ganti Bukti (Opsional)</label>
          <input type="file" name="file_prestasi" class="form-control">
          <p class="mt-2 text-muted small">
            File sekarang: 
            <a href="#" target="_blank" class="text-decoration-none text-primary">contoh_bukti.pdf</a>
          </p>
        </div>

        <!-- Tombol -->
<div class="text-center">
  <button type="submit" class="btn btn-primary px-4 me-2">
    <i class="bi bi-save"></i> Simpan Perubahan
  </button>
  <a href="{{ route('prestasi.rekap') }}" class="btn btn-secondary px-4">
    <i class="bi bi-arrow-left-circle"></i> Kembali
  </a>
</div>


      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
