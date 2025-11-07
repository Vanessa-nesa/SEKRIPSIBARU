<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/regis.css', 'resources/js/app.js'])
</head>
<body>

    <div class="register-container">
        <div class="register-card shadow p-4">
            <h2 class="text-center mb-4">Registrasi Akun</h2>

            {{-- Pesan sukses / error --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Pesan validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Registrasi --}}
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" value="{{ old('fullname') }}" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="nim" class="form-control" placeholder="NIM" value="{{ old('nim') }}" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                </div>

                {{-- Dropdown Role --}}
                <div class="mb-3">
                    <select name="role" class="form-select" required>
                        <option value="" selected disabled>Pilih Role</option>
                        <option value="Guru BK">Guru BK</option>
                        <option value="Wali Kelas">Wali Kelas</option>
                        <option value="Kepala Sekolah & Wakil Kepala Sekolah">Kepala Sekolah & Wakil Kepala Sekolah</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-2">Daftar</button>
            </form>

            <div class="text-center mt-3">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
