<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Preview Data Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
    input[readonly] { background-color: #f9f9f9; }
  </style>
</head>

<body>
  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">📋 Preview Data Siswa</a>
      <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"
         onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </div>
  </nav>

  <div class="container py-4">
    <div class="alert alert-info text-center">
      <strong>Kelas:</strong> {{ $kelas->nama_kelas }} | 
      <strong>Jurusan:</strong> {{ $jurusan }}
    </div>

    <form id="previewForm" action="{{ route('siswa.save') }}" method="POST">
      @csrf
      <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
      <input type="hidden" name="jurusan_siswa" value="{{ $jurusan }}">

      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>NIS</th>
              <th>Nama Siswa</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($previewData as $index => $row)
            <tr>
              <td>
                <input type="text" name="data[{{ $index }}][NIS]" class="form-control text-center" value="{{ $row['NIS'] }}" readonly>
              </td>
              <td>
                <input type="text" name="data[{{ $index }}][nama_siswa]" class="form-control text-center" value="{{ $row['nama_siswa'] }}" readonly>
              </td>
              <td>
                <div class="d-flex justify-content-center gap-2">
                  <button type="button" class="btn btn-warning btn-sm" onclick="editRow(this)">
                    ✏️ Edit
                  </button>
                  <button type="button" class="btn btn-danger btn-sm" onclick="hapusRow(this)">
                    🗑️ Hapus
                  </button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="text-center mt-4">
        <button type="button" id="btnSimpan" class="btn btn-success btn-lg">
          💾 Simpan Semua Data
        </button>
      </div>
    </form>
  </div>

  <script>
    function editRow(button) {
      const row = button.closest('tr');
      const inputs = row.querySelectorAll('input');
      const isEditing = button.dataset.editing === 'true';

      if (!isEditing) {
        inputs.forEach(input => input.removeAttribute('readonly'));
        button.textContent = '💾 Simpan';
        button.classList.replace('btn-warning', 'btn-success');
        button.dataset.editing = 'true';
      } else {
        inputs.forEach(input => input.setAttribute('readonly', true));
        button.textContent = '✏️ Edit';
        button.classList.replace('btn-success', 'btn-warning');
        button.dataset.editing = 'false';
      }
    }

    function hapusRow(button) {
      Swal.fire({
        title: 'Hapus Data?',
        text: 'Apakah kamu yakin ingin menghapus baris ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          button.closest('tr').remove();
        }
      });
    }

    document.getElementById('btnSimpan').addEventListener('click', function() {
      Swal.fire({
        title: 'Simpan Semua Data?',
        text: 'Apakah kamu yakin semua data sudah benar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Periksa lagi'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('previewForm').submit();
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
