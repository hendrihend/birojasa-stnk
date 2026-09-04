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
                    <img src="{{ asset('images/images_logo.png') }}" alt="Logo" class="h-20 w-20 object-contain"/>
                </div>
            </div>

            <!-- Nama Aplikasi -->
            <div class="text-center mt-0 mb-4">
                 <h2 class="text-lg font-semibold text-gray-800">Biro Jasa STNK</h2>
                <p class="text-sm text-gray-500">Management System</p>
            </div>

            <!-- Judul Login -->
            <h2 class="text-2xl font-bold text-gray-800 text-center mt-6 mb-5">Login</h2>
            @if(session('success'))
                <div class="mb-5 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
                    <span class="mr-2">✅</span> {{ session('success') }}
                </div>
            @endif

            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                 <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"/>
                </div>
                 <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"/>
                </div>
                <div class="text-right">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">
                        Lupa Kata Sandi?
                    </a>
                </div>
                <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Login</button>
                <p class="text-sm text-center text-gray-600 mt-4">Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Daftar
                    </a>
                </p>
            </form>

    </div>

</body>
</html>
