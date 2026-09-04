<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Biro Jasa STNK</title>
    <!-- tailwindcss -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- fonts awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar navigation -->
        <aside class="w-64 bg-blue-900 text-white flex flex-col shadow-2xl flex-shrink-0" id="sidebar">
            <!-- Logo -->
            <div class="h-[84px] px-5 flex items-center gap-3 border-b border-white/10">
                <img src="{{ asset('images/images_logo.png') }}" alt="Logo Biro Jasa STNK" class="w-11 h-11 object-contain flex-shrink-0">
                <div id="sidebarBrand" class="overflow-hidden whitespace-nowrap">
                    <h2 class="text-xl font-black tracking-wider text-blue-200 leading-tight">BIRO JASA</h2>
                    <p class="text-[10px] text-blue-200 mt-1 uppercase mt-0.5 tracking-widest">Sistem Manajemen STNK</p>
                </div>
            </div>
            <!-- Navigasi -->
            <nav class="flex-1 overflow-y-auto py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="menu-item flex items-center gap-4 px-7 py-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">
                   <i class="fa-solid fa-chart-pie w-5 text-center text-lg"></i>
                   <span class="menu-text whitespace-nowrap">Dashboard</span>
                </a>
                <!-- Data Master -->
                @php
                    $dataMasterOpen = request()->routeIs('clients.*', 'vehicles.*', 'documents.*');
                @endphp
                <div>
                    <button type="button"
                        onclick="toggleDataMaster()"
                        class="menu-item w-full flex items-center justify-between px-7 py-3.5 rounded-xl
                        transition-all duration-200 {{ $dataMasterOpen ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">

                        <div class="flex items-center gap-4">
                            <i class="fa-solid fa-database w-5 text-center text-lg flex-shrink-0"></i>
                            <span class="menu-text whitespace-nowrap">Data Master</span>
                        </div>

                        <i id="dataMasterIcon"
                            class="menu-text fa-solid fa-chevron-right mx-2 text-xs transition-transform duration-200
                            {{ $dataMasterOpen ? 'rotate-90' : '' }}">
                        </i>
                    </button>
                    <!-- Submenu -->
                    <div id="dataMasterMenu" class="{{ $dataMasterOpen ? '' : 'hidden' }} mt-1 ml-4 space-y-1">
                        <!-- Data Klien -->
                        <a href="{{ route('clients.index') }}"
                            class="flex items-center gap-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                            {{ request()->routeIs('clients.*') ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">
                            <i class="fa-solid fa-users w-4 text-center"></i><span class="menu-text">Data Klien</span>
                        </a>
                        <!-- Data Kendaraan -->
                        <a href="{{ route('vehicles.index') }}"
                            class="flex items-center gap-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                            {{ request()->routeIs('vehicles.*', 'documents.*') ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">
                            <i class="fa-solid fa-car w-4 text-center"></i><span class="menu-text">Data Kendaraan</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('stnk_records.index') }}" class="menu-item flex items-center gap-4 px-7 py-3.5 rounded-xl transition-all duration-200 duration-200 {{ request()->routeIs('stnk_records.*') ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">
                    <i class="fa-regular fa-calendar-check w-5 text-center text-lg"></i>
                    <span class="menu-text whitespace-nowrap">Manajemen Pajak</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="menu-item flex items-center gap-4 px-7 py-3.5 rounded-xl transition-all duration-200 duration-200 {{ request()->routeIs('transactions.*') ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-signature w-5 text-center text-lg"></i>
                    <span class="menu-text whitespace-nowrap">Transaksi</span>
                </a>
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('users.index') }}" class="menu-item flex items-center gap-4 px-7 py-3.5 rounded-xl transition-all duration-200 duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-shield w-5 text-center text-lg"></i>
                    <span class="menu-text whitespace-nowrap">Kelola User</span>
                </a>
                @endif
                <a href="{{ route('scan.qr') }}" class="menu-item flex items-center gap-4 px-7 py-3.5 rounded-xl transition-all duration-200 duration-200 {{ request()->routeIs('scan.qr') ? 'bg-blue-800 text-blue-200 font-bold border-blue-500' : 'text-blue-200 border-transparent hover:bg-blue-800 hover:text-white' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center text-lg"></i>
                    <span class="menu-text whitespace-nowrap">Scan QR Code</span>
                </a>
            </nav>
            <!-- Footer Sidebar -->
            <div id="sidebarFooter" class="p-4 border-t border-blue-800 text-center text-xs text-blue-200">
                &copy; {{ date('Y') }} Biro Jasa STNK
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 flex flex-col h-screen overflow-y-auto overflow-x-hidden bg-gray-50">
                <!-- Header -->
                <header class="h-[64px] bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0">
                    <div class="flex items-center gap-5">
                        <!-- Hamburger -->
                        <button type="button" onclick="toggleSidebar()" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>

                        <!-- Page Title -->
                        <h1 class="text-2xl font-semibold text-gray-800">
                            @yield('header_title')
                        </h1>

                    </div>


                    <!-- User -->
                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-9 h-9 rounded-full bg-indigo-100 text-[#35318B] flex items-center justify-center font-semibold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <!-- User Info -->
                        <div class="hidden sm:block">
                            <p class="text-sm font-medium text-gray-800 leading-tight">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </p>
                        </div>
                        <!-- Logout -->
                        <div class="h-8 w-px bg-gray-200"></div>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-600 font-medium transition">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </header>
                <div class="p-6 lg:p-7">
                    @yield('content')
                </div>
          </main>
    </div>


    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* ================= SIDEBAR ================= */
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const brand = document.getElementById('sidebarBrand');
            const footer = document.getElementById('sidebarFooter');
            const menuTexts = document.querySelectorAll('.menu-text');

            const isCollapsed = sidebar.classList.contains('w-20');

            if (isCollapsed) {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');

                brand.classList.remove('hidden');
                footer.classList.remove('hidden');

                menuTexts.forEach(item => {
                    item.classList.remove('hidden');
                });

            } else {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');

                brand.classList.add('hidden');
                footer.classList.add('hidden');

                menuTexts.forEach(item => {
                    item.classList.add('hidden');
                });
            }
        }

        /* ================= DATA MASTER ================= */
        function toggleDataMaster() {
            const menu = document.getElementById('dataMasterMenu');
            const icon = document.getElementById('dataMasterIcon');

            menu.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        }

        // <!-- Logika Popup Otomatis -->
        // Konfigurasi default Toast (Popup kecil di pojok kanan atas)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Tangkap session('success') dari laravel
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // Tangkap session('error') jika gagal
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6',
            });
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            // Cari kotak input pencarian di halaman
            const searchInput = document.querySelector('input[name="search"]');
            
            if (searchInput) {
                let timeout = null;
                
                // Dengarkan setiap kali ada tombol keyboard yang ditekan
                searchInput.addEventListener('input', function () {
                    // Batalkan pencarian sebelumnya jika user masih mengetik cepat
                    clearTimeout(timeout);
                    
                    const query = this.value;
                    const form = this.closest('form');
                    // Buat URL pencarian
                    const url = form.action + '?search=' + encodeURIComponent(query);
                    
                    // Beri jeda 500 milidetik (Setengah detik) setelah user berhenti mengetik
                    // agar server tidak kelebihan beban (teknik ini disebut Debouncing)
                    timeout = setTimeout(() => {
                        // Lakukan pencarian di latar belakang (tanpa reload halaman)
                        fetch(url)
                            .then(response => response.text())
                            .then(html => {
                                // Ubah teks yang diterima menjadi elemen HTML
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                
                                // Ambil tabel dari hasil yang baru, lalu timpa tabel yang lama
                                const newTable = doc.querySelector('table');
                                const currentTable = document.querySelector('table');
                                
                                if (newTable && currentTable) {
                                    currentTable.innerHTML = newTable.innerHTML;
                                }
                            });
                    }, 500); 
                });
            }
        });

        /* ================= LIVE SEARCH ================= */
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="search"]');
            if (!searchInput) return;
            let timeout = null;
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                const query = this.value;
                const form = this.closest('form');
                if (!form) return;
                const url = form.action + '?search=' + encodeURIComponent(query);
                timeout = setTimeout(() => {
                    fetch(url)
                        .then(response => response.text())
                        .then(html => {

                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            const newTable = doc.querySelector('table');
                            const currentTable = document.querySelector('table');

                            if (newTable && currentTable) {
                                currentTable.innerHTML = newTable.innerHTML;
                            }

                        })
                        .catch(error => {
                            console.error('Search error:', error);
                        });

                }, 500);

            });

        });
    </script>
    
</body>
</html>