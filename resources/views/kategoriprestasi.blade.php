<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kategori Prestasi</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/prestasi.css') }}" rel="stylesheet">
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">Prestasi Siswa</a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center gap-3">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.input') ? 'active' : '' }}" href="{{ route('prestasi.input') }}">Input Prestasi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.kategori') ? 'active' : '' }}" href="{{ route('prestasi.kategori') }}">Kategori</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.jenis') ? 'active' : '' }}" href="{{ route('prestasi.jenis') }}">Jenis</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.rekap') ? 'active' : '' }}" href="{{ route('prestasi.rekap') }}">Rekap Prestasi</a>
        </li>
        <li class="nav-item">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Konten -->
<div class="container py-5 mt-5">
  <div class="card shadow-sm p-4 bg-white">
    <h3 class="text-center mb-4 fw-bold">Input Kategori Prestasi</h3>

    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="{{ route('kategori.store') }}" method="POST" class="mb-4">
      @csrf
      <div class="row justify-content-center">
        <div class="col-md-4">
          <label class="fw-semibold mb-2">Nama Kategori</label>
          <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Akademik" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-primary w-100">Tambah</button>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle shadow-sm">
        <thead class="table-secondary">
          <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kategori as $i => $k)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $k->nama_kategori }}</td>
              <td>
                <form action="{{ route('kategori.destroy', $k->id_kategoriprestasi) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-sm">🗑️ Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-muted">Belum ada kategori ditambahkan</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
