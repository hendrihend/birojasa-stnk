<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>
        @yield ('title', 'Biro Jasa STNK')
    </title>

    {{-- Tailwind --}}
    @vite (['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />

    <style>
        body {font-family: 'Poppins', sans-serif;}
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">

        {{-- OVERLAY MOBILE --}}
        <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>

        {{-- SIDEBAR --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full transform flex-col bg-indigo-900 text-white transition-all duration-300 ease-in-out lg:static lg:translate-x-0 lg:w-64">

            {{-- Logo + Nama Aplikasi --}}
            <div class="flex h-20 items-center border-b border-indigo-800 px-5">
                <div class="flex items-center gap-3">
                    {{-- Logo --}}
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Biro Jasa STNK" class="h-10 w-10 object-contain"/>
                    </div>

                    {{-- Nama aplikasi --}}
                    <div class="sidebar-label">
                        <h1 class="text-base font-semibold text-white">Biro Jasa STNK</h1>

                        <p class="text-[10px] text-indigo-300">Management System</p>
                    </div>
                </div>

            </div>

            {{-- Menu --}}
            <nav class="flex-1 overflow-y-auto px-3 py-5">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 mb-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-white text-indigo-700 font-semibold' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>

                    <span class="sidebar-label">Dashboard</span>
                </a>

                {{-- ================================================= --}}
                {{-- DATA MASTER (Dropdown) --}}
                {{-- ================================================= --}}

                @php
                    $masterActive = request()->routeIs('clients.*') || request()->routeIs('vehicles.*');
                @endphp

                <div class="mb-2">
                    <button
                        type="button"
                        class="sidebar-dropdown-toggle flex w-full items-center justify-between gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
                            {{ $masterActive ? 'text-white' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}"
                    >
                        <span class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"
                                />
                            </svg>

                            <span class="sidebar-label">Data Master</span>
                        </span>

                        <svg
                            class="chevron sidebar-label h-4 w-4 shrink-0 transition-transform {{ $masterActive ? 'rotate-90' : '' }}"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <div class="sidebar-submenu {{ $masterActive ? '' : 'hidden' }} mt-1 space-y-1 pl-4">
                        {{-- Data Client --}}
                        <a
                            href="{{ route('clients.index') }}"
                            class="sidebar-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs('clients.index') ? 'bg-white text-indigo-700 font-semibold' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}"
                        >
                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.964 0a9 9 0 1 0-11.964 0m11.964 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                />
                            </svg>

                            <span class="sidebar-label">Data Client</span>
                        </a>

                        {{-- Data Kendaraan --}}
                        <a
                            href="{{ route('vehicles.index') }}"
                            class="sidebar-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs('vehicles.index') ? 'bg-white text-indigo-700 font-semibold' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}"
                        >
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25h-6.75c-.621 0-1.125.504-1.125 1.125v9.375m0-9.375h5.25v9.375m-5.25 0h5.25m-5.25 0v-9.375"
                                />
                            </svg>

                            <span class="sidebar-label">Data Kendaraan</span>
                        </a>
                    </div>
                </div>

                {{-- Manajemen Pajak --}}
                <a
                    href="{{ route('stnk_records.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 mb-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('stnk_records.index') ? 'bg-white text-indigo-700 font-semibold' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}"
                >
                    <span class="w-5 shrink-0 text-center text-lg">🧾</span>

                    <span class="sidebar-label">Jadwal Jatuh Tempo</span>
                </a>

                {{-- Status Pengurusan --}}
                <a
                    href="{{ route('transactions.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 mb-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('transactions.index') ? 'bg-white text-indigo-700 font-semibold' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}"
                >
                    <span class="w-5 shrink-0 text-center text-lg">📄</span>

                    <span class="sidebar-label">Status Pengurusan</span>
                </a>

                {{-- User --}}
                <a
                    href="{{ route('users.index') }}"
                    class="sidebar-link flex items-center gap-3 px-4 py-3 mb-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('users.index') ? 'bg-white text-indigo-700 font-semibold' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}"
                >
                    <span class="w-5 shrink-0 text-center text-lg">👤</span>
                    <span class="sidebar-label">Kelola User</span>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="border-t border-indigo-800 px-4 py-4">
                <div class="sidebar-label text-center text-xs text-indigo-300">Biro Jasa STNK</div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}

        <main class="min-h-screen min-w-0 flex-1">
            {{-- HEADER --}}

            <header
                class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6"
            >
                {{-- Bagian kiri --}}
                <div class="flex items-center gap-3">
                    {{-- Hamburger --}}
                    <button
                        id="hamburgerButton"
                        type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-gray-600 transition hover:bg-gray-100"
                        aria-label="Buka menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>

                    {{-- Judul halaman --}}
                    <h1 class="text-lg font-semibold text-gray-800 sm:text-xl">
                        @yield ('header_title')
                    </h1>
                </div>

                {{-- User --}}
                <div class="flex items-center gap-3">
                    {{-- Avatar --}}
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600"
                    >
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    {{-- Nama user --}}
                    <div class="hidden sm:block">
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>

                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button
                            type="submit"
                            class="hidden rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 sm:block"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- PAGE CONTENT --}}

            <div class="p-4 sm:p-6">
                @yield ('content')
            </div>
        </main>
    </div>

    {{-- SIDEBAR SCRIPT --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburgerButton = document.getElementById('hamburgerButton');

            const sidebar = document.getElementById('sidebar');

            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');

                sidebarOverlay.classList.remove('hidden');

                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');

                sidebarOverlay.classList.add('hidden');

                document.body.classList.remove('overflow-hidden');
            }

            // Klik area di luar sidebar
            sidebarOverlay.addEventListener('click', function () {
                closeSidebar();
            });

            // Tutup sidebar ketika menu diklik di mobile
            const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');

            sidebarLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });

            // Jika layar kembali ke desktop/mobile
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    sidebarOverlay.classList.add('hidden');

                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.style.width = '';
                }
            });

            // ==============================
            // Dropdown submenu (Data Master, dst)
            // ==============================

            const dropdownToggles = sidebar.querySelectorAll('.sidebar-dropdown-toggle');

            dropdownToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    const submenu = toggle.nextElementSibling;
                    const chevron = toggle.querySelector('.chevron');

                    submenu.classList.toggle('hidden');
                    chevron.classList.toggle('rotate-90');
                });
            });

            // ==============================
            // Collapse sidebar (desktop)
            // ==============================

            const sidebarLabels = sidebar.querySelectorAll('.sidebar-label');
            let isCollapsed = false;

            function collapseSidebar() {
                sidebar.style.width = '5rem';

                sidebarLabels.forEach(function (el) {
                    el.classList.add('hidden');
                });

                sidebarLinks.forEach(function (link) {
                    link.classList.add('lg:justify-center');
                });

                // Tutup semua dropdown submenu saat diciutkan
                sidebar.querySelectorAll('.sidebar-submenu').forEach(function (el) {
                    el.classList.add('hidden');
                });

                sidebar.querySelectorAll('.chevron').forEach(function (el) {
                    el.classList.remove('rotate-90');
                });

                isCollapsed = true;
            }

            function expandSidebar() {
                sidebar.style.width = '';

                sidebarLabels.forEach(function (el) {
                    el.classList.remove('hidden');
                });

                sidebarLinks.forEach(function (link) {
                    link.classList.remove('lg:justify-center');
                });

                isCollapsed = false;
            }

            // ==============================
            // Tombol Hamburger (selalu tampil)
            // Desktop -> collapse/expand sidebar
            // Mobile  -> buka/tutup overlay sidebar
            // ==============================

            hamburgerButton.addEventListener('click', function () {
                if (window.innerWidth >= 1024) {
                    if (isCollapsed) {
                        expandSidebar();
                    } else {
                        collapseSidebar();
                    }
                } else {
                    const isClosed = sidebar.classList.contains('-translate-x-full');

                    if (isClosed) {
                        openSidebar();
                    } else {
                        closeSidebar();
                    }
                }
            });
        });
    </script>

    {{-- SWEETALERT NOTIFICATION --}}

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonColor: '#4f46e5',
                timer: 2500,
                timerProgressBar: true,
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonColor: '#4f46e5',
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terdapat kesalahan pada data',
                html: `<ul style="text-align:left; margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>`,
                confirmButtonColor: '#4f46e5',
            });
        </script>
    @endif
</body>

</html>