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
    .btn-kembali {
    background-color: #343a40;
    color: #fff;
    padding: 8px 16px;
    border-radius: 8px;
    transition: 0.2s;
  }
  .btn-kembali:hover {
    background-color: #23272b;
    color: #fff;
  }
  .input-box {
    min-width: 230px;     /* biar kotaknya sama panjang */
}
    .input-normal {
        width: 100%;        /* panjang normal */
        max-width: 250px;     /* biar responsive */
    }

  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
    Guru BK Panel
    @if(session('nama'))
        <span class="ms-2 fw-bold text-white">| {{ session('nama') }}</span>
    @endif
</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="#" id="tab-bimbingan-btn">Input Bimbingan</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekapabsen-btn">Rekap Absensi</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekapbimbingan-btn">Rekap Bimbingan</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="tab-rekappelanggaran-btn">Rekap Pelanggaran</a></li>
        <li class="nav-item"><a class="nav-link text-danger fw-bold" href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">

  <!--Tombol Kembali ke Menu Guru BK -->
  <div class="mb-4">
    <a href="{{ route('kebutuhanbk') }}" 
       class="btn btn-kembali shadow-sm d-inline-flex align-items-center">
      <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Menu Guru BK
    </a>
  </div>


  {{-- ===========================================================
       TAB 1 : INPUT BIMBINGAN
  ============================================================ --}}
  <div id="tab-bimbingan">
    <h3 class="text-center mb-4">Input Bimbingan Konseling</h3>

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
      <option value="{{ $k->nama_kelas }}" 
        {{ ($kelas ?? '') == $k->nama_kelas ? 'selected' : '' }}>
        {{ $k->nama_kelas }}
      </option>
    @endforeach
  </select>
</div>

<div class="col-md-3 mb-2">
  <label class="form-label fw-bold">Jurusan</label>
  <select name="jurusan" class="form-select" required>
    <option value="">-- Pilih Jurusan --</option>
@foreach($daftar_jurusan as $j)
    <option value="{{ $j }}" {{ ($jurusan??'') == $j ? 'selected' : '' }}>
        {{ $j }}
    </option>
@endforeach

  </select>
</div>

<div class="col-md-3 mb-2">
  <label class="form-label fw-bold">Tahun Ajar</label>
  <select name="tahunAjar" class="form-select" required>
    <option value="">-- Pilih Tahun Ajar --</option>
    @foreach($daftar_tahunAjar as $t)
      <option value="{{ $t }}" {{ ($tahunAjar??'') == $t ? 'selected' : '' }}>
        {{ $t }}
      </option>
    @endforeach
  </select>
  </div>

  <!--Tombol Tampilkan-->
      <div class="col-md-2 d-flex align-items-end mb-2">
        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
      </div>
    </form>

    @if($siswa->count() > 0)
    <form action="{{ route('bimbingan.store') }}" method="POST">

    <input type="hidden" name="kelas" value="{{ $kelas }}">
    <input type="hidden" name="jurusan" value="{{ $jurusan }}">

    @csrf
    <input type="hidden" name="tahunAjar" value="{{ $tahunAjar }}">

    <div class="row mb-3 justify-content-center">
        <div class="col-md-3">
            <label class="form-label fw-bold">Tanggal Bimbingan</label>
            <input type="date" name="tanggal" class="form-control" required>

        </div>
    </div>


      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm">
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
    <h3 class="text-center mb-4">Rekap Absensi Siswa</h3>

    <div class="mx-auto" style="max-width:1100px; margin-top:30px;">
       <form method="GET" action="{{ route('bimbingan') }}"
      class="row g-4 justify-content-center">

    <input type="hidden" name="mode" value="rekapbk">

    <!-- Kelas -->
    <div class="col-md-2">
        <label class="form-label fw-bold">Kelas</label>
        <select class="form-select" name="kelas">
            @foreach($daftar_kelas as $k)
                <option value="{{ $k->nama_kelas }}" {{ request('kelas')==$k->nama_kelas?'selected':'' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Jurusan -->
    <div class="col-md-2">
        <label class="form-label fw-bold">Jurusan</label>
        <select class="form-select" name="jurusan">
            @foreach($daftar_jurusan as $j)
                <option value="{{ $j }}" {{ request('jurusan')==$j?'selected':'' }}>
                    {{ $j }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tahun Ajar -->
    <div class="col-md-2">
        <label class="form-label fw-bold">Tahun Ajar</label>
        <select class="form-select" name="tahunAjar">
            @foreach($daftar_tahunAjar as $t)
                <option value="{{ $t }}" {{ request('tahunAjar')==$t?'selected':'' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tanggal Awal -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Tanggal Awal</label>
        <input type="date" class="form-control" name="tanggal_awal"
               value="{{ request('tanggal_awal') }}" required>
    </div>

    <!-- Tanggal Akhir -->
    <div class="col-md-3">
        <label class="form-label fw-bold">Tanggal Akhir</label>
        <input type="date" class="form-control" name="tanggal_akhir"
               value="{{ request('tanggal_akhir') }}" required>
    </div>

    <!-- Tombol -->
    <div class="col-md-2 d-flex align-items-end justify-content-end">
        <button class="btn btn-primary w-100 py-2">Tampilkan</button>
    </div>
</form>

    </div>

    {{-- Tabel Rekap --}}
    @if(isset($rekap) && $rekap->count() > 0)
        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $r)
                        <tr>
                            <td>{{ $r->nama_siswa }}</td>
                            <td>{{ $r->status }}</td>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif(request()->mode=='rekapbk')
        <div class="alert alert-warning text-center mt-4">
            Tidak ada data absensi untuk filter ini.
        </div>
    @endif
</div>






  {{-- ===========================================================
       TAB 3 : REKAP BIMBINGAN
  ============================================================ --}}
  <div id="tab-rekapbimbingan" style="display:none;">
  <h3 class="text-center mb-4">Rekap Bimbingan Konseling</h3>

  <div class="mx-auto" style="max-width:900px;">
<form method="GET" action="{{ route('bimbingan') }}" class="row g-3 mb-4">
    <input type="hidden" name="mode" value="rekapbimbingan">

    <!-- KELAS -->
    <div class="col-md-2">
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

    <!-- JURUSAN -->
    <div class="col-md-2">
      <label class="form-label fw-bold">Jurusan</label>
      <select name="jurusan" class="form-select" required>
        <option value="">-- Pilih Jurusan --</option>
        @foreach(['IPA','IPS'] as $j)
          <option value="{{ $j }}" {{ ($jurusan??'')==$j?'selected':'' }}>{{ $j }}</option>
        @endforeach
      </select>
    </div>

    <!-- TAHUN AJAR -->
    <div class="col-md-2">
      <label class="form-label fw-bold">Tahun Ajar</label>
      <select name="tahunAjar" class="form-select" required>
        <option value="">-- Pilih Tahun Ajar --</option>
        @foreach($daftar_tahunAjar as $t)
          <option value="{{ $t }}" {{ ($tahunAjar??'')==$t?'selected':'' }}>{{ $t }}</option>
        @endforeach
      </select>
    </div>

    <!-- Tanggal Awal -->
    <div class="col-md-3">
      <label class="form-label fw-bold">Tanggal Awal</label>
      <input type="date" name="tanggal_awal_bimbingan" class="form-control"
             value="{{ request('tanggal_awal_bimbingan') }}" required>
    </div>

    <!-- Tanggal Akhir -->
    <div class="col-md-3">
      <label class="form-label fw-bold">Tanggal Akhir</label>
      <input type="date" name="tanggal_akhir_bimbingan" class="form-control"
             value="{{ request('tanggal_akhir_bimbingan') }}" required>
    </div>

    <!-- Tombol -->
    <div class="col-12 text-center mt-3">
      <button type="submit" class="btn btn-primary px-5">Tampilkan</button>
    </div>

</form>


    @if(isset($riwayat)&&$riwayat->count()>0)
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center shadow-sm">
        <thead class="table-secondary">
  <tr>
    <th>Tanggal</th>
    <th>Nama Siswa</th>
    <th>Bimbingan Ke-</th>
    <th>Catatan</th>
    <th>Aksi</th>
  </tr>
</thead>

        <tbody>
        @foreach($riwayat as $r)
      <tr>
        <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
        <td>{{ $r->siswa->nama_siswa ?? '-' }}</td>
        <td>{{ $r->bimbingan_ke }}</td>
        <td>{{ $r->notes ?? '-' }}</td>

    <td>
      <button class="btn btn-warning btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#editModal"
        data-id="{{ $r->id_bimbingan }}"
        data-nama="{{ $r->siswa->nama_siswa }}"
        data-pelanggaran="{{ $r->pelanggaran }}"
        data-bimbingan="{{ $r->bimbingan_ke }}"
        data-notes="{{ $r->notes }}">
          Edit
</button>

      <form action="{{ route('bimbingan.delete', $r->id_bimbingan) }}" 
            method="POST" 
            style="display:inline-block;"
            onsubmit="return confirm('Yakin hapus data ini?')">
          @csrf
          @method('DELETE')
          <button class="btn btn-danger btn-sm">Hapus</button>
      </form>
    </td>
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
<!-- ============================
      MODAL EDIT BIMBINGAN
=============================== -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="editForm" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Edit Bimbingan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="fw-bold">Nama Siswa</label>
            <input type="text" class="form-control" id="editNama" disabled>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Bimbingan Ke-</label>
            <input type="number" class="form-control" name="bimbingan_ke" id="editBimbingan">
          </div>

          <div class="mb-3">
            <label class="fw-bold">Catatan</label>
            <textarea class="form-control" name="notes" id="editNotes"></textarea>
          </div>

          <!-- Hidden redirect data -->
          <input type="hidden" name="kelas" value="{{ $kelas }}">
          <input type="hidden" name="jurusan" value="{{ $jurusan }}">
          <input type="hidden" name="tahunAjar" value="{{ $tahunAjar }}">
          <input type="hidden" name="tanggal_awal_bimbingan" value="{{ request('tanggal_awal_bimbingan') }}">
          <input type="hidden" name="tanggal_akhir_bimbingan" value="{{ request('tanggal_akhir_bimbingan') }}">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>

    </div>
  </div>
</div>


{{-- ===========================================================
     TAB 4 : REKAP PELANGGARAN
=========================================================== --}}
<div id="tab-rekappelanggaran" style="display:none;">
  <h3 class="text-center mb-4">Rekap Pelanggaran Siswa</h3>

<form method="GET" action="{{ route('bimbingan') }}" class="mb-4">

    <input type="hidden" name="mode" value="rekappelanggaran">

    <div class="row g-4 justify-content-center">

        <!-- Kelas -->
        <div class="col-md-2">
            <label class="form-label fw-bold">Kelas</label>
            <select name="kelas" class="form-select" required>
                @foreach($daftar_kelas as $k)
                    <option value="{{ $k->nama_kelas }}" {{ request('kelas')==$k->nama_kelas?'selected':'' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Jurusan -->
        <div class="col-md-2">
            <label class="form-label fw-bold">Jurusan</label>
            <select name="jurusan" class="form-select" required>
                @foreach($daftar_jurusan as $j)
                    <option value="{{ $j }}" {{ request('jurusan')==$j?'selected':'' }}>
                        {{ $j }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tahun Ajar -->
        <div class="col-md-2">
            <label class="form-label fw-bold">Tahun Ajar</label>
            <select name="tahunAjar" class="form-select" required>
                @foreach($daftar_tahunAjar as $t)
                    <option value="{{ $t }}" {{ request('tahunAjar')==$t?'selected':'' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal Awal -->
        <div class="col-md-2">
            <label class="form-label fw-bold">Tanggal Awal</label>
            <input type="date" name="tanggal_awal_pelanggaran" class="form-control"
                   value="{{ request('tanggal_awal_pelanggaran') }}" required>
        </div>

        <!-- Tanggal Akhir -->
        <div class="col-md-2">
            <label class="form-label fw-bold">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir_pelanggaran" class="form-control"
                   value="{{ request('tanggal_akhir_pelanggaran') }}" required>
        </div>

        <!-- Tombol -->
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Tampilkan</button>
        </div>

    </div>

</form>








  @if(isset($rekap_pelanggaran) && $rekap_pelanggaran->count() > 0)
    <div class="mx-auto" style="max-width:1100px;">
  <div class="table-responsive">
    <table class="table table-bordered text-center align-middle shadow-sm">

      <thead style="background:#000; color:white;">
        <thead class="table-dark">
          <tr>
            <th>Tanggal</th>
            <th>Nama Siswa</th>
            <th>Jenis Pelanggaran</th>
            <th>Catatan</th>
          </tr>
        </thead>
        <tbody>
        @foreach($rekap_pelanggaran as $rp)
          <tr>
            <td>{{ \Carbon\Carbon::parse($rp->tanggal)->format('d-m-Y') }}</td>
            <td>{{ $rp->nama_siswa }}</td>
            <td>{{ $rp->nama_pelanggaran }}</td>
            <td>{{ $rp->notes ?? '-' }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @elseif(request()->has('mode') && request('mode')==='rekappelanggaran')
    <div class="alert alert-warning text-center">
        Tidak ada data pelanggaran untuk filter ini.
    </div>
  @endif

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const editModal = document.getElementById('editModal');

    editModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;

        let id = button.getAttribute('data-id');
        let nama = button.getAttribute('data-nama');
        let pelanggaran = button.getAttribute('data-pelanggaran');
        let bimbingan = button.getAttribute('data-bimbingan');
        let notes = button.getAttribute('data-notes');

        document.getElementById('editNama').value = nama;
        document.getElementById('editPelanggaran').value = pelanggaran;
        document.getElementById('editBimbingan').value = bimbingan;
        document.getElementById('editNotes').value = notes;

        // Set action URL untuk update
        document.getElementById('editForm').action = "/bimbingan/update/" + id;
    });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const tabBimbingan = document.getElementById('tab-bimbingan');
const tabRekapAbsen = document.getElementById('tab-rekapabsen');
const tabRekapBimbingan = document.getElementById('tab-rekapbimbingan');
const tabRekapPelanggaran = document.getElementById('tab-rekappelanggaran');

document.getElementById('tab-rekappelanggaran-btn').onclick = () => {
    tabBimbingan.style.display='none';
    tabRekapAbsen.style.display='none';
    tabRekapBimbingan.style.display='none';
    tabRekapPelanggaran.style.display='block';
};

document.getElementById('tab-bimbingan-btn').onclick = () => {
    tabBimbingan.style.display='block';
    tabRekapAbsen.style.display='none';
    tabRekapBimbingan.style.display='none';
    tabRekapPelanggaran.style.display='none';
};

document.getElementById('tab-rekapabsen-btn').onclick = () => {
    tabBimbingan.style.display='none';
    tabRekapAbsen.style.display='block';
    tabRekapBimbingan.style.display='none';
    tabRekapPelanggaran.style.display='none';
};

document.getElementById('tab-rekapbimbingan-btn').onclick = () => {
    tabBimbingan.style.display='none';
    tabRekapAbsen.style.display='none';
    tabRekapBimbingan.style.display='block';
    tabRekapPelanggaran.style.display='none';
};

document.addEventListener('DOMContentLoaded', () => {
    const m = "{{ request('mode') }}";

    if (m === 'rekapbk') {
        tabBimbingan.style.display='none';
        tabRekapAbsen.style.display='block';
        tabRekapBimbingan.style.display='none';
        tabRekapPelanggaran.style.display='none';
    }
    else if (m === 'rekapbimbingan') {
        tabBimbingan.style.display='none';
        tabRekapAbsen.style.display='none';
        tabRekapBimbingan.style.display='block';
        tabRekapPelanggaran.style.display='none';
    }
    else if (m === 'rekappelanggaran') {
        tabBimbingan.style.display='none';
        tabRekapAbsen.style.display='none';
        tabRekapBimbingan.style.display='none';
        tabRekapPelanggaran.style.display='block';
    }
    else {
        tabBimbingan.style.display='block';
        tabRekapAbsen.style.display='none';
        tabRekapBimbingan.style.display='none';
        tabRekapPelanggaran.style.display='none';
    }
});

</script>
</body>
</html>