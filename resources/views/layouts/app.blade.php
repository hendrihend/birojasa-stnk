<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - Biro Jasa STNK</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/images_logo.png') }}">

    <!-- Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-900">

<div class="flex h-screen overflow-hidden">

    <!-- ================= SIDEBAR ================= -->
    <aside id="sidebar"
        class="w-64 bg-[#35318B] text-white flex flex-col shadow-xl flex-shrink-0 transition-all duration-300">

        <!-- Logo -->
        <div class="h-[84px] px-5 flex items-center gap-3 border-b border-white/10">
            <img src="{{ asset('images/images_logo.png') }}"
                alt="Logo Biro Jasa STNK"
                class="w-11 h-11 object-contain flex-shrink-0">

            <div id="sidebarBrand" class="overflow-hidden whitespace-nowrap">
                <h2 class="text-lg font-semibold leading-tight">Biro Jasa STNK</h2>
                <p class="text-[11px] text-indigo-200 mt-0.5">Management System</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="menu-item flex items-center gap-4 px-5 py-3.5 rounded-xl transition-all duration-200
                {{ request()->routeIs('dashboard')
                    ? 'bg-white text-[#35318B] font-semibold'
                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">

                <i class="fa-solid fa-house w-5 text-center text-lg flex-shrink-0"></i>
                <span class="menu-text whitespace-nowrap">Dashboard</span>
            </a>


            <!-- ================= DATA MASTER ================= -->
            @php
                $dataMasterOpen = request()->routeIs('clients.*', 'vehicles.*', 'documents.*');
            @endphp

            <div>

                <button type="button"
                    onclick="toggleDataMaster()"
                    class="menu-item w-full flex items-center justify-between px-5 py-3.5 rounded-xl
                    transition-all duration-200
                    {{ $dataMasterOpen
                        ? 'bg-white/10 text-white'
                        : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">

                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-database w-5 text-center text-lg flex-shrink-0"></i>
                        <span class="menu-text whitespace-nowrap">Data Master</span>
                    </div>

                    <i id="dataMasterIcon"
                        class="menu-text fa-solid fa-chevron-right text-xs transition-transform duration-200
                        {{ $dataMasterOpen ? 'rotate-90' : '' }}">
                    </i>
                </button>


                <!-- Submenu -->
                <div id="dataMasterMenu"
                    class="{{ $dataMasterOpen ? '' : 'hidden' }} mt-1 ml-4 space-y-1">

                    <!-- Data Klien -->
                    <a href="{{ route('clients.index') }}"
                        class="flex items-center gap-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('clients.*')
                            ? 'bg-white text-[#35318B] font-semibold'
                            : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">

                        <i class="fa-solid fa-users w-4 text-center"></i>
                        <span>Data Klien</span>
                    </a>


                    <!-- Data Kendaraan -->
                    <a href="{{ route('vehicles.index') }}"
                        class="flex items-center gap-3 px-5 py-2.5 rounded-lg text-sm transition-all duration-200
                        {{ request()->routeIs('vehicles.*', 'documents.*')
                            ? 'bg-white text-[#35318B] font-semibold'
                            : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">

                        <i class="fa-solid fa-car w-4 text-center"></i>
                        <span>Data Kendaraan</span>
                    </a>

                </div>
            </div>


            <!-- Jadwal Jatuh Tempo -->
            <a href="{{ route('stnk_records.index') }}"
                class="menu-item flex items-center gap-4 px-5 py-3.5 rounded-xl transition-all duration-200
                {{ request()->routeIs('stnk_records.*')
                    ? 'bg-white text-[#35318B] font-semibold'
                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">

                <i class="fa-solid fa-calendar-days w-5 text-center text-lg flex-shrink-0"></i>
                <span class="menu-text whitespace-nowrap">Jadwal Jatuh Tempo</span>
            </a>


            <!-- Status Pengurusan -->
            <a href="{{ route('transactions.index') }}"
                class="menu-item flex items-center gap-4 px-5 py-3.5 rounded-xl transition-all duration-200
                {{ request()->routeIs('transactions.*')
                    ? 'bg-white text-[#35318B] font-semibold'
                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">

                <i class="fa-solid fa-file-lines w-5 text-center text-lg flex-shrink-0"></i>
                <span class="menu-text whitespace-nowrap">Status Pengurusan</span>
            </a>


            <!-- Kelola User -->
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('users.index') }}"
                    class="menu-item flex items-center gap-4 px-5 py-3.5 rounded-xl transition-all duration-200
                    {{ request()->routeIs('users.*')
                        ? 'bg-white text-[#35318B] font-semibold'
                        : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">

                    <i class="fa-solid fa-user-shield w-5 text-center text-lg flex-shrink-0"></i>
                    <span class="menu-text whitespace-nowrap">Kelola User</span>
                </a>
            @endif


            <!-- Scan QR -->
            <a href="{{ route('scan.qr') }}"
                class="menu-item flex items-center gap-4 px-5 py-3.5 rounded-xl transition-all duration-200
                {{ request()->routeIs('scan.qr')
                    ? 'bg-white text-[#35318B] font-semibold'
                    : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">

                <i class="fa-solid fa-qrcode w-5 text-center text-lg flex-shrink-0"></i>
                <span class="menu-text whitespace-nowrap">Scan QR Code</span>
            </a>

        </nav>


        <!-- Footer -->
        <div id="sidebarFooter"
            class="px-5 py-4 border-t border-white/10 text-center text-[11px] text-indigo-200 whitespace-nowrap overflow-hidden">

            &copy; {{ date('Y') }} Biro Jasa STNK

        </div>

    </aside>


    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto overflow-x-hidden bg-gray-50">

        <!-- Header -->
        <header class="h-[64px] bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0">

            <div class="flex items-center gap-5">

                <!-- Hamburger -->
                <button type="button"
                    onclick="toggleSidebar()"
                    class="w-9 h-9 flex items-center justify-center rounded-lg
                    text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">

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
                <div class="w-9 h-9 rounded-full bg-indigo-100 text-[#35318B]
                    flex items-center justify-center font-semibold text-sm">

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

                    <button type="submit"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg
                        text-gray-500 hover:bg-red-50 hover:text-red-600
                        font-medium transition">

                        <i class="fa-solid fa-arrow-right-from-bracket"></i>

                        <span class="hidden sm:inline">Logout</span>

                    </button>
                </form>

            </div>

        </header>


        <!-- Content -->
        <div class="p-6 lg:p-7">
            @yield('content')
        </div>

    </main>

</div>


<!-- ================= SWEET ALERT ================= -->
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


    /* ================= SWEET ALERT ================= */

    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,

        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });


    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif


    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            confirmButtonColor: '#3085d6'
        });
    @endif


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