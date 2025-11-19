<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>📘 Rekap Bimbingan Konseling</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
    .navbar-brand { font-weight: bold; }
    table th, table td { vertical-align: middle !important; }
  </style>
</head>

<body>

{{-- ===========================================================
     NAVBAR (Disembunyikan jika from_bk == true)
=========================================================== --}}
@unless(isset($from_bk) && $from_bk === true)
<nav class="navbar navbar-dark bg-dark shadow-sm px-4">
    <a class="navbar-brand" href="#">Guru BK Panel</a>
    <div class="d-flex gap-3">
      <a href="{{ route('bimbingan') }}" class="nav-link text-white">Input Bimbingan</a>
      <a href="{{ route('rekapbimbingan') }}" class="nav-link text-white fw-semibold">Rekap Bimbingan</a>
      <a href="{{ route('rekapabsensi') }}" class="nav-link text-white">Rekap Absensi</a>
      <a href="{{ route('pelanggaran.index') }}" class="nav-link text-white">Pelanggaran</a>
      <a href="{{ route('logout') }}" class="nav-link text-danger fw-bold">Logout</a>
    </div>
</nav>
@endunless


<div class="container py-5">

  <h3 class="text-center mb-4"><i class="bi bi-journal-check text-primary"></i> Rekap Bimbingan Konseling</h3>

  <!-- 🔸 FILTER FORM -->
  <form method="GET" action="{{ route('rekapbimbingan') }}" class="row justify-content-center g-3 mb-4">

    <!-- KELAS -->
    <div class="col-md-3">
        <label class="form-label fw-semibold">Kelas</label>
        <select name="kelas" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($daftar_kelas as $k)
                <option value="{{ $k->nama_kelas }}" {{ ($kelas ?? '') == $k->nama_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- JURUSAN (BARU DITAMBAHKAN) -->
    <div class="col-md-3">
        <label class="form-label fw-semibold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
            <option value="">-- Pilih Jurusan --</option>
            @foreach(['IPA','IPS'] as $j)
                <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>
                    {{ $j }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- TAHUN AJAR -->
    <div class="col-md-3">
        <label class="form-label fw-semibold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
            <option value="">-- Pilih Tahun Ajar --</option>
            @foreach($daftar_tahunAjar as $t)
                <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- TANGGAL (DIRUBAH MENJADI INPUT DATE) -->
    <div class="col-md-3">
        <label class="form-label fw-semibold">Tanggal Bimbingan</label>
        <input type="date"
               name="tanggal_riwayat"
               value="{{ $tanggal_riwayat ?? '' }}"
               class="form-control">
    </div>

    <!-- BUTTON -->
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-funnel"></i> Tampilkan
        </button>
    </div>

</form>


  {{-- ===========================================================
       GURU BK VIEW
  ============================================================ --}}
  @if(session('role') === 'Guru BK')

      {{-- 🔹 Rekap Bimbingan --}}
      @if(isset($rekapBimbingan) && $rekapBimbingan->count() > 0)
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
          <i class="bi bi-calendar-event"></i>
          Rekap Bimbingan Kelas {{ $kelas ?? '-' }} ({{ $tahunAjar ?? '-' }})
        </div>

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center m-0">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>Kelas</th>
                <th>Tahun Ajar</th>
                <th>Total Siswa</th>
                <th>Total Bimbingan</th>
                <th>Pelanggaran</th>
              </tr>
            </thead>

            <tbody>
              @foreach($rekapBimbingan as $r)
              <tr>
                <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $r->kelas }}</td>
                <td>{{ $r->tahunAjar }}</td>
                <td>{{ $r->total_siswa }}</td>
                <td>{{ $r->total_bimbingan ?? '-' }}</td>
                <td>{{ $r->pelanggaran ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>

          </table>
        </div>
      </div>
      @endif

      {{-- 🔹 Riwayat Detail --}}
      @if(isset($riwayat) && $riwayat->count() > 0)
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white fw-bold">
          <i class="bi bi-journal-text"></i>
          Riwayat Detail Bimbingan ({{ \Carbon\Carbon::parse($tanggal_riwayat)->format('d M Y') }})
        </div>

        <div class="table-responsive">
          <table class="table table-bordered text-center m-0">
            <thead class="table-light">
              <tr>
                <th>Nama Siswa</th>
                <th>Bimbingan Ke-</th>
                <th>Catatan</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              @foreach($riwayat as $r)
              <tr>
                <td>{{ $r->siswa->nama_siswa ?? '-' }}</td>
                <td>{{ $r->bimbingan_ke ?? '-' }}</td>
                <td>{{ $r->notes ?? '-' }}</td>

                <td>
                  <!-- Edit -->
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id_bimbingan }}">
                    ✏️ Edit
                  </button>

                  <!-- Hapus -->
                  <form action="{{ route('bimbingan.destroy', $r->id_bimbingan) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">🗑️</button>
                  </form>
                </td>
              </tr>

              {{-- Modal Edit --}}
              <div class="modal fade" id="editModal{{ $r->id_bimbingan }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('bimbingan.update', $r->id_bimbingan) }}" method="POST">
                      @csrf
                      @method('PUT')

                      <div class="modal-header bg-warning">
                        <h5 class="modal-title fw-bold">✏️ Edit Bimbingan</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Tanggal</label>
                          <input type="date" name="tanggal" class="form-control" value="{{ $r->tanggal }}" required>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Bimbingan Ke-</label>
                          <input type="number" name="bimbingan_ke" class="form-control" value="{{ $r->bimbingan_ke }}">
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Catatan</label>
                          <textarea name="notes" class="form-control" rows="3">{{ $r->notes }}</textarea>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success">💾 Simpan</button>
                      </div>

                    </form>
                  </div>
                </div>
              </div>

              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endif

  @endif

  {{-- ===========================================================
       VIEW UNTUK KEPALA & WAKASEK
  ============================================================ --}}
  @if(in_array(session('role'), ['Kepala Sekolah', 'Wakil Kepala Sekolah']))

      @if(isset($rekapBimbingan) && $rekapBimbingan->count() > 0)
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
          <i class="bi bi-bar-chart-line"></i> Daftar Bimbingan Siswa
        </div>

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center m-0">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Bimbingan Ke-</th>
                <th>Catatan</th>
                <th>Tahun Ajar</th>
              </tr>
            </thead>

            <tbody>
              @foreach($rekapBimbingan as $r)
              <tr>
                <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $r->nama_siswa }}</td>
                <td>{{ $r->kelas }}</td>
                <td>{{ $r->bimbingan_ke ?? '-' }}</td>
                <td>{{ $r->notes ?? '-' }}</td>
                <td>{{ $r->tahunAjar }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      @else
        <div class="alert alert-warning text-center mt-4">
          ⚠️ Belum ada data bimbingan yang tercatat untuk filter ini.
        </div>
      @endif

  @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
