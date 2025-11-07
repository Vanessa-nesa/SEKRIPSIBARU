<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Absensi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
  <h3 class="text-center mb-4">
    🧾 Detail Absensi {{ $kelas }} {{ $jurusan }} - {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
  </h3>

  <a href="{{ route('absensi.rekap') }}" class="btn btn-secondary mb-3">⬅️ Kembali ke Rekap</a>

  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Nama Siswa</th>
        <th>Status</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      @forelse($detail as $index => $d)
      <tr class="{{ $d->status === 'Alpa' ? 'table-danger' : ($d->status === 'Sakit' ? 'table-warning' : ($d->status === 'Izin' ? 'table-primary' : '')) }}">
        <td>{{ $index + 1 }}</td>
        <td>{{ $d->nama_siswa }}</td>
        <td>{{ $d->status }}</td>
        <td>{{ $d->keterangan ?? '-' }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="4" class="text-center text-muted">Belum ada data absensi.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
</body>
</html>
