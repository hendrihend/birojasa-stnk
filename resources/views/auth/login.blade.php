<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Biro Jasa</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="login-wrapper">
        <!-- Placeholder Logo & Teks -->
        <div class="logo-box">
            <span class="cross-line"></span>
        </div>

        <!-- Judul -->
        <h1 class="login-title">Login</h1>

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email" required>
            </div>

            <div class="input-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
            </div>

            <!-- Tautan Lupa Sandi (Sesuai kotak abu-abu di kanan bawah password) -->
            <div class="forgot-password-container">
                <a href="{{ route('password.request') }}" class="forgot-password-link">Lupa Kata Sandi?</a>
            </div>

            <button type="submit" class="btn-login">Login</button>
            <button type="submit" class="btn-register">
                <a href="{{ route('register') }}" >Daftar
                </a>
            </button>
        </form>
    </div>

</body>
</html>
