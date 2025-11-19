<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Bimbingan Konseling</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f2f3f7;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .filter-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.08);
            margin-bottom: 35px;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }

        /* HEADER TABEL */
        table thead {
            background: #151515;
            color: white;
        }

        table thead th {
            font-size: 14px;
            text-align: center;
        }

        table tbody td {
            vertical-align: middle;
            font-size: 14px;
        }

        .btn-primary {
            background: #0d6efd !important;
            font-weight: 600;
        }

        .btn-warning {
            font-size: 12px;
            font-weight: bold;
        }

        .btn-danger {
            font-size: 12px;
            font-weight: bold;
        }

        .form-select, .form-control {
            border-radius: 8px;
        }

        .rounded-btn {
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <h2>Rekap Bimbingan Konseling</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- -------- FILTER -------- -->
    <div class="filter-container">
        <form method="GET" class="row g-3">

            <div class="col-md-4">
                <label class="fw-bold">Kelas</label>
                <select name="kelas" class="form-select" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($daftar_kelas as $k)
                    <option value="{{ $k->nama_kelas }}"
                        @if($selectedKelas==$k->nama_kelas) selected @endif>
                        {{ $k->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="fw-bold">Jurusan</label>
                <input type="text" class="form-control" value="{{ $selectedJurusan ?? '-' }}" readonly>
            </div>

            <div class="col-md-4">
                <label class="fw-bold">Tahun Ajar</label>
                <select name="tahunAjar" class="form-select" required>
                    <option value="">Pilih Tahun Ajar</option>
                    @foreach($daftar_tahunAjar as $ta)
                    <option value="{{ $ta }}" @if($selectedTahun==$ta) selected @endif>
                        {{ $ta }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="fw-bold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Tampilkan</button>
            </div>

        </form>
    </div>


    <!-- -------- TABEL REKAP -------- -->
    <div class="table-container">
        <h5 class="fw-bold mb-3">
            Data Bimbingan – 
            <span class="text-primary">
                {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
            </span>
        </h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
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
                    @php
                        $abs = $absensi->where('NIS', $b->NIS)->first();
                        $pel = $pelanggaran->where('NIS', $b->NIS)->first();
                    @endphp

                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $b->NIS }}</td>
                        <td class="text-start">{{ $b->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $b->kelas }}</td>
                        <td>{{ $b->bimbingan_ke }}</td>
                        <td class="text-start">{{ $b->notes }}</td>

                        <!-- ABSENSI -->
                        <td>
                            @if($abs)
                                {{ $abs->status }}
                                @if($abs->keterangan)
                                    <br><small class="text-muted">{{ $abs->keterangan }}</small>
                                @endif
                            @else
                                -
                            @endif
                        </td>

                        <!-- PELANGGARAN -->
                        <td>
                            @if($pel)
                                {{ $pel->notes ?? '-' }}
                            @else
                                {{ $b->pelanggaran ?? '-' }}
                            @endif
                        </td>

                        <!-- BUTTON ACTION -->
                        <td class="text-nowrap">
                            <a href="{{ route('guru.bimbingan.edit', $b->id_bimbingan) }}" 
                               class="btn btn-warning btn-sm rounded-btn">Edit</a>

                            <form action="{{ route('guru.bimbingan.destroy', $b->id_bimbingan) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus data?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm rounded-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">
                            Tidak ada data bimbingan untuk tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

</body>
</html>
