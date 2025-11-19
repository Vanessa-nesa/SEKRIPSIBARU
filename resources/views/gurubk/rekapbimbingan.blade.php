<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Bimbingan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-4">

    <h3 class="mb-4">📑 Rekap Bimbingan</h3>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- FILTER -->
    <div class="card mb-4">
        <div class="card-header">🔍 Filter Rekap</div>
        <div class="card-body">

            <form action="" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($daftar_kelas as $k)
                        <option value="{{ $k->nama_kelas }}" @if($selectedKelas==$k->nama_kelas) selected @endif>
                            {{ $k->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun Ajar</label>
                    <select name="tahunAjar" class="form-select" required>
                        <option value="">-- Pilih Tahun Ajar --</option>
                        @foreach($daftar_tahunAjar as $ta)
                        <option value="{{ $ta }}" @if($selectedTahun==$ta) selected @endif>
                            {{ $ta }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100">Terapkan Filter</button>
                </div>
            </form>

        </div>
    </div>

    <!-- TABEL REKAP -->
    <div class="card">
        <div class="card-header">
            Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Bimbingan Ke</th>
                        <th>Notes</th>
                        <th>Absensi</th>
                        <th>Pelanggaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($bimbingan as $i => $b)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $b->NIS }}</td>
                    <td>{{ $b->siswa->nama_siswa ?? '-' }}</td>
                    <td>{{ $b->kelas }}</td>
                    <td>{{ $b->bimbingan_ke }}</td>
                    <td>{{ $b->notes }}</td>

                    <!-- Absensi -->
                    @php
                        $abs = $absensi->where('NIS', $b->NIS)->first();
                        $pel = $pelanggaran->where('NIS', $b->NIS)->first();
                    @endphp

                    <td>
                        @if($abs)
                            {{ $abs->status }}
                            @if($abs->keterangan)
                                <br><small>{{ $abs->keterangan }}</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    <!-- Pelanggaran -->
                    <td>
                        @if($pel)
                            {{ $pel->notes ?? '-' }}
                        @else
                            {{ $b->pelanggaran ?? '-' }}
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('guru.bimbingan.edit', $b->id_bimbingan) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('guru.bimbingan.destroy', $b->id_bimbingan) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Yakin ingin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Tidak ada data.</td>
                </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
</body>
</html>
