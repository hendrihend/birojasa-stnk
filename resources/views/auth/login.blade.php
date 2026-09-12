<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Biro Jasa</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
    <!-- Fonts Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 to-blue-50 flex items-center justify-center p-4">
    
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="p-8 sm:p-10">
            <!-- Logo & Nama Aplikasi -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4 shadow-sm border border-blue-100">
                    <img src="{{ asset('images/images_logo.png') }}" alt="Logo" class="h-14 w-14 object-contain"/>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Biro Jasa STNK</h2>
                <p class="text-sm font-medium text-blue-600 mt-1 uppercase tracking-widest">Management System</p>
            </div>

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center text-sm shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500 mr-3 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-6 text-center">
                <h3 class="text-xl font-semibold text-gray-800">Selamat Datang Kembali!</h3>
                <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda</p>
            </div>

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Input Email -->
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" placeholder="contoh@email.com" 
                            class="w-full rounded-xl border border-gray-300 pl-10 pr-4 py-3 text-sm text-gray-900 bg-gray-50/50 outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"/>
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••" 
                            class="w-full rounded-xl border border-gray-300 pl-10 pr-12 py-3 text-sm text-gray-900 bg-gray-50/50 outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"/>
                        <button type="button" id="togglePassword" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-blue-600 transition focus:outline-none">
                            <i id="eyeIcon" class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
        
                <!-- Tombol Submit -->
                <button type="submit" 
                    class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition-all hover:bg-blue-700 hover:shadow-blue-600/40 focus:outline-none focus:ring-4 focus:ring-blue-500/50 active:scale-[0.98]">
                    Masuk ke Sistem
                </button>

            </form>
        </div>
    </div>

    <!-- Hapus CDN ini jika Anda sudah menjalankan `npm run dev` atau `npm run build` melalui Vite -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        toggleButton.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            if (isPassword) {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    </script>
</body>
</html>