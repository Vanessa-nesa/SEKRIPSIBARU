<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Absensi Wali Kelas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
    .nav-link.active { color: #0d6efd !important; font-weight: bold; }
  </style>
</head>

<body>
  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('absensi.index') }}">📋 Absensi Wali Kelas</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}" href="{{ route('absensi.index') }}">📝 Input Absensi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('absensi.rekap') ? 'active' : '' }}" href="{{ route('absensi.rekap') }}">📊 Rekap Absensi</a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item d-flex align-items-center me-3 text-white fw-semibold">
            👤 {{ Auth::user()->name ?? 'Guru Wali' }}
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="btn btn-outline-light btn-sm">🚪 Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- 🔹 Main Content -->
  <div class="container py-5">
    <h3 class="text-center mb-4">📋 Absensi Wali Kelas</h3>

    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- 🔸 Filter Kelas & Tahun Ajar --}}
    <form method="GET" action="{{ route('absensi.index') }}" class="row justify-content-center mb-4">
      {{-- Kelas --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" id="kelasSelect" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas ?? '') == $k->nama_kelas ? 'selected' : '' }}>
              {{ $k->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Jurusan --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">-- Pilih Jurusan --</option>
          @foreach(['IPA','IPS'] as $j)
            <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>

      {{-- Tahun Ajar --}}
      <div class="col-md-3">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" id="tahunAjarSelect" class="form-select" required>
          <option value="">-- Pilih Tahun Ajar --</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>
              {{ $t }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    {{-- 🔹 Input Absensi --}}
    @if($siswa->count() > 0)
    <form method="POST" action="{{ route('absensi.store') }}">
      @csrf
      <input type="hidden" name="kelas" value="{{ $kelas }}">
      <input type="hidden" name="tahunAjar" value="{{ $tahunAjar }}">

      <div class="row justify-content-center mb-4">
        <div class="col-md-3">
          <label class="form-label fw-bold">Tanggal Absensi</label>
          <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
        </div>
      </div>

      <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>Status</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($siswa as $index => $s)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $s->nama_siswa }}</td>
            <td>{{ $s->kelas_siswa }}</td>
            <td>{{ $s->jurusan_siswa }}</td>
            <td>
              <input type="hidden" name="NIS[]" value="{{ $s->NIS }}">
              <select name="status[]" class="form-select">
                @foreach(['Hadir','Izin','Sakit','Alpha'] as $st)
                  <option value="{{ $st }}" 
                    {{ optional($absensi->firstWhere('NIS', $s->NIS))->status == $st ? 'selected' : '' }}>
                    {{ $st }}
                  </option>
                @endforeach
              </select>
            </td>
            <td>
              <input type="text" name="keterangan[]" class="form-control"
                value="{{ optional($absensi->firstWhere('NIS', $s->NIS))->keterangan }}">
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="text-center">
        <button type="submit" class="btn btn-success mt-3">💾 Simpan Absensi</button>
      </div>
    </form>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- 🔹 Script Dinamis Tahun Ajar -->
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const kelasSelect = document.getElementById('kelasSelect');
    const tahunAjarSelect = document.getElementById('tahunAjarSelect');

    // Semua data kelas + tahun ajar dari database
    const kelasData = @json(\App\Models\Kelas::select('nama_kelas', 'tahunAjar')->get());

    kelasSelect.addEventListener('change', function () {
      const selectedKelas = this.value;

      // Kosongkan dropdown tahun ajar
      tahunAjarSelect.innerHTML = '<option value="">-- Pilih Tahun Ajar --</option>';

      // Filter tahun ajar berdasarkan kelas yang dipilih
      kelasData.forEach(item => {
        if (item.nama_kelas === selectedKelas) {
          const opt = document.createElement('option');
          opt.value = item.tahunAjar;
          opt.textContent = item.tahunAjar;
          tahunAjarSelect.appendChild(opt);
        }
      });

      // Optional UX: disable jika belum pilih kelas
      tahunAjarSelect.disabled = selectedKelas === '';
    });
  });
  </script>
</body>
</html>
