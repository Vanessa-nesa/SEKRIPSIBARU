<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menu Guru BK</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="{{ asset('css/menugurubk.css') }}" rel="stylesheet">
</head>
<body>

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">👩‍🏫 Guru BK Panel</a>
      <div class="ms-auto">
        <form action="{{ route('logout') }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin logout?')">
          @csrf
          <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <!-- 🔹 Konten Utama -->
  <div class="menu-container">
    <div class="card-menu">
      <h3 class="fw-bold mb-4">Selamat Datang, {{ session('nama') }}</h3>
      <p class="text-muted mb-4">Silakan pilih fitur yang ingin Anda kelola:</p>

      <div class="d-grid gap-3">
        <a href="{{ route('bimbingan') }}" class="btn btn-menu btn-bk">📘 Bimbingan Konseling</a>
        <a href="{{ route('pelanggaran.index') }}" class="btn btn-menu btn-pelanggaran">⚠️ Pelanggaran Siswa</a>
      </div>
    </div>
  </div>

  <footer>
    &copy; {{ date('Y') }} Sistem Pemantauan Sekolah — Guru BK
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
