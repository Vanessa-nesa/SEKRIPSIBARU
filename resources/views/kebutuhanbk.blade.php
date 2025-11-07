<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilihan Kebutuhan Guru BK</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/kebutuhanbk.css', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex flex-column min-vh-100">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Pilihan Fitur Guru BK</a>
      <div class="d-flex align-items-center">
        @if(session('username'))
          <span class="text-white me-3 fw-semibold">👤 {{ session('username') }}</span>
        @endif
        <a href="{{ route('logout') }}" class="btn btn-light btn-sm fw-bold text-primary">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Konten Utama -->
  <div class="container d-flex justify-content-center align-items-center flex-grow-1">
    <div class="card shadow p-5 text-center" style="max-width: 400px; border-radius: 15px;">
      <h3 class="fw-bold mb-4">Pilih Kebutuhan</h3>
      <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('bimbingan') }}" class="btn btn-primary px-4 py-2 fw-semibold">Bimbingan Konseling</a>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
