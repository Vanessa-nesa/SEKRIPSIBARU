<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bimbingan Konseling</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color:#f8f9fa; }
    .navbar-brand { font-weight:bold; }
  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">📘 Guru BK Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="#" id="tab-bimbingan-btn">Input Bimbingan</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekapabsen-btn">Rekap Absensi</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekapbimbingan-btn">Rekap Bimbingan</a></li>
        <li class="nav-item"><a class="nav-link text-danger fw-bold" href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">

  {{-- ===========================================================
       TAB 1 : INPUT BIMBINGAN
  ============================================================ --}}
  <div id="tab-bimbingan">
    <h3 class="text-center mb-4">📘 Input Bimbingan Konseling</h3>

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

    @if($siswa->count() > 0)
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
        <table class="table table-bordered align-middle text-center shadow-sm">
          <thead class="table-secondary">
            <tr>
              <th>Nama Siswa</th>
              <th>Pelanggaran</th>
              <th>Bimbingan Ke-</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($siswa as $i => $s)
            <tr>
              <input type="hidden" name="data[{{ $i }}][NIS]" value="{{ $s->NIS }}">
              <td class="fw-semibold">{{ $s->nama_siswa }}</td>
              <td><input type="text" name="data[{{ $i }}][pelanggaran]" class="form-control form-control-sm" placeholder="Isi pelanggaran"></td>
              <td><input type="number" name="data[{{ $i }}][bimbingan_ke]" class="form-control form-control-sm" min="1"></td>
              <td><input type="text" name="data[{{ $i }}][notes]" class="form-control form-control-sm" placeholder="Catatan"></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">💾 Simpan Semua</button>
      </div>
    </form>
    @else
      <div class="alert alert-warning text-center mt-4">
        Tidak ada siswa di kelas {{ $kelas ?? '-' }} jurusan {{ $jurusan ?? '-' }}.
      </div>
    @endif
  </div>

  {{-- ===========================================================
       TAB 2 : REKAP ABSENSI
  ============================================================ --}}
  <div id="tab-rekapabsen" style="display:none;">
    <h3 class="text-center mb-4">📊 Rekap Absensi Siswa</h3>

    <form method="GET" action="{{ route('bimbingan') }}" class="row justify-content-center mb-4">
      <input type="hidden" name="mode" value="rekapbk">

      <div class="col-md-3">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas??'')==$k->nama_kelas?'selected':'' }}>
              {{ $k->nama_kelas }}
            </option>
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

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    @if(isset($rekap) && $rekap->count() > 0)
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle shadow-sm">
          <thead class="table-dark">
            <tr>
              <th>Tanggal</th><th>Kelas</th><th>Jurusan</th><th>Total</th>
              <th class="text-success">Hadir</th>
              <th class="text-warning">Sakit</th>
              <th class="text-primary">Izin</th>
              <th class="text-danger">Alpha</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rekap as $r)
              <tr>
                <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $r->kelas }}</td>
                <td>{{ $r->jurusan }}</td>
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
    @elseif(isset($rekap))
      <div class="alert alert-warning text-center">Belum ada data absensi untuk kelas dan jurusan ini.</div>
    @endif
  </div>

  {{-- ===========================================================
       TAB 3 : REKAP BIMBINGAN
  ============================================================ --}}
  <div id="tab-rekapbimbingan" style="display:none;">
    <h3 class="text-center mb-4">📅 Rekap Bimbingan Konseling</h3>

    <form method="GET" action="{{ route('bimbingan') }}" class="row justify-content-center g-3 mb-4">
      <input type="hidden" name="mode" value="rekapbimbingan">
      <div class="col-md-3">
        <label class="form-label fw-bold">Kelas</label>
        <select name="kelas" class="form-select" required>
          <option value="">-- Pilih Kelas --</option>
          @foreach($daftar_kelas as $k)
            <option value="{{ $k->nama_kelas }}" {{ ($kelas??'')==$k->nama_kelas?'selected':'' }}>
              {{ $k->nama_kelas }}
            </option>
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
        <label class="form-label fw-bold">Tanggal Bimbingan</label>
        <select name="tanggal_riwayat" class="form-select" required>
          <option value="">-- Pilih Tanggal --</option>
          @foreach($daftar_tanggal??[] as $t)
            <option value="{{ $t }}" {{ ($tanggal_riwayat??'')==$t?'selected':'' }}>
              {{ \Carbon\Carbon::parse($t)->format('d-m-Y') }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    @if(isset($riwayat)&&$riwayat->count()>0)
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center shadow-sm">
        <thead class="table-secondary">
          <tr><th>Tanggal</th><th>Nama Siswa</th><th>Pelanggaran</th><th>Bimbingan Ke-</th><th>Catatan</th></tr>
        </thead>
        <tbody>
          @foreach($riwayat as $r)
          <tr>
            <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
            <td>{{ $r->siswa->nama_siswa ?? '-' }}</td>
            <td>{{ $r->pelanggaran }}</td>
            <td>{{ $r->bimbingan_ke }}</td>
            <td>{{ $r->notes ?? '-' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @elseif(request()->has('tanggal_riwayat'))
      <div class="alert alert-warning text-center">Tidak ada data bimbingan untuk tanggal ini.</div>
    @endif
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const tabBimbingan=document.getElementById('tab-bimbingan');
const tabRekapAbsen=document.getElementById('tab-rekapabsen');
const tabRekapBimbingan=document.getElementById('tab-rekapbimbingan');

document.getElementById('tab-bimbingan-btn').onclick=()=>{tabBimbingan.style.display='block';tabRekapAbsen.style.display='none';tabRekapBimbingan.style.display='none';};
document.getElementById('tab-rekapabsen-btn').onclick=()=>{tabBimbingan.style.display='none';tabRekapAbsen.style.display='block';tabRekapBimbingan.style.display='none';};
document.getElementById('tab-rekapbimbingan-btn').onclick=()=>{tabBimbingan.style.display='none';tabRekapAbsen.style.display='none';tabRekapBimbingan.style.display='block';};

document.addEventListener('DOMContentLoaded',()=>{
  const m="{{ request('mode') }}";
  if(m==='rekapbk'){tabBimbingan.style.display='none';tabRekapAbsen.style.display='block';}
  else if(m==='rekapbimbingan'){tabBimbingan.style.display='none';tabRekapAbsen.style.display='none';tabRekapBimbingan.style.display='block';}
});
</script>
</body>
</html>
