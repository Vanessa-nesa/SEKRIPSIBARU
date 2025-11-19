<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rekap Prestasi Siswa</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('css/prestasi.css') }}" rel="stylesheet">
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">Prestasi Siswa</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav gap-2">
        <li><a href="{{ route('prestasi.input') }}" class="nav-link {{ request()->routeIs('prestasi.input') ? 'active text-white fw-bold' : '' }}">Input Prestasi</a></li>
        <li><a href="{{ route('prestasi.kategori') }}" class="nav-link {{ request()->routeIs('prestasi.kategori') ? 'active text-white fw-bold' : '' }}">Kategori</a></li>
        <li><a href="{{ route('prestasi.jenis') }}" class="nav-link {{ request()->routeIs('prestasi.jenis') ? 'active text-white fw-bold' : '' }}">Jenis</a></li>
        <li><a href="{{ route('prestasi.rekap') }}" class="nav-link {{ request()->routeIs('prestasi.rekap') ? 'active text-white fw-bold' : '' }}">Rekap Prestasi</a></li>
        <li>
          <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Logout</a>
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

<!-- 🔹 Konten -->
<div class="container py-5 mt-5">
  <div class="card shadow-sm p-4 bg-white">
    <h3 class="text-center mb-4 fw-bold">Rekap Prestasi Siswa</h3>

    <!-- 🔹 Form Filter -->
    <form method="GET" action="{{ route('prestasi.rekap') }}" class="row g-3 justify-content-center mb-4">
      <div class="col-md-3">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select">
          <option value="">Semua</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas ?? '') == $k->nama_kelas ? 'selected' : '' }}>
              {{ $k->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select">
          <option value="">Semua</option>
          <option value="IPA" {{ ($jurusan ?? '') == 'IPA' ? 'selected' : '' }}>IPA</option>
          <option value="IPS" {{ ($jurusan ?? '') == 'IPS' ? 'selected' : '' }}>IPS</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select">
          <option value="">Semua</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>
              {{ $t }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label fw-bold">Jenis Prestasi</label>
        <select name="id_jenisprestasi" class="form-select">
          <option value="">Semua</option>
          @foreach($jenis as $j)
            <option value="{{ $j->id_jenisprestasi }}" {{ ($id_jenisprestasi ?? '') == $j->id_jenisprestasi ? 'selected' : '' }}>
              {{ $j->nama_jenis }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100 fw-semibold">Tampilkan</button>
      </div>
    </form>

    <!-- 🔹 Tabel Data -->
    @if($prestasi->count() > 0)
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm">
          <thead class="table-secondary">
            <tr>
              <th>#</th>
              <th>NIS</th>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Jenis Prestasi</th>
              <th>Tanggal</th>
              <th>Tahun Ajar</th>
              <th>Bukti</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($prestasi as $index => $p)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->NIS }}</td>
                <td>{{ $p->siswa->nama_siswa ?? '-' }}</td>
                <td>{{ $p->kelas }}</td>
                <td>{{ $p->jurusan }}</td>
                <td>{{ $p->jenis->nama_jenis ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $p->tahunAjar }}</td>
                <td>
                  @if($p->file_prestasi)
                    <a href="{{ asset('uploads/prestasi/' . $p->file_prestasi) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                      <i class="bi bi-eye"></i> Lihat
                    </a>
                  @else
                    <span class="text-muted">Tidak ada</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('prestasi.edit', $p->id_prestasi) }}" class="btn btn-sm btn-warning text-white">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <form action="{{ route('prestasi.destroy', $p->id_prestasi) }}" method="POST" class="d-inline hapus-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="alert alert-warning text-center mt-4">
        Tidak ada data prestasi ditemukan untuk filter tersebut.
      </div>
    @endif
  </div>
</div>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.hapus-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Hapus Data?',
      text: "Data ini akan dihapus secara permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
