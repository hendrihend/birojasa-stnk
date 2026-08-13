<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Biro Jasa</title>
    @vite('resources/css/app.css')
    <!-- fonts awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
            <!-- Logo -->
            <div class="flex justify-center">
                <div class="w-20 h-20 flex items-center justify-center">
                    <i class="fa-solid fa-shield-halved text-gray-800 text-2xl"></i>
                </div>
            </div>

            <!-- Nama Aplikasi -->
            <div class="text-center mt-0 mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    Biro Jasa STNK
                </h2>

                <p class="text-sm text-gray-500">
                    Management System
                </p>
            </div>

            <!-- Judul Login -->
            <h2 class="text-2xl font-bold text-gray-800 text-center mt-6 mb-5">
                Login
            </h2>

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                </div>
                <div class="text-right">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">
                        Lupa Kata Sandi?
                    </a>
                </div>
                <button type="submit" class="w-full rounded-lg bg-gray-800 py-3 text-white font-semibold hover:bg-gray-900 transition duration-200">Login</button>
                <p class="text-sm text-center text-gray-600 mt-4">Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Daftar
                    </a>
                </p>
            </form>

    </div>

</body>
</html>
