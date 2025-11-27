<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Prestasi Siswa</title>

  <!-- Bootstrap & Icon CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Memuat file CSS dan JS dari Laravel Vite -->
  @vite([
      'resources/css/prestasi.css',
      'resources/js/app.js'
  ])
</head>

<body>

<!-- Navbar utama yang tetap berada di bagian atas halaman -->
<nav class="navbar navbar-dark bg-dark shadow-sm fixed-top">
  <div class="container-fluid d-flex justify-content-between align-items-center px-4">

    <!-- Menampilkan judul panel dan nama pengguna dari session -->
    <a class="navbar-brand fw-bold text-light d-flex align-items-center gap-2 mb-0" href="#">
      <span>Prestasi Siswa</span>
      <span class="text-light ms-2">| {{ session('nama') ?? 'Guru Wali' }}</span>
    </a>

    <!-- Menu navigasi kanan -->
    <div class="d-flex align-items-center gap-4">

      <!-- Link ke halaman input prestasi -->
      <a href="{{ route('prestasi.input') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.input') ? 'fw-bold text-white' : 'text-secondary' }}">
         Input Prestasi
      </a>

      <!-- Link ke halaman kategori prestasi -->
      <a href="{{ route('prestasi.kategori') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.kategori') ? 'fw-bold text-white' : 'text-secondary' }}">
         Kategori
      </a>

      <!-- Link ke halaman jenis prestasi -->
      <a href="{{ route('prestasi.jenis') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.jenis') ? 'fw-bold text-white' : 'text-secondary' }}">
         Jenis
      </a>

      <!-- Link ke halaman rekap prestasi -->
      <a href="{{ route('prestasi.rekap') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.rekap') ? 'fw-bold text-white' : 'text-secondary' }}">
         Laporan Prestasi
      </a>

      <!-- Tombol logout (GET karena rute logout menggunakan GET) -->
      <form id="logoutForm" method="GET" action="{{ route('logout') }}">
        <button type="button" id="logoutBtn" class="btn btn-outline-light btn-sm">
          Logout
        </button>
      </form>

    </div>
  </div>
</nav>


<!-- Tombol kembali ke menu wali kelas -->
<div class="container mt-5 mb-4" style="margin-top: 90px !important;">
  <a href="{{ route('kebutuhanwalikelas') }}" 
     class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
    <i class="bi bi-arrow-left-circle me-2"></i>
    Kembali ke Menu Wali Kelas
  </a>
</div>


<!-- Konten utama halaman -->
<div class="container py-5 mt-5">
  <div class="card shadow-sm p-4 bg-white">

    <!-- Judul form -->
    <h3 class="text-center mb-4 fw-bold">Input Prestasi Siswa</h3>

    <!-- Pesan notifikasi sukses atau error -->
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif


    <!-- Form untuk input data prestasi -->
    <form action="{{ route('prestasi.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Baris input kelas, jurusan, dan nama siswa -->
      <div class="row g-3 mb-3">

        <!-- Dropdown kelas -->
        <div class="col-md-3">
          <label class="form-label fw-semibold">Kelas</label>
          <select id="kelas" name="kelas" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($daftar_kelas as $k)
              <option value="{{ $k->nama_kelas }}" {{ ($kelasDipilih ?? '') == $k->nama_kelas ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Dropdown jurusan -->
        <div class="col-md-3">
          <label class="form-label fw-semibold">Jurusan</label>
          <select id="jurusan" name="jurusan" class="form-select" required>
            <option value="">Pilih Jurusan</option>
            <option value="IPA">IPA</option>
            <option value="IPS">IPS</option>
          </select>
        </div>

        <!-- Dropdown nama siswa, akan aktif setelah kelas dan jurusan dipilih -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nama Siswa</label>
          <select id="nama_siswa" name="NIS" class="form-select" disabled required>
            <option value="">Pilih kelas & jurusan dulu...</option>
          </select>
        </div>
      </div>


      <!-- Baris input jenis prestasi, tanggal, tahun ajar -->
      <div class="row g-3 mb-3">

        <!-- Dropdown jenis prestasi -->
        <div class="col-md-4">
          <label class="form-label fw-semibold">Jenis Prestasi</label>
          <select name="id_jenisprestasi" class="form-select" required>
            <option value="">Pilih Jenis Prestasi</option>
            @foreach($jenis as $j)
              <option value="{{ $j->id_jenisprestasi }}">{{ $j->nama_jenis }}</option>
            @endforeach
          </select>
        </div>

        <!-- Input tanggal prestasi -->
        <div class="col-md-4">
          <label class="form-label fw-semibold">Tanggal Prestasi</label>
          <input type="date" name="tanggal" class="form-control" required>
        </div>

        <!-- Dropdown tahun ajar -->
        <div class="col-md-4">
          <label class="form-label fw-semibold">Tahun Ajar</label>
          <select name="tahunAjar" class="form-select" required>
            <option value="">-- Pilih Tahun Ajar --</option>
            @foreach($daftar_tahunAjar as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
        </div>
      </div>


      <!-- Upload file bukti prestasi -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Upload Bukti Prestasi (Gambar / PDF)</label>
        <input type="file" id="file_prestasi" name="file_prestasi" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        <small class="text-muted">Format yang diperbolehkan: JPG, PNG, atau PDF (maks 2MB)</small>

        <!-- Preview file yang diupload -->
        <div class="preview-container" id="preview-container"></div>
      </div>

      <!-- Tombol submit -->
      <div class="text-center">
        <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Data</button>
      </div>
    </form>
  </div>
</div>


<!-- Script untuk preview file dan mengisi dropdown siswa -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

  // Menampilkan preview untuk file gambar atau PDF
  $('#file_prestasi').on('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = $('#preview-container');
    previewContainer.empty();

    if (file) {
      const fileType = file.type;
      const fileSize = file.size / 1024 / 1024;

      if (fileSize > 2) {
        alert('Ukuran file maksimal 2MB');
        $(this).val('');
        return;
      }

      if (fileType.startsWith('image/')) {
        const img = $('<img>').attr('src', URL.createObjectURL(file));
        previewContainer.append(img);
      } else if (fileType === 'application/pdf') {
        const iframe = $('<iframe>').attr('src', URL.createObjectURL(file));
        previewContainer.append(iframe);
      }
    }
  });

  // Memuat nama siswa sesuai kelas dan jurusan
  $('#kelas, #jurusan').on('change', function() {
    const kelas = $('#kelas').val();
    const jurusan = $('#jurusan').val();

    if (kelas && jurusan) {
      $('#nama_siswa').prop('disabled', false).html('<option>Memuat data siswa...</option>');

      $.get(`/get-siswa?kelas=${kelas}&jurusan=${jurusan}`, function(data) {
        let options = '<option value="">Pilih Nama Siswa</option>';

        if (data.length > 0) {
          data.forEach(siswa => {
            options += `<option value="${siswa.NIS}">${siswa.nama_siswa}</option>`;
          });
        } else {
          options += '<option value="">Tidak ada siswa ditemukan</option>';
        }

        $('#nama_siswa').html(options);
      }).fail(function() {
        $('#nama_siswa').html('<option value="">Gagal memuat data siswa</option>');
      });

    } else {
      $('#nama_siswa').prop('disabled', true).html('<option>Pilih kelas & jurusan dulu...</option>');
    }
  });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script konfirmasi logout -->
<script>
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (confirm("Yakin ingin logout?")) {
        document.getElementById('logoutForm').submit();
    }
});
</script>

</body>
</html>
