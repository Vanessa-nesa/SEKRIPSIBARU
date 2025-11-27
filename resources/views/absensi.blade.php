<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Absensi Wali Kelas</title>

  <!-- Bootstrap CSS untuk gaya dan layout -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom CSS buatan sendiri -->
  @vite(['resources/css/absensi.css'])
</head>


<body>

<!-- 🔹 NAVBAR (Header atas halaman) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    
    <!-- 🔹 Judul Panel + Nama Guru -->
    <div class="d-flex align-items-center">
      <a class="navbar-brand fw-bold mb-0 h5 text-white">Absensi Wali Kelas Panel</a>
      <span class="text-white">| {{ session('nama') ?? 'Guru Wali' }}</span>
    </div>

    <!-- 🔹 Menu navigasi dalam navbar -->
    <ul class="navbar-nav d-flex align-items-center">

      <!-- 🔸 Menu input absensi -->
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
          Input Absensi
        </a>
      </li>

      <!-- 🔸 Menu laporan absensi -->
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('wali.rekapabsensi') ? 'active' : '' }}" href="{{ route('wali.rekapabsensi') }}">
          Laporan Absensi
        </a>
      </li>

      <!-- 🔸 Tombol Logout -->
      <!-- 
           Catatan penting:
           Tombol disimpan di <button>, sementara form logout disembunyikan.
           Ini mencegah HTML error yang bisa membuat button lain ikut tergabung
           ke dalam form logout (penyebab tombol kembali ikut logout).
      -->
      <li class="nav-item ms-3 d-flex align-items-center">
        <!-- Tombol logout yang memicu konfirmasi -->
        <button type="button" id="logoutBtn" class="btn btn-outline-light btn-sm">
            Logout
        </button>

        <!-- Form logout yang di-submit lewat JavaScript -->
        <form id="logoutForm" method="GET" action="{{ route('logout') }}" style="display: none;"></form>
      </li>

    </ul>
  </div>
</nav>


<!-- Tombol kembali ke menu wali kelas -->
<div class="mb-4">
  <a href="{{ route('kebutuhanwalikelas') }}" class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
    <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Wali Kelas
  </a>
</div>


<!-- BAGIAN UTAMA HALAMAN -->
<div class="container py-5">
  <h3 class="text-center mb-4">Absensi Wali Kelas</h3>

  <!-- Pesan sukses setelah simpan absensi -->
  @if(session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
  @endif

  <!-- 🔸 FORM FILTER: Kelas, Jurusan, Tahun Ajar -->
  <form method="GET" action="{{ route('absensi.index') }}" class="row justify-content-center mb-4">

    <!-- Pilih kelas -->
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

    <!-- Pilih jurusan -->
    <div class="col-md-3">
      <label class="form-label fw-bold">Jurusan</label>
      <select name="jurusan" class="form-select" required>
        <option value="">-- Pilih Jurusan --</option>
        @foreach(['IPA','IPS'] as $j)
          <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
        @endforeach
      </select>
    </div>

    <!-- Pilih tahun ajar -->
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

    <!-- Tombol tampilkan data siswa -->
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
    </div>
  </form>


  <!-- 🔹 FORM INPUT ABSENSI -->
  @if($siswa->count() > 0)
  <form method="POST" action="{{ route('absensi.store') }}">
    @csrf

    <!-- Hidden input untuk menyimpan data kelas & tahun ajar -->
    <input type="hidden" name="kelas" value="{{ $kelas }}">
    <input type="hidden" name="tahunAjar" value="{{ $tahunAjar }}">

    <!-- Input tanggal absensi -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-3">
        <label class="form-label fw-bold">Tanggal Absensi</label>
        <input type="date" name="tanggal" class="form-control" required>
      </div>
    </div>

    <!-- TABEL ABSENSI -->
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

          <!-- Hidden NIS untuk identifikasi -->
          <td>
            <input type="hidden" name="NIS[]" value="{{ $s->NIS }}">

            <!-- Dropdown status absensi -->
            <select name="status[]" class="form-select">
              @foreach(['Hadir','Izin','Sakit','Alpha'] as $st)
                <option value="{{ $st }}" 
                  {{ optional($absensi->firstWhere('NIS', $s->NIS))->status == $st ? 'selected' : '' }}>
                  {{ $st }}
                </option>
              @endforeach
            </select>
          </td>

          <!-- Keterangan -->
          <td>
            <input type="text" name="keterangan[]" class="form-control"
              value="{{ optional($absensi->firstWhere('NIS', $s->NIS))->keterangan }}">
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Tombol simpan -->
    <div class="text-center">
      <button type="submit" class="btn btn-success mt-3">Simpan Absensi</button>
    </div>
  </form>
  @endif
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Data kelas & tahun ajar versi JSON -->
<div id="kelasDataHolder" data-kelas='@json(App\Models\Kelas::select("nama_kelas", "tahunAjar")->get())' style="display:none"></div>


<!-- 🔹 Script untuk membuat dropdown Tahun Ajar mengikuti pilihan Kelas -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.getElementById('kelasSelect');
    const tahunAjarSelect = document.getElementById('tahunAjarSelect');

    const kelasDataElem = document.getElementById('kelasDataHolder');
    const kelasData = kelasDataElem ? JSON.parse(kelasDataElem.getAttribute('data-kelas') || '[]') : [];

    kelasSelect.addEventListener('change', function() {
        const selectedKelas = this.value;

        tahunAjarSelect.innerHTML = '<option value="">-- Pilih Tahun Ajar --</option>';

        // Filter data tahun ajar berdasarkan kelas
        kelasData.forEach(item => {
            if (item.nama_kelas === selectedKelas) {
                const opt = document.createElement('option');
                opt.value = item.tahunAjar;
                opt.textContent = item.tahunAjar;
                tahunAjarSelect.appendChild(opt);
            }
        });

        tahunAjarSelect.disabled = selectedKelas === '';
    });
});
</script>


<!-- 🔹 Script Logout dengan popup konfirmasi browser -->
<script>
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (confirm("Yakin ingin logout?")) {
        document.getElementById('logoutForm').submit();
    }
});
</script>


</body>
</html>
