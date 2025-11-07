<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width: 380px;">
            <h3 class="text-center mb-4 fw-bold text-primary">Login</h3>

            {{-- Pesan sukses (misal setelah registrasi) --}}
            @if(session('success'))
                <div class="alert alert-success text-center py-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan error --}}
            @if(session('error'))
                <div class="alert alert-danger text-center py-2">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-semibold">Masuk</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <small>Belum punya akun? 
                    <a href="{{ route('regis') }}" class="fw-bold text-decoration-none">Daftar Sekarang</a>
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
