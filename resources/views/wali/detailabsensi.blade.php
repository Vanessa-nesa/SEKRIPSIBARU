<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Absensi Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container mt-4">
    <h4 class="mb-3 text-center">Detail Absensi - {{ $kelas }} {{ $jurusan }} ({{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }})</h4>

    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($detailAbsensi as $i => $abs)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $abs->nama_siswa }}</td>
            <td>{{ $abs->status }}</td>
            <td>{{ $abs->keterangan }}</td>
            <td>
              <a href="{{ route('wali.editAbsensi', ['id_absensi' => $abs->id_absensi]) }}" 
                 class="btn btn-sm btn-warning">
                <i class="bi bi-pencil-square"></i>
              </a>
              <form action="{{ route('wali.deleteAbsensi', ['id_absensi' => $abs->id_absensi]) }}" 
                    method="POST" class="d-inline" 
                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>
</body>
</html>
