<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/siswa.css', 'resources/js/app.js'])
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
  </style>
</head>

<body class="bg-light">

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">🎓 Edit Data Siswa</a>
      <div class="ms-auto">
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-light btn-sm">
          ⬅️ Kembali
        </a>
      </div>
    </div>
  </nav>

  <!-- 🔹 Konten -->
  <div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
      <div class="card-header bg-primary text-white text-center fw-bold">
        ✏️ Form Edit Data Siswa
      </div>

      <div class="card-body">
        <form action="{{ route('siswa.update', $siswa->NIS) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label fw-bold">NIS</label>
            <input type="text" class="form-control" value="{{ $siswa->NIS }}" readonly>
          </div>

          <div class="mb-3">
            <label for="nama_siswa" class="form-label fw-bold">Nama Siswa</label>
            <input type="text" id="nama_siswa" name="nama_siswa" class="form-control"
                   value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
          </div>

          <div class="mb-3">
            <label for="kelas_siswa" class="form-label fw-bold">Kelas</label>
            <select id="kelas_siswa" name="kelas_siswa" class="form-select" required>
              @foreach($kelas as $k)
                <option value="{{ $k->nama_kelas }}"
                  {{ $siswa->kelas_siswa == $k->nama_kelas ? 'selected' : '' }}>
                  {{ $k->nama_kelas }} ({{ $k->tahunAjar }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="jurusan_siswa" class="form-label fw-bold">Jurusan</label>
            <select id="jurusan_siswa" name="jurusan_siswa" class="form-select" required>
              <option value="IPA" {{ $siswa->jurusan_siswa == 'IPA' ? 'selected' : '' }}>IPA</option>
              <option value="IPS" {{ $siswa->jurusan_siswa == 'IPS' ? 'selected' : '' }}>IPS</option>
            </select>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-success px-4">💾 Simpan Perubahan</button>
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary px-4">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
