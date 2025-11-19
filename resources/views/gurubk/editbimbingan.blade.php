<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Bimbingan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h3 class="mb-3">✏️ Edit Bimbingan</h3>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            Siswa: <strong>{{ $data->siswa->nama_siswa ?? $data->NIS }}</strong> <br>
            Kelas: {{ $data->kelas }} <br>
            Tanggal: {{ $data->tanggal }}
        </div>

        <div class="card-body">

            <form action="{{ route('guru.bimbingan.update', $data->id_bimbingan) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Bimbingan Ke</label>
                    <input type="number" name="bimbingan_ke" class="form-control"
                        value="{{ $data->bimbingan_ke }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kehadiran</label>
                    <select name="kehadiran" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="Hadir" @if($data->kehadiran=='Hadir') selected @endif>Hadir</option>
                        <option value="Tidak Hadir" @if($data->kehadiran=='Tidak Hadir') selected @endif>Tidak Hadir</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pelanggaran</label>
                    <input type="text" name="pelanggaran" class="form-control"
                           value="{{ $data->pelanggaran }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan / Notes</label>
                    <textarea name="notes" class="form-control" rows="4" required>{{ $data->notes }}</textarea>
                </div>

                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                <button class="btn btn-primary">Simpan</button>

            </form>

        </div>
    </div>

</div>

</body>
</html>
