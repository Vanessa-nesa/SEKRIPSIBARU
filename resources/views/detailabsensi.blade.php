<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Absensi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

  <h3 class="fw-bold mb-3">
    Detail Absensi – {{ $kelas }} / {{ $jurusan }} ({{ $tahunAjar }})
  </h3>
  
<!-- tombol kembali pematauan-->
  <a href="{{ route('pemantauan.index', [
    'kelas' => $kelas,
    'jurusan' => $jurusan,
    'tahunAjar' => $tahunAjar
]) }}" class="btn btn-warning mb-3">
    Kembali
</a>


  <!-- 🔹 FILTER TANGGAL -->
  <form method="GET" action="" class="row g-2 mb-4">

      <input type="hidden" name="kelas" value="{{ $kelas }}">
      <input type="hidden" name="jurusan" value="{{ $jurusan }}">
      <input type="hidden" name="tahunAjar" value="{{ $tahunAjar }}">

      <div class="col-md-3">
        <input type="date" name="tanggal" 
           class="form-control" 
           value="{{ request('tanggal') }}"
           required>
      </div>

      <div class="col-md-2">
          <button class="btn btn-primary w-100">Filter</button>
      </div>

  </form>

  <table class="table table-bordered align-middle">
    <thead class="table-dark">
      <tr>
        <th>Nama Siswa</th>
        <th>Status</th>
        <th>Tanggal</th>
      </tr>
    </thead>

    <tbody>
      @foreach($dataAbsensi as $d)
      <tr>
        <td>{{ $d->nama_siswa }}</td>
        <td>
          @if($d->status == 'Hadir')
            <span class="text-success fw-bold">Hadir</span>
          @elseif($d->status == 'Sakit')
            <span class="text-warning fw-bold">Sakit</span>
          @elseif($d->status == 'Izin')
            <span class="text-primary fw-bold">Izin</span>
          @else
            <span class="text-danger fw-bold">Alpa</span>
          @endif
        </td>
        <td>{{ $d->tanggal }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

</div>

</body>
</html>
