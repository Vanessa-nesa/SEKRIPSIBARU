<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Pelanggaran Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .table th, .table td { vertical-align: middle; }
    .navbar-brand { font-weight: 600; }
  </style>
</head>
<body>

  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Sistem Pelanggaran</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a href="{{ route('pelanggaran.rekap') }}" class="nav-link">📊 Rekap Pelanggaran</a>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-light ms-2">🚪 Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- 🔹 Konten Utama -->
  <div class="container py-5">
    <h3 class="text-center mb-4">📋 Input Pelanggaran Siswa</h3>

    {{-- Pesan sukses/error --}}
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- 🔹 Filter Tahun Ajaran, Kelas & Jurusan --}}
  {{-- 🔹 Filter Kelas, Jurusan, dan Tahun Ajaran --}}
<form method="GET" action="{{ route('pelanggaran.index') }}" class="row justify-content-center mb-4">
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

  <div class="col-md-3">
    <label class="form-label fw-bold">Jurusan</label>
    <select name="jurusan" class="form-select" required>
      <option value="">-- Pilih Jurusan --</option>
      @foreach(['IPA','IPS'] as $j)
        <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-bold">Tahun Ajaran</label>
    <select name="tahunAjar" class="form-select" required>
      <option value="">-- Pilih Tahun Ajaran --</option>
      @foreach($daftar_tahunAjar as $t)
        <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-2 d-flex align-items-end">
    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
  </div>
</form>



    {{-- 🔹 Form Input Pelanggaran --}}
   @if($siswa->count() > 0)
  <form method="POST" action="{{ route('pelanggaran.store') }}">
    @csrf
    {{-- kirimkan tahun ajaran agar bisa disimpan --}}
    <input type="hidden" name="tahunAjar" value="{{ request('tahunAjar') }}">

    <div class="row mb-3 justify-content-center">

          <div class="col-md-3 col-12">
            <label class="form-label fw-bold">Tanggal Pelanggaran <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center shadow-sm">
            <thead class="table-secondary">
              <tr>
                <th>Nama Siswa</th>
                <th>Jenis Pelanggaran</th>
                <th>Kategori</th>
                <th>Sudah Ke-</th>
                <th>Catatan / Notes (Opsional)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($siswa as $index => $s)
              <tr>
                <input type="hidden" name="data[{{ $index }}][NIS]" value="{{ $s->NIS }}">
                <td class="fw-semibold text-start ps-3">{{ $s->nama_siswa }}</td>

                <td>
                  <select name="data[{{ $index }}][id_jenispelanggaran]" 
                          class="form-select form-select-sm jenis-select" 
                          data-index="{{ $index }}">
                    <option value="">-- Pilih Pelanggaran --</option>
                    @foreach($jenispelanggaran as $jp)
                      <option value="{{ $jp->id_jenispelanggaran }}" 
                              data-kategori="{{ $jp->kategori->nama_kategori ?? '-' }}">
                        {{ $jp->nama_pelanggaran }}
                      </option>
                    @endforeach
                  </select>
                </td>

                <td>
                  <input type="text" 
                         name="data[{{ $index }}][kategori]" 
                         class="form-control form-control-sm kategori-input" 
                         readonly placeholder="-" />
                </td>

                <td>
                  <input type="number" name="data[{{ $index }}][jumlah]" 
                         class="form-control form-control-sm" 
                         min="1" placeholder="Ke-berapa">
                </td>

                <td>
                  <input type="text" name="data[{{ $index }}][notes]" 
                         class="form-control form-control-sm" 
                         placeholder="Catatan tambahan...">
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">💾 Simpan</button>
        </div>
      </form>
    @else
      <div class="alert alert-warning text-center">
        Pilih tahun ajaran, kelas, dan jurusan terlebih dahulu untuk menampilkan daftar siswa.
      </div>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- 🔸 Script untuk update kategori otomatis --}}
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll(".jenis-select").forEach(select => {
        select.addEventListener("change", e => {
          const kategori = e.target.selectedOptions[0].getAttribute("data-kategori") || "-";
          const row = e.target.closest("tr");
          const kategoriInput = row.querySelector(".kategori-input");
          if (kategoriInput) kategoriInput.value = kategori;
        });
      });
    });
  </script>
</body>
</html>
