<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Absensi Siswa</title>

    <!-- Bootstrap CSS (pastikan kamu sudah punya link ini di layout utama juga) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f9fb;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-top: 60px;
        }
        h4 {
            font-weight: 600;
        }
        label {
            font-weight: 500;
        }
        .btn-primary {
            background-color: #4e73df;
            border: none;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card p-4">
            <h4 class="mb-3 text-center">Edit Absensi Siswa</h4>
            <hr>

            <div class="mb-4">
    <label class="form-label fw-bold">Nama Siswa</label>
    <input type="text" class="form-control" value="{{ $absensi->nama_siswa }}"
           disabled>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Kelas / Jurusan</label>
    <input type="text" class="form-control"
           value="{{ $absensi->kelas_siswa }} - {{ $absensi->jurusan_siswa }}" disabled>
</div>

            <!-- FORM EDIT -->
            <form action="{{ route('wali.updateAbsensi', $absensi->id_absensi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="status" class="form-label">Status Kehadiran</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Hadir" {{ $absensi->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Sakit" {{ $absensi->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ $absensi->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alpha" {{ $absensi->status == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3">{{ $absensi->keterangan }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('wali.rekapabsensi') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (opsional, untuk interaksi tambahan) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
