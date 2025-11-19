<!DOCTYPE html>
<html>
<head>
  <title>Edit Bimbingan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3 class="mb-4">✏️ Edit Data Bimbingan</h3>

  <form action="{{ route('bimbingan.update', $data->id_bimbingan) }}" method="POST">
    @csrf

    <input type="hidden" name="kelas" value="{{ request('kelas') }}">
    <input type="hidden" name="jurusan" value="{{ request('jurusan') }}">
    <input type="hidden" name="tahunAjar" value="{{ request('tahunAjar') }}">
    <input type="hidden" name="tanggal" value="{{ request('tanggal_riwayat') }}">

    <div class="mb-3">
      <label class="fw-bold">Nama Siswa</label>
      <input type="text" class="form-control" value="{{ $data->nama_siswa }}" disabled>
    </div>

    <div class="mb-3">
      <label class="fw-bold">Pelanggaran</label>
      <input type="text" name="pelanggaran" class="form-control" value="{{ $data->pelanggaran }}">
    </div>

    <div class="mb-3">
      <label class="fw-bold">Bimbingan Ke-</label>
      <input type="number" name="bimbingan_ke" class="form-control" value="{{ $data->bimbingan_ke }}">
    </div>

    <div class="mb-3">
      <label class="fw-bold">Catatan</label>
      <textarea name="notes" class="form-control">{{ $data->notes }}</textarea>
    </div>

    <button class="btn btn-primary">💾 Update</button>
    <a href="{{ route('bimbingan', ['mode'=>'rekapbimbingan']) }}" class="btn btn-secondary">Kembali</a>
  </form>
</div>

</body>
</html>
