<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
    private $id_kelas;
    private $kelas_siswa;
    private $jurusan_siswa;

    public function __construct($id_kelas, $kelas_siswa, $jurusan_siswa)
    {
        $this->id_kelas = $id_kelas;
        $this->kelas_siswa = $kelas_siswa;
        $this->jurusan_siswa = $jurusan_siswa;
    }

    public function model(array $row)
    {
        if (!isset($row[0]) || strtolower(trim($row[0])) === 'nis') {
            return null;
        }

        $nis = $row[0] ?? null;
        $nama = $row[1] ?? null;

        if (empty($nis) || empty($nama)) return null;

        return new Siswa([
            'NIS'           => $nis,
            'nama_siswa'    => trim($nama),
            'id_kelas'      => $this->id_kelas,
            'kelas_siswa'   => $this->kelas_siswa,
            'jurusan_siswa' => $this->jurusan_siswa,
        ]);
    }
}
