<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>📊 Rekap Absensi Harian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
    .table thead { background-color: #212529; color: white; }
    .nav-link.active { font-weight: bold; color: #0d6efd !important; }
  </style>
</head>
<body>
  <!-- 🔹 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">📋 Absensi Wali Kelas</a>

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}" 
             href="{{ route('absensi.index') }}">🧾 Input Absensi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('absensi.rekap') ? 'active' : '' }}" 
             href="{{ route('absensi.rekap') }}">📊 Rekap Absensi</a>
        </li>
      </ul>

      <div class="ms-auto">
        <span class="text-white me-3"><i class="bi bi-person-fill"></i> Guru Wali</span>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">@csrf
          <button type="submit" class="btn btn-outline-light btn-sm">🚪 Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <h3 class="text-center mb-4">📅 Rekap Absensi Harian</h3>

    {{-- 🔸 Notifikasi sukses --}}
    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- 🔸 Filter --}}
    <form method="GET" action="{{ route('absensi.rekap') }}" class="row justify-content-center mb-4">
      <div class="col-md-2">
        <label class="fw-bold">Kelas</label>
        <select name="kelas" id="kelasSelect" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas ?? '') == $k->nama_kelas ? 'selected' : '' }}>
              {{ $k->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" id="tahunAjarSelect" class="form-select" required>
          <option value="">-- Pilih Tahun Ajar --</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ ($tahunAjar ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">-- Pilih Jurusan --</option>
          @foreach(['IPA','IPS'] as $j)
            <option value="{{ $j }}" {{ ($jurusan ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="fw-bold">Tanggal</label>
        <input type="date" name="tanggal" value="{{ $tanggal ?? '' }}" class="form-control" required>
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    {{-- 🔹 Rekap Data --}}
    @if(!$kelas || !$jurusan || !$tahunAjar || !$tanggal)
      <div class="alert alert-secondary text-center">
        Silakan isi semua filter dan klik <b>Tampilkan</b> untuk melihat data.
      </div>
    @elseif(($rekapAbsensi ?? collect())->count() == 0)
      <div class="alert alert-warning text-center">Tidak ada data absensi untuk filter ini.</div>
    @else
      {{-- 🔹 Tabel Rekap --}}
      <div class="table-responsive mb-4">
        <table class="table table-bordered text-center align-middle shadow-sm">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Kelas</th>
              <th>Jurusan</th>
              <th>Tahun Ajar</th>
              <th>Total</th>
              <th>Hadir</th>
              <th>Sakit</th>
              <th>Izin</th>
              <th>Alpha</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rekapAbsensi as $r)
              <tr>
                <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $r->kelas_siswa }}</td>
                <td>{{ $r->jurusan_siswa }}</td>
                <td>{{ $r->tahunAjar }}</td>
                <td>{{ $r->total }}</td>
                <td class="text-success fw-bold">{{ $r->hadir }}</td>
                <td class="text-warning fw-bold">{{ $r->sakit }}</td>
                <td class="text-primary fw-bold">{{ $r->izin }}</td>
                <td class="text-danger fw-bold">{{ $r->alpha }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- 🔹 Detail Siswa Langsung Ditampilkan --}}
      @php
        $data = $rekapAbsensi->first();
        $siswaDetail = \App\Models\Absensi::join('siswa','absensi.NIS','=','siswa.NIS')
          ->where('siswa.kelas_siswa', $data->kelas_siswa)
          ->where('siswa.jurusan_siswa', $data->jurusan_siswa)
          ->where('absensi.tahunAjar', $data->tahunAjar)
          ->whereDate('absensi.tanggal', $data->tanggal)
          ->select('absensi.*','siswa.nama_siswa','siswa.NIS')
          ->orderBy('siswa.nama_siswa')
          ->get();
      @endphp

      <form method="POST" action="{{ route('absensi.update') }}">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $data->tanggal }}">
        <input type="hidden" name="kelas" value="{{ $data->kelas_siswa }}">
        <input type="hidden" name="tahunAjar" value="{{ $data->tahunAjar }}">

        <table class="table table-bordered text-center align-middle">
          <thead class="table-secondary">
            <tr>
              <th>No</th>
              <th>Nama Siswa</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($siswaDetail as $index => $s)
            <tr>
              <td>{{ $index+1 }}</td>
              <td>{{ $s->nama_siswa }}</td>
              <td>
                <input type="hidden" name="NIS[]" value="{{ $s->NIS }}">
                <select name="status[]" class="form-select form-select-sm">
                  @foreach(['Hadir','Sakit','Izin','Alpha'] as $st)
                    <option value="{{ $st }}" {{ $s->status == $st ? 'selected' : '' }}>
                      {{ $st }}
                    </option>
                  @endforeach
                </select>
              </td>
              <td>
                <input type="text" name="keterangan[]" class="form-control form-control-sm" value="{{ $s->keterangan }}">
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <div class="text-center mt-3">
          <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
        </div>
      </form>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
