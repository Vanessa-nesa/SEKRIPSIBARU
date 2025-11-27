<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <!-- Mengatur tampilan agar responsif di perangkat mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kategori Prestasi</title>

  <!-- Bootstrap untuk styling utama -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icon Bootstrap (dipakai untuk ikon tombol kembali) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- File CSS custom yang mengatur tampilan halaman prestasi -->
  <link href="{{ asset('css/prestasi.css') }}" rel="stylesheet">
</head>
<body>

<!-- Navigasi utama untuk modul prestasi -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid px-4">

    <!-- Judul panel dan nama pengguna yang login -->
    <a class="navbar-brand fw-bold text-light d-flex align-items-center gap-2 mb-0" href="#">
      <span>Prestasi Siswa</span>
      <span class="text-light ms-2">| {{ session('nama') ?? 'Guru Wali' }}</span>
    </a>

    <!-- Tombol burger menu untuk mode mobile -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Daftar menu navigasi -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center gap-3">

        <!-- Menu Input Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.input') ? 'active' : '' }}" 
             href="{{ route('prestasi.input') }}">
             Input Prestasi
          </a>
        </li>

        <!-- Menu Kategori Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.kategori') ? 'active' : '' }}" 
             href="{{ route('prestasi.kategori') }}">
             Kategori
          </a>
        </li>

        <!-- Menu Jenis Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.jenis') ? 'active' : '' }}" 
             href="{{ route('prestasi.jenis') }}">
             Jenis
          </a>
        </li>

        <!-- Menu Rekap Prestasi -->
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('prestasi.rekap') ? 'active' : '' }}" 
             href="{{ route('prestasi.rekap') }}">
             Laporan Prestasi
          </a>
        </li>

        <!-- Tombol Logout -->
        <li class="nav-item">
          <form id="logoutForm" method="GET" action="{{ route('logout') }}">
            <button type="button" id="logoutBtn" class="btn btn-outline-light btn-sm">
              Logout
            </button>
          </form>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- Tombol kembali ke menu wali kelas -->
<div class="container mt-5 mb-4" style="margin-top: 90px !important;">
    <a href="{{ route('kebutuhanwalikelas') }}" 
       class="btn btn-dark btn-kembali shadow-sm d-inline-flex align-items-center">
      <i class="bi bi-arrow-left-circle me-2"></i> 
      Kembali ke Menu Wali Kelas
    </a>
</div>

<!-- Bagian utama halaman -->
<div class="container py-5 mt-5">
  <div class="card shadow-sm p-4 bg-white">

    <!-- Judul halaman -->
    <h3 class="text-center mb-4 fw-bold">Input Kategori Prestasi</h3>

    <!-- Notifikasi jika berhasil menambah atau mengedit kategori -->
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <!-- Form untuk menambah kategori baru -->
    <form action="{{ route('kategori.store') }}" method="POST" class="mb-4">
      @csrf
      <div class="row justify-content-center">

        <!-- Input nama kategori -->
        <div class="col-md-4">
          <label class="fw-semibold mb-2">Nama Kategori</label>
          <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Akademik" required>
        </div>

        <!-- Tombol submit -->
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-primary w-100">Tambah</button>
        </div>

      </div>
    </form>

    <!-- Tabel daftar kategori prestasi -->
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

              <!-- Nomor urut -->
              <td>{{ $i + 1 }}</td>

              <!-- Nama kategori -->
              <td>{{ $k->nama_kategori }}</td>

              <!-- Tombol aksi edit dan hapus -->
              <td class="d-flex justify-content-center gap-2">

                <!-- Tombol membuka modal edit -->
                <button 
                  class="btn btn-warning btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#editModal"
                  data-id="{{ $k->id_kategoriprestasi }}"
                  data-nama="{{ $k->nama_kategori }}">
                  Edit
                </button>

                <!-- Tombol hapus kategori -->
                <form action="{{ route('kategori.destroy', $k->id_kategoriprestasi) }}" 
                      method="POST"
                      onsubmit="return confirm('Hapus kategori ini?')">
                  @csrf 
                  @method('DELETE')
                  <button class="btn btn-danger btn-sm">Hapus</button>
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
    </div> <!-- End table -->
  </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Form edit kategori -->
      <form method="POST" id="editForm">
        @csrf
        @method('PUT')

        <!-- Header modal -->
        <div class="modal-header">
          <h5 class="modal-title">Edit Kategori Prestasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body modal berisi input nama kategori -->
        <div class="modal-body">
          <label class="fw-semibold">Nama Kategori</label>
          <input type="text" id="editNama" name="nama_kategori" class="form-control" required>
        </div>

        <!-- Footer modal -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  /* Mengisi modal edit dengan data kategori yang diklik */
  const editModal = document.getElementById('editModal');

  editModal.addEventListener('show.bs.modal', function (event) {

    let button = event.relatedTarget;

    let id   = button.getAttribute('data-id');
    let nama = button.getAttribute('data-nama');

    document.getElementById('editNama').value = nama;

    document.getElementById('editForm').action = "/kategori/" + id;
  });
</script>

<script>
/* Konfirmasi logout sebelum submit form */
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (confirm("Yakin ingin logout?")) {
        document.getElementById('logoutForm').submit();
    }
});
</script>

</body>
</html>
