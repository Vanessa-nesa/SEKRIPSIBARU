<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Import Data Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/siswa.css', 'resources/js/app.js'])
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
    .table th { background-color: #cfe2ff; }
  </style>
</head>

<body class="bg-light">

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">🎓 Data Siswa</a>

      <div class="d-flex align-items-center ms-auto">
        <a class="btn btn-outline-danger btn-sm d-flex align-items-center"
           href="{{ route('logout') }}" onclick="return confirm('Yakin ingin logout?')">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
               class="bi bi-box-arrow-right me-1" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M6 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v3h-1V3H7v10h6v-3h1v3a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V3z"/>
            <path fill-rule="evenodd"
                  d="M11.146 8.354a.5.5 0 0 1 0-.708L13.793 5H9.5a.5.5 0 0 1 0-1h5.5a.5.5 0 0 1 .5.5v5.5a.5.5 0 0 1-1 0V6.707l-2.647 2.647a.5.5 0 0 1-.708 0z"/>
          </svg>
          Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- 🔹 Konten Utama -->
  <div class="container py-5">
    <h2 class="text-center mb-4">📂 Import Data Siswa dari Excel</h2>

    {{-- 🔸 Pesan sukses / error --}}
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- 🔹 Form Upload Excel --}}
    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
      @csrf
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="text-center mb-3">🎓 Pilih Kelas & Jurusan</h5>

          <div class="row justify-content-center">
            <!-- 🔹 Dropdown Kelas -->
            <div class="col-md-3">
              <label for="kelas" class="form-label fw-bold">Kelas</label>
              <select name="nama_kelas" id="kelas" class="form-select" required>
                <option value="" disabled selected>-- Pilih Kelas --</option>
                @foreach($kelas->unique('nama_kelas') as $k)
                  <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                @endforeach
              </select>
            </div>

            <!-- 🔹 Dropdown Tahun Ajar -->
            <div class="col-md-3">
              <label for="tahunAjar" class="form-label fw-bold">Tahun Ajar</label>
              <select name="tahunAjar" id="tahunAjar" class="form-select" required>
                <option value="" disabled selected>-- Pilih Tahun Ajar --</option>
                @foreach($kelas->unique('tahunAjar') as $t)
                  <option value="{{ $t->tahunAjar }}">{{ $t->tahunAjar }}</option>
                @endforeach
              </select>
            </div>

            <!-- 🔹 Jurusan -->
            <div class="col-md-3">
              <label for="jurusan" class="form-label fw-bold">Jurusan</label>
              <select id="jurusan" name="jurusan_siswa" class="form-select" required>
                <option value="" selected disabled>-- Pilih Jurusan --</option>
                <option value="IPA">IPA</option>
                <option value="IPS">IPS</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- 🔹 Upload Excel -->
      <div class="input-group mb-4">
        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
        <button type="submit" class="btn btn-primary">📤 Upload Excel</button>
      </div>
    </form>

    <!-- 🔹 Tabel Data -->
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h5 class="mb-0">📋 Data Siswa Tersimpan</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle mb-0">
          <thead class="table-primary">
            <tr>
              <th>NIS</th>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($data as $s)
            <tr>
              <td>{{ $s->NIS }}</td>
              <td>{{ $s->nama_siswa }}</td>
              <td>{{ $s->kelas_siswa }}</td>
              <td>{{ $s->jurusan_siswa }}</td>
              <td>
                <a href="{{ route('siswa.edit', $s->NIS) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                <form action="{{ route('siswa.delete', $s->NIS) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger btn-sm">🗑️ Hapus</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-muted">Belum ada data siswa</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
