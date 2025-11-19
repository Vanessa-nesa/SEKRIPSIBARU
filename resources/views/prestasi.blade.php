<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Prestasi Siswa</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/prestasi.css', 'resources/js/app.js'])
  <style>
    body { background-color: #f8f9fa; }
    .preview-container img {
      max-width: 180px;
      border-radius: 10px;
      margin-top: 10px;
      border: 1px solid #ddd;
    }
    .preview-container iframe {
      width: 100%;
      height: 300px;
      border: 1px solid #ccc;
      margin-top: 10px;
    }
        .btn-kembali {
    background-color: #343a40;
    color: #fff;
    padding: 8px 16px;
    border-radius: 8px;
    transition: 0.2s;
}
.btn-kembali:hover {
    background-color: #23272b;
    color: #fff;
}
  </style>
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-dark bg-dark shadow-sm fixed-top">
  <div class="container-fluid d-flex justify-content-between align-items-center px-4">
    <!-- Logo kiri -->
    <a class="navbar-brand fw-bold text-light d-flex align-items-center gap-2 mb-0" href="#">
      <span>Prestasi Siswa</span>
    </a>

    <!-- Menu kanan -->
    <div class="d-flex align-items-center gap-4">
      <a href="{{ route('prestasi.input') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.input') ? 'fw-bold text-white' : 'text-secondary' }}">
         Input Prestasi
      </a>
      <a href="{{ route('prestasi.kategori') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.kategori') ? 'fw-bold text-white' : 'text-secondary' }}">
         Kategori
      </a>
      <a href="{{ route('prestasi.jenis') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.jenis') ? 'fw-bold text-white' : 'text-secondary' }}">
         Jenis
      </a>
      <a href="{{ route('prestasi.rekap') }}" 
         class="nav-link px-2 {{ request()->routeIs('prestasi.rekap') ? 'fw-bold text-white' : 'text-secondary' }}">
         Rekap Prestasi
      </a>
      <form action="{{ route('logout') }}" method="POST" class="d-inline mb-0">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
      </form>
    </div>
  </div>
</nav>


<!-- 🔙 Tombol Kembali ke Menu Wali Kelas -->
<div class="container mt-5 mb-4" style="margin-top: 90px !important;">
    <a href="{{ route('kebutuhanwalikelas') }}" 
       class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
      <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Wali Kelas
    </a>
</div>



<!-- 🔹 Konten Utama -->
<div class="container py-5 mt-5">
  <div class="card shadow-sm p-4 bg-white">
    <h3 class="text-center mb-4 fw-bold">Tambah Prestasi Siswa</h3>

    {{-- 🔹 Notifikasi --}}
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <!-- 🔹 Form Input -->
    <form action="{{ route('prestasi.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-3 mb-3">
        <!-- Kelas -->
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

        <!-- Jurusan -->
        <div class="col-md-3">
          <label class="form-label fw-semibold">Jurusan</label>
          <select id="jurusan" name="jurusan" class="form-select" required>
            <option value="">Pilih Jurusan</option>
            <option value="IPA">IPA</option>
            <option value="IPS">IPS</option>
          </select>
        </div>

        <!-- Nama Siswa -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nama Siswa</label>
          <select id="nama_siswa" name="NIS" class="form-select" disabled required>
            <option value="">Pilih kelas & jurusan dulu...</option>
          </select>
        </div>
      </div>

      <div class="row g-3 mb-3">
        <!-- Jenis Prestasi -->
        <div class="col-md-4">
          <label class="form-label fw-semibold">Jenis Prestasi</label>
          <select name="id_jenisprestasi" class="form-select" required>
            <option value="">Pilih Jenis Prestasi</option>
            @foreach($jenis as $j)
              <option value="{{ $j->id_jenisprestasi }}">{{ $j->nama_jenis }}</option>
            @endforeach
          </select>
        </div>

        <!-- Tanggal -->
        <div class="col-md-4">
          <label class="form-label fw-semibold">Tanggal Prestasi</label>
          <input type="date" name="tanggal" class="form-control" required>
        </div>

        <!-- Tahun Ajar -->
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

      <!-- Upload Bukti -->
      <div class="mb-3">
        <label class="form-label fw-semibold">Upload Bukti Prestasi (Gambar / PDF)</label>
        <input type="file" id="file_prestasi" name="file_prestasi" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
        <small class="text-muted">*Format yang diperbolehkan: JPG, PNG, atau PDF (maks 2MB)</small>

        <!-- Preview -->
        <div class="preview-container" id="preview-container"></div>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Data</button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Script AJAX & Preview -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // 🔹 Preview file (gambar atau PDF)
  $('#file_prestasi').on('change', function(e) {
    const file = e.target.files[0];
    const previewContainer = $('#preview-container');
    previewContainer.empty();

    if (file) {
      const fileType = file.type;
      const fileSize = file.size / 1024 / 1024; // MB

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

  // 🔹 Muat nama siswa otomatis
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
</body>
</html>
