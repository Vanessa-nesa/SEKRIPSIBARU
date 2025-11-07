@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold text-primary mb-3">📊 Dashboard Admin</h3>
    <p>Selamat datang, <strong>{{ session('nama') }}</strong>!</p>
    <p>Gunakan menu di sidebar untuk mengelola kelas, siswa, dan data lainnya.</p>
</div>
@endsection
