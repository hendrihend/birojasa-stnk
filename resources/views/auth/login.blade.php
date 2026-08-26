<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistem Biro Jasa</title>
    @vite ('resources/css/app.css')
</head>
<body>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg">

            <!-- Logo -->
            <div class="flex justify-center">
                <div class="flex h-20 w-20 items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-20 object-contain"/>
                </div>
            </div>

            <!-- Nama Aplikasi -->
            <div class="mb-4 mt-0 text-center">
                <h2 class="text-lg font-semibold text-gray-800">Biro Jasa STNK</h2>
                <p class="text-sm text-gray-500">Management System</p>
            </div>

            <!-- Judul Login -->
            <h2 class="mb-5 mt-6 text-center text-2xl font-bold text-gray-900">Login</h2>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"/>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">Kata Sandi</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"/>
                </div>

                <!-- Lupa Password -->
                <div class="text-right">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lupa Kata Sandi?</a>
                </div>
            

                <!-- Button -->
                <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Login</button>

                <!-- Daftar -->
                <p class="mt-4 text-center text-sm text-gray-600">Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-800">Daftar</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
