<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jenis Prestasi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/prestasi.css') }}" rel="stylesheet">
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">Prestasi Siswa</a>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center gap-3">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('prestasi.input') ? 'active' : '' }}" href="{{ route('prestasi.input') }}">Input Prestasi</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('prestasi.kategori') ? 'active' : '' }}" href="{{ route('prestasi.kategori') }}">Kategori</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('prestasi.jenis') ? 'active' : '' }}" href="{{ route('prestasi.jenis') }}">Jenis</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('prestasi.rekap') ? 'active' : '' }}" href="{{ route('prestasi.rekap') }}">Rekap Prestasi</a></li>

        <li class="nav-item">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔙 Tombol Kembali ke Menu Wali Kelas -->
<div class="container mt-5 mb-4" style="margin-top: 90px !important;">
   <a href="{{ route('kebutuhanwalikelas') }}" 
   class="btn btn-dark btn-kembali shadow-sm d-inline-flex align-items-center">

      <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Wali Kelas
    </a>
</div>

<!-- 🔹 CONTENT -->
<div class="container py-5 mt-5">
  <div class="card shadow-sm p-4 bg-white">
    <h3 class="text-center mb-4 fw-bold">Input Jenis Prestasi</h3>

    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    
    <!-- 🔸 FORM TAMBAH -->
    <form action="{{ route('jenis.store') }}" method="POST" class="mb-4">
      @csrf
      <div class="row justify-content-center">
        
        <div class="col-md-3">
          <label class="fw-semibold mb-2">Kategori</label>
          <select name="id_kategoriprestasi" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategori as $k)
              <option value="{{ $k->id_kategoriprestasi }}">{{ $k->nama_kategori }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="fw-semibold mb-2">Nama Jenis Prestasi</label>
          <input type="text" name="nama_jenis" class="form-control" placeholder="Contoh: OSN, FLS2N" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-primary w-100">Tambah</button>
        </div>

      </div>
    </form>

    <!-- 🔸 TABLE LIST -->
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
            <td>{{ $i + 1 }}</td>
            <td>{{ $j->kategori->nama_kategori }}</td>
            <td>{{ $j->nama_jenis }}</td>

            <td class="d-flex justify-content-center gap-2">

              <!-- 🔸 EDIT BUTTON -->
              <button class="btn btn-warning btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#editModal"
                      data-id="{{ $j->id_jenispres }}"
                      data-nama="{{ $j->nama_jenis }}"
                      data-kat="{{ $j->id_kategoriprestasi }}">
                Edit
              </button>

              <!-- 🔸 DELETE BUTTON -->
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
            <tr><td colspan="4" class="text-muted">Belum ada data jenis prestasi</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>


<!-- 🔹 MODAL EDIT -->
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

          <label class="fw-semibold">Kategori Prestasi</label>
          <select name="id_kategoriprestasi" id="editKategori" class="form-select mb-3" required>
            @foreach($kategori as $k)
              <option value="{{ $k->id_kategoriprestasi }}">{{ $k->nama_kategori }}</option>
            @endforeach
          </select>

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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ISI DATA MODAL SAAT KLIK EDIT
  const editModal = document.getElementById('editModal');
  editModal.addEventListener('show.bs.modal', function(event) {

    let button = event.relatedTarget;

    let id   = button.getAttribute('data-id');
    let nama = button.getAttribute('data-nama');
    let kat  = button.getAttribute('data-kat');

    document.getElementById('editNama').value = nama;
    document.getElementById('editKategori').value = kat;

    document.getElementById('editForm').action = "/jenis/" + id;
  });
</script>

</body>
</html>
