<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Import Data Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  @vite(['resources/css/siswa.css', 'resources/js/app.js'])
</head>

<body class="bg-light">

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid d-flex align-items-center">
      <!-- Judul Panel -->
      <div class="d-flex align-items-center">
        <a class="navbar-brand fw-bold" href="#">Import Data Siswa Panel tes</a>
        <span>| {{ session('nama') ?? 'Admin' }}</span>
      </div>

      <!-- Logout Button -->
      <div class="ms-auto">
        <a class="btn btn-outline-light btn-sm" href="{{ route('logout') }}" onclick="return confirm('Yakin ingin logout?')">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- 🔙 Tombol Kembali ke Menu Wali Kelas -->
<div class="mb-4">
  <a href="{{ route('kebutuhanwalikelas') }}" class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
    <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Wali Kelas
  </a>
</div>

  <!-- 🔹 Konten Utama -->
  <div class="container py-5">
    <h2 class="text-center mb-4">Import Data Siswa dari Excel</h2>

    {{-- Pesan sukses / error --}}
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
          <h5 class="text-center mb-3">Pilih Kelas & Jurusan</h5>

          <div class="row justify-content-center">
            <!-- Dropdown Kelas -->
            <div class="col-md-3">
              <label for="kelas" class="form-label fw-bold">Kelas</label>
              <select name="nama_kelas" id="kelas" class="form-select" required>
                <option value="" disabled selected>-- Pilih Kelas --</option>
                @foreach($kelas->unique('nama_kelas') as $k)
                  <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                @endforeach
              </select>
            </div>

            <!-- Dropdown Tahun Ajar -->
            <div class="col-md-3">
              <label for="tahunAjar" class="form-label fw-bold">Tahun Ajar</label>
              <select name="tahunAjar" id="tahunAjar" class="form-select" required>
                <option value="" disabled selected>-- Pilih Tahun Ajar --</option>
                @foreach($kelas->unique('tahunAjar') as $t)
                  <option value="{{ $t->tahunAjar }}">{{ $t->tahunAjar }}</option>
                @endforeach
              </select>
            </div>

            <!-- Jurusan -->
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

      <!-- Upload Excel -->
      <div class="input-group mb-4">
        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
        <button type="submit" class="btn btn-primary">Upload Excel</button>
      </div>
    </form>

    <!-- 🔹 Tabel Data -->
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h5 class="mb-0">Data Siswa Tersimpan</h5>
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
                <a href="{{ route('siswa.edit', $s->NIS) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('siswa.delete', $s->NIS) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger btn-sm">Hapus</button>
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
