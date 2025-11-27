<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jenis Prestasi</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- File CSS utama untuk semua halaman prestasi -->
  <link href="{{ asset('css/prestasi.css') }}" rel="stylesheet">
</head>

<body>

<!-- ==========================================================
     NAVBAR
     Berisi nama panel, nama pengguna, dan menu navigasi
========================================================== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid px-4">

    <!-- Nama panel di kiri + nama pengguna dari session -->
    <a class="navbar-brand fw-bold text-light d-flex align-items-center gap-2 mb-0" href="#">
      <span>Prestasi Siswa</span>
      <span class="text-light ms-2">| {{ session('nama') ?? 'Guru Wali' }}</span>
    </a>

    <!-- Menu navigasi -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center gap-3">

        <!-- Menu menuju halaman Input Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.input') ? 'active' : '' }}"
             href="{{ route('prestasi.input') }}">
             Input Prestasi
          </a>
        </li>

        <!-- Menu menuju halaman Kategori Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.kategori') ? 'active' : '' }}"
             href="{{ route('prestasi.kategori') }}">
             Kategori
          </a>
        </li>

        <!-- Menu menuju halaman Jenis Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.jenis') ? 'active' : '' }}"
             href="{{ route('prestasi.jenis') }}">
             Jenis
          </a>
        </li>

        <!-- Menu menuju halaman Rekap Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.rekap') ? 'active' : '' }}"
             href="{{ route('prestasi.rekap') }}">
             Laporan Prestasi
          </a>
        </li>

        <!-- Tombol logout -->
        <li class="nav-item">
          <form id="logoutForm" method="GET" action="{{ route('logout') }}">
            <button type="button" id="logoutBtn" class="btn btn-outline-light btn-sm">Logout</button>
          </form>
        </li>

      </ul>
    </div>

  </div>
</nav>

<!-- ==========================================================
     TOMBOL KEMBALI KE MENU WALI KELAS
========================================================== -->
<div class="container mt-5 mb-4" style="margin-top: 90px !important;">
   <a href="{{ route('kebutuhanwalikelas') }}"
      class="btn btn-dark btn-kembali shadow-sm d-inline-flex align-items-center">
      <i class="bi bi-arrow-left-circle me-2"></i>
      Kembali ke Menu Wali Kelas
   </a>
</div>

<!-- ==========================================================
     KONTEN UTAMA: Form Input & Tabel Jenis Prestasi
========================================================== -->
<div class="container py-5 mt-5">
  <div class="card shadow-sm p-4 bg-white">

    <!-- Judul halaman -->
    <h3 class="text-center mb-4 fw-bold">Input Jenis Prestasi</h3>

    <!-- Notifikasi sukses -->
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <!-- ============================================
         FORM TAMBAH JENIS PRESTASI
         Digunakan untuk menambah data baru
    ============================================ -->
    <form action="{{ route('jenis.store') }}" method="POST" class="mb-4">
      @csrf
      <div class="row justify-content-center">

        <!-- Pilihan kategori -->
        <div class="col-md-3">
          <label class="fw-semibold mb-2">Kategori</label>
          <select name="id_kategoriprestasi" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategori as $k)
              <option value="{{ $k->id_kategoriprestasi }}">{{ $k->nama_kategori }}</option>
            @endforeach
          </select>
        </div>

        <!-- Input nama jenis prestasi -->
        <div class="col-md-3">
          <label class="fw-semibold mb-2">Nama Jenis Prestasi</label>
          <input type="text" name="nama_jenis" class="form-control"
                 placeholder="Contoh: OSN, FLS2N" required>
        </div>

        <!-- Tombol tambah -->
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-primary w-100">Tambah</button>
        </div>

      </div>
    </form>

    <!-- ============================================
         TABEL DAFTAR JENIS PRESTASI
         Menampilkan seluruh data yang sudah ada
    ============================================ -->
    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle shadow-sm">
        <thead class="table-secondary">
          <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Jenis Prestasi</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($jenis as $i => $j)
          <tr>

            <!-- Nomor urut -->
            <td>{{ $i + 1 }}</td>

            <!-- Nama kategori -->
            <td>{{ $j->kategori->nama_kategori }}</td>

            <!-- Nama jenis prestasi -->
            <td>{{ $j->nama_jenis }}</td>

            <!-- Tombol Edit & Hapus -->
            <td class="d-flex justify-content-center gap-2">

              <!-- Tombol edit membuka modal -->
              <button class="btn btn-warning btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#editModal"
                      data-id="{{ $j->id_jenisprestasi }}"
                      data-nama="{{ $j->nama_jenis }}"
                      data-kat="{{ $j->id_kategoriprestasi }}">
                Edit
              </button>

              <!-- Tombol hapus -->
              <form action="{{ route('jenis.destroy', $j->id_jenisprestasi) }}"
                    method="POST"
                    onsubmit="return confirm('Hapus jenis prestasi ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus</button>
              </form>

            </td>
          </tr>

          @empty
            <tr>
              <td colspan="4" class="text-muted">Belum ada data jenis prestasi</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>

<!-- ==========================================================
     MODAL EDIT JENIS PRESTASI
     Digunakan untuk mengubah data yang dipilih
========================================================== -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" id="editForm">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Jenis Prestasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- Pilihan kategori -->
          <label class="fw-semibold">Kategori Prestasi</label>
          <select name="id_kategoriprestasi" id="editKategori" class="form-select mb-3" required>
            @foreach($kategori as $k)
              <option value="{{ $k->id_kategoriprestasi }}">{{ $k->nama_kategori }}</option>
            @endforeach
          </select>

          <!-- Input nama jenis -->
          <label class="fw-semibold">Nama Jenis</label>
          <input type="text" name="nama_jenis" id="editNama" class="form-control" required>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ==========================================================
     SCRIPT MENGISI MODAL EDIT DENGAN DATA TERPILIH
========================================================== -->
<script>
  const editModal = document.getElementById('editModal');
  editModal.addEventListener('show.bs.modal', function(event) {

    // Tombol yang men-trigger modal
    let button = event.relatedTarget;

    // Mengambil atribut data dari tombol edit
    let id   = button.getAttribute('data-id');
    let nama = button.getAttribute('data-nama');
    let kat  = button.getAttribute('data-kat');

    // Mengisi input pada modal
    document.getElementById('editNama').value = nama;
    document.getElementById('editKategori').value = kat;

    // Mengatur action form untuk update data
    document.getElementById('editForm').action = "/jenis/" + id;
  });
</script>

<!-- ==========================================================
     SCRIPT KONFIRMASI LOGOUT
========================================================== -->
<script>
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (confirm("Yakin ingin logout?")) {
        document.getElementById('logoutForm').submit();
    }
});
</script>

</body>
</html>
