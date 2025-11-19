<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bimbingan Konseling - Guru BK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color:#f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    .navbar-brand { font-weight:bold; }

    /* 🔹 Container utama geser ke bawah karena navbar fixed */
    .main-container {
      padding-top: 70px;
    }

    h3 {
      margin-bottom: 40px;
      font-weight: 600;
    }

    .table {
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      border-radius: 8px;
    }

    .alert {
      border-radius: 10px;
    }
  </style>
</head>

<body>
<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Guru BK Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="#" id="tab-bimbingan-btn">Input Bimbingan</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekapabsen-btn">Rekap Absensi</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekappelanggaran-btn">Rekap Pelanggaran</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekapbimbingan-btn">Rekap Bimbingan</a></li>
        <li class="nav-item"><a class="nav-link text-danger fw-bold" href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container main-container">

  {{-- ===========================================================
       TAB 1 : INPUT BIMBINGAN
  ============================================================ --}}
  <div id="tab-bimbingan">
    <h3 class="text-center"><i class="bi bi-person-lines-fill"></i> Input Bimbingan Konseling</h3>

    @if(session('success'))
      <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('bimbingan') }}" class="row justify-content-center mb-4">
      <input type="hidden" name="mode" value="bimbingan">

      <div class="col-md-3 mb-2">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas??'') == $k->nama_kelas ? 'selected' : '' }}>
              {{ $k->nama_kelas }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3 mb-2">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">-- Pilih Jurusan --</option>
          @foreach(['IPA','IPS'] as $j)
            <option value="{{ $j }}" {{ ($jurusan??'') == $j ? 'selected' : '' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>

      @php
        $tahunSekarang = date('Y');
        $tahunList = [
          ($tahunSekarang - 1) . '/' . $tahunSekarang,
          "$tahunSekarang/" . ($tahunSekarang + 1),
          ($tahunSekarang + 1) . '/' . ($tahunSekarang + 2)
        ];
      @endphp
      <div class="col-md-3 mb-2">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
          <option value="">-- Pilih Tahun Ajar --</option>
          @foreach($tahunList as $t)
            <option value="{{ $t }}" {{ ($tahunAjar??'') == $t ? 'selected' : '' }}>
              {{ $t }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2 d-flex align-items-end mb-2">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    {{-- ✅ Jika ada data siswa --}}
    @if(isset($siswa) && $siswa->count() > 0)
    <form action="{{ route('bimbingan.store') }}" method="POST">
      @csrf
      <input type="hidden" name="tahunAjar" value="{{ $tahunAjar }}">
      <div class="row mb-3 justify-content-center">
        <div class="col-md-3">
          <label class="form-label fw-bold">Tanggal Bimbingan</label>
          <input type="date" name="tanggal_bimbingan" class="form-control" required>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-secondary">
            <tr>
              <th>Nama Siswa</th>
              <th>Bimbingan Ke-</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($siswa as $i => $s)
            <tr>
              <input type="hidden" name="data[{{ $i }}][NIS]" value="{{ $s->NIS }}">
              <td class="fw-semibold">{{ $s->nama_siswa }}</td>
              <td><input type="number" name="data[{{ $i }}][bimbingan_ke]" class="form-control form-control-sm" min="1"></td>
              <td><input type="text" name="data[{{ $i }}][notes]" class="form-control form-control-sm" placeholder="Tuliskan catatan bimbingan..."></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">💾 Simpan Semua</button>
      </div>
    </form>
    @endif
  </div>

  {{-- ===========================================================
       TAB 2 : REKAP ABSENSI
  ============================================================ --}}
  <div id="tab-rekapabsen" style="display:none;">
    <h3 class="text-center"><i class="bi bi-calendar-check"></i> Rekap Absensi Siswa</h3>

    <form method="GET" action="{{ route('gurubk.rekap.absensi') }}" class="row justify-content-center mb-4">
      <div class="col-md-3">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">-- Pilih Jurusan --</option>
          <option value="IPA">IPA</option>
          <option value="IPS">IPS</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
          <option value="">-- Pilih Tahun Ajar --</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}">{{ $t }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    @if(isset($rekapAbsensi) && $rekapAbsensi->count() > 0)
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>No</th><th>Nama Siswa</th><th>Kelas</th><th>Jurusan</th><th>Tanggal</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rekapAbsensi as $i => $a)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $a->nama_siswa }}</td>
              <td>{{ $a->kelas_siswa }}</td>
              <td>{{ $a->jurusan_siswa }}</td>
              <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
              <td>{{ $a->status ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @elseif(request()->has('kelas'))
      <div class="alert alert-warning text-center">Belum ada data absensi.</div>
    @endif
  </div>

  {{-- ===========================================================
       TAB 3 : REKAP PELANGGARAN
  ============================================================ --}}
  <div id="tab-rekappelanggaran" style="display:none;">
    <h3 class="text-center"><i class="bi bi-clipboard2-check"></i> Rekap Pelanggaran Siswa</h3>

    <form method="GET" action="{{ route('gurubk.rekap.pelanggaran') }}" class="row justify-content-center mb-4">
      <div class="col-md-3">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas??'')==$k->nama_kelas?'selected':'' }}>{{ $k->nama_kelas }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-bold">Jurusan</label>
        <select name="jurusan" class="form-select" required>
          <option value="">-- Pilih Jurusan --</option>
          @foreach(['IPA','IPS'] as $j)
            <option value="{{ $j }}" {{ ($jurusan??'')==$j?'selected':'' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select name="tahunAjar" class="form-select" required>
          <option value="">-- Pilih Tahun Ajar --</option>
          @foreach($daftar_tahunAjar as $t)
            <option value="{{ $t }}" {{ ($tahunAjar??'')==$t?'selected':'' }}>{{ $t }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-bold">Tanggal Pelanggaran</label>
        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    @if(isset($rekap) && $rekap->count() > 0)
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>No</th><th>Nama Siswa</th><th>Kelas</th><th>Jurusan</th><th>Tanggal</th><th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rekap as $i => $r)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $r->nama_siswa }}</td>
              <td>{{ $r->kelas_siswa }}</td>
              <td>{{ $r->jurusan_siswa }}</td>
              <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
              <td>{{ $r->notes ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @elseif(request()->has('kelas'))
      <div class="alert alert-warning text-center">Belum ada data pelanggaran.</div>
    @endif
  </div>

 {{-- ===========================================================
     TAB 4 : REKAP BIMBINGAN
============================================================ --}}
<div id="tab-rekapbimbingan" style="display:none;">
  <h3 class="text-center"><i class="bi bi-journal-text"></i> Rekap Bimbingan Siswa</h3>

  <form method="GET" action="{{ route('gurubk.rekap.bimbingan') }}" class="row justify-content-center mb-4">
    <div class="col-md-3">
      <label class="form-label fw-bold">Kelas</label>
      <select name="kelas" class="form-select" required>
        <option value="">-- Pilih Kelas --</option>
        @foreach($daftar_kelas as $k)
          <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-bold">Jurusan</label>
      <select name="jurusan" class="form-select" required>
        <option value="">-- Pilih Jurusan --</option>
        <option value="IPA">IPA</option>
        <option value="IPS">IPS</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-bold">Tahun Ajar</label>
      <select name="tahunAjar" class="form-select" required>
        <option value="">-- Pilih Tahun Ajar --</option>
        @foreach($daftar_tahunAjar as $t)
          <option value="{{ $t }}">{{ $t }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label fw-bold">Tanggal Bimbingan</label>
      <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
    </div>
  </form>

  @if(isset($rekapBimbingan) && $rekapBimbingan->count() > 0)
    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>Tanggal</th>
            <th>Catatan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rekapBimbingan as $i => $b)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $b->nama_siswa }}</td>
            <td>{{ $b->kelas_siswa }}</td>
            <td>{{ $b->jurusan_siswa }}</td>
            <td>{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->format('d-m-Y') }}</td>
            <td>{{ $b->notes ?? '-' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @elseif(request()->has('kelas'))
    <div class="alert alert-warning text-center">Belum ada data bimbingan.</div>
  @endif
</div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const tabs = {
  bimbingan: document.getElementById('tab-bimbingan'),
  rekapabsen: document.getElementById('tab-rekapabsen'),
  rekappelanggaran: document.getElementById('tab-rekappelanggaran'),
  rekapbimbingan: document.getElementById('tab-rekapbimbingan')
};

// Tombol Tab Switching
document.getElementById('tab-bimbingan-btn').onclick = () => {
  Object.values(tabs).forEach(tab => tab.style.display = 'none');
  tabs.bimbingan.style.display = 'block';
};
document.getElementById('tab-rekapabsen-btn').onclick = () => {
  Object.values(tabs).forEach(tab => tab.style.display = 'none');
  tabs.rekapabsen.style.display = 'block';
};
document.getElementById('tab-rekappelanggaran-btn').onclick = () => {
  Object.values(tabs).forEach(tab => tab.style.display = 'none');
  tabs.rekappelanggaran.style.display = 'block';
};
document.getElementById('tab-rekapbimbingan-btn').onclick = () => {
  Object.values(tabs).forEach(tab => tab.style.display = 'none');
  tabs.rekapbimbingan.style.display = 'block';
};
</script>
</body>
</html>
