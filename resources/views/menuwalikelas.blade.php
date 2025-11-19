<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Wali Kelas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/menu.css', 'resources/js/app.js'])
</head>
<body>

    <!-- 🔹 Navbar --> 
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="#">
                Wali Kelas Panel - {{ session('nama') }}
            </a>
            <div class="ms-auto">
                <form action="{{ route('logout') }}" method="GET">
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- 🔹 Konten Utama -->
    <div class="menu-container d-flex justify-content-center align-items-center min-vh-100">
        <div class="menu-card text-center shadow p-5 rounded bg-white">
            <h3 class="fw-bold mb-3">Selamat Datang, {{ session('nama') }}</h3>
            <p class="text-muted mb-4">Silakan pilih fitur yang ingin Anda kelola:</p>

            {{-- Pesan sukses / error --}}
            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mt-3">{{ session('error') }}</div>
            @endif

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <a href="{{ route('absensi.index') }}" class="btn btn-fitur btn-primary w-100 py-3 fw-semibold">
                        Input Absensi
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('siswa.index') }}" class="btn btn-fitur btn-danger w-100 py-3 fw-semibold">
                        Input Data Siswa
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('prestasi.index') }}" class="btn btn-fitur btn-danger w-100 py-3 fw-semibold">
                        Input Prestasi Siswa
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('kelas.index') }}" class="btn btn-fitur btn-primary w-100 py-3 fw-semibold">
                        Input Kelas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
