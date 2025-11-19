<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>📘 Manajemen Kelas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
    .table th, .table td { vertical-align: middle !important; }
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
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">📘 Manajemen Kelas</a>
      <div class="d-flex align-items-center">
        <span class="text-white me-3">👤 {{ session('nama') ?? 'Admin' }}</span>
        <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm"
           onclick="return confirm('Yakin ingin logout?')">Logout</a>
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
  <div class="container py-5">
    <h2 class="text-center mb-4">Daftar Kelas</h2>

    {{-- 🔸 Pesan sukses/error --}}
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <!-- 🔹 Form Tambah/Edit Kelas -->
    <div class="card shadow-sm mb-4 border-0">
      <div class="card-body">
        <h5 id="formTitle" class="fw-bold text-primary mb-3">➕ Tambah Kelas Baru</h5>
        <form id="kelasForm" action="{{ route('kelas.store') }}" method="POST" class="row g-3 align-items-center">
          @csrf
          <input type="hidden" name="_method" id="methodField" value="POST">
          <input type="hidden" name="edit_id" id="editId">

          <div class="col-md-4">
            <label class="form-label fw-semibold">Nama Kelas</label>
            <select name="nama_kelas" id="nama_kelas" class="form-select" required>
              <option value="" selected disabled>-- Pilih Kelas --</option>
              <optgroup label="Kelas X">
                <option value="X-1">X-1</option>
                <option value="X-2">X-2</option>
                <option value="X-3">X-3</option>
              </optgroup>
              <optgroup label="Kelas XI">
                <option value="XI-1">XI-1</option>
                <option value="XI-2">XI-2</option>
                <option value="XI-3">XI-3</option>
              </optgroup>
              <optgroup label="Kelas XII">
                <option value="XII-1">XII-1</option>
                <option value="XII-2">XII-2</option>
                <option value="XII-3">XII-3</option>
              </optgroup>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Tahun Ajar</label>
            <input type="text" name="tahunAjar" id="tahunAjar" class="form-control"
                   value="{{ date('Y') }}/{{ date('Y') + 1 }}" required>
          </div>

          <div class="col-md-3 d-grid">
            <button type="submit" id="submitBtn" class="btn btn-primary fw-semibold">💾 Simpan</button>
            <button type="button" id="cancelEdit" class="btn btn-secondary fw-semibold mt-2 d-none">Batal</button>
          </div>
        </form>
      </div>
    </div>

    <!-- 🔹 Tabel Data Kelas -->
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h5 class="fw-bold text-secondary mb-3">📋 Data Kelas</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Nama Kelas</th>
                <th>Tahun Ajar</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($data as $k)
              <tr>
                <td>{{ $k->id_kelas }}</td>
                <td>{{ $k->nama_kelas }}</td>
                <td>{{ $k->tahunAjar }}</td>
                <td>{{ $k->user->nama ?? 'Admin' }}</td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-warning btn-sm"
                            onclick="editKelas('{{ $k->id_kelas }}', '{{ $k->nama_kelas }}', '{{ $k->tahunAjar }}')">
                      ✏️ Edit
                    </button>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('kelas.destroy', $k->id_kelas) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger btn-sm">🗑️ Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-muted">Belum ada data kelas</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 🔹 Script Edit Inline -->
  <script>
    function editKelas(id, nama, tahun) {
      document.getElementById('formTitle').innerText = '✏️ Edit Kelas';
      document.getElementById('kelasForm').action = "{{ url('kelas') }}/" + id;
      document.getElementById('methodField').value = 'PUT';
      document.getElementById('nama_kelas').value = nama;
      document.getElementById('tahunAjar').value = tahun;
      document.getElementById('submitBtn').innerText = '🔄 Update';
      document.getElementById('cancelEdit').classList.remove('d-none');
      document.getElementById('editId').value = id;
    }

    document.getElementById('cancelEdit').addEventListener('click', () => {
      document.getElementById('formTitle').innerText = '➕ Tambah Kelas Baru';
      document.getElementById('kelasForm').action = "{{ route('kelas.store') }}";
      document.getElementById('methodField').value = 'POST';
      document.getElementById('nama_kelas').value = '';
      document.getElementById('tahunAjar').value = `{{ date('Y') }}/{{ date('Y') + 1 }}`;
      document.getElementById('submitBtn').innerText = '💾 Simpan';
      document.getElementById('cancelEdit').classList.add('d-none');
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
