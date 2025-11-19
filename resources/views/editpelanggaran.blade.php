<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pelanggaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h3 class="text-center mb-4">Edit Pelanggaran Siswa</h3>

    <form method="POST" action="{{ route('pelanggaran.update', $pelanggaran->id_pelanggaran) }}" class="p-4 bg-white shadow-sm rounded">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label fw-bold">Nama Siswa</label>
        <input type="text" class="form-control" value="{{ $pelanggaran->siswa->nama_siswa ?? '-' }}" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Jenis Pelanggaran</label>
        <select name="id_jenispelanggaran" class="form-select" required>
          @foreach($jenispelanggaran as $jp)
            <option value="{{ $jp->id_jenispelanggaran }}" 
              {{ $pelanggaran->id_jenispelanggaran == $jp->id_jenispelanggaran ? 'selected' : '' }}>
              {{ $jp->nama_pelanggaran }} ({{ $jp->kategori->nama_kategori ?? '-' }})
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Jumlah (keberapa)</label>
        <input type="number" name="jumlah" class="form-control" value="{{ $pelanggaran->jumlah }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Catatan</label>
        <textarea name="notes" class="form-control" rows="3">{{ $pelanggaran->notes }}</textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-success px-4">Simpan Perubahan</button>
        <a href="{{ route('pelanggaran.rekap') }}" class="btn btn-secondary px-4">↩️ Kembali</a>
      </div>
    </form>
  </div>
</body>
</html>
