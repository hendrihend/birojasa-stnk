<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Biro Jasa STNK')
    </title>

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen flex">


        {{-- ================================================= --}}
        {{-- OVERLAY MOBILE --}}
        {{-- ================================================= --}}

        <div
            id="sidebarOverlay"
            class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"
        ></div>


        {{-- ================================================= --}}
        {{-- SIDEBAR --}}
        {{-- ================================================= --}}

        <aside
            id="sidebar"
            class="
                fixed lg:static
                inset-y-0 left-0
                z-50
                w-64
                bg-[#111111]
                text-white
                transform -translate-x-full
                lg:translate-x-0
                transition-transform duration-300 ease-in-out
                flex flex-col
            "
        >

            {{-- Logo + Nama Aplikasi --}}
            <div class="h-20 px-5 flex items-center border-b border-gray-800">

                <div class="flex items-center gap-3">

                    {{-- Logo --}}
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img
                            src="{{ asset('images/logo.png') }}"
                            alt="Logo Biro Jasa STNK"
                            class="w-10 h-10 object-contain"
                        >
                    </div>

                    {{-- Nama aplikasi --}}
                    <div>
                        <h1 class="text-base font-semibold text-white">
                            Biro Jasa STNK
                        </h1>

                        <p class="text-[10px] text-gray-400">
                            Management System
                        </p>
                    </div>

                </div>

            </div>


            {{-- Menu --}}
            <nav class="flex-1 px-3 py-5 overflow-y-auto">

                {{-- Dashboard --}}
        <a 
                
                href="{{ route('dashboard') }}"
                class="
                flex items-center gap-3
                px-4 py-3 mb-2
                rounded-lg
                text-sm font-medium
                transition
                {{ request()->routeIs('dashboard')
                ? 'bg-indigo-600 text-white'
                : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                }}"
                >
                
                <svg
                class="h-5 w-5 shrink-0"
                fill="none"        
                stroke="currentColor"
                viewBox="0 0 24 24"> 
                
                <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                />
            </svg>
            
            <span>Dashboard</span>
        
        </a>


                {{-- Data Client --}}
                <a
                    href="{{ route('clients.index') }}"
                    class="
                        flex items-center gap-3
                        px-4 py-3 mb-2
                        rounded-lg
                        text-sm font-medium
                        transition
                       {{ request()->routeIs('clients.index')
                            ? 'bg-indigo-600 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                        }}
                    "
                >
                <svg 
                    class="h-5 w-5 shrink-0" 
                    fill="none" stroke="currentColor" 
                    viewBox="0 0 24 24">
                    
                    <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2"
                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.964 0a9 9 0 1 0-11.964 0m11.964 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                    <span>Data Client</span>
                </a>


                {{-- Data Kendaraan --}}
                <a
                    href="{{ route('vehicles.index') }}"
                    class="
                        flex items-center gap-3
                        px-4 py-3 mb-2
                        rounded-lg
                        text-sm font-medium
                        transition
                        {{ request()->routeIs('vehicles.index')
                            ? 'bg-indigo-600 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                        }}
                    "
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25h-6.75c-.621 0-1.125.504-1.125 1.125v9.375m0-9.375h5.25v9.375m-5.25 0h5.25m-5.25 0v-9.375" />
</svg>

                    <span>Data Kendaraan</span>
                </a>


                {{-- Status Pengurusan --}}
                <a
                    href="{{ route('transactions.index') }}"
                    class="
                        flex items-center gap-3
                        px-4 py-3 mb-2
                        rounded-lg
                        text-sm font-medium
                        transition
                        {{ request()->routeIs('transactions.index')
                            ? 'bg-indigo-600 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                        }}
                    "
                >
                    <span class="text-lg">📄</span>

                    <span>Status Pengurusan</span>
                </a>


                {{-- Manajemen Pajak --}}
                <a
                    href="{{ route('stnk_records.index') }}"
                    class="
                        flex items-center gap-3
                        px-4 py-3 mb-2
                        rounded-lg
                        text-sm font-medium
                        {{ request()->routeIs('stnk_records.index')
                            ? 'bg-indigo-600 text-white'
                            : 'text-gray-300 hover:bg-gray-800 hover:text-white'
                        }}
                        transition
                    "
                >
                    <span class="text-lg">🧾</span>

                    <span>Manajemen Pajak</span>
                </a>

            </nav>


            {{-- Sidebar Footer --}}
            <div class="px-4 py-4 border-t border-gray-800">

                <div class="text-xs text-gray-500 text-center">
                    Biro Jasa STNK
                </div>

            </div>

        </aside>

        {{-- MAIN CONTENT --}}

        <main class="flex-1 min-w-0 min-h-screen">

            {{-- HEADER --}}

            <header
                class="
                    h-16
                    bg-white
                    border-b border-gray-200
                    flex items-center
                    justify-between
                    px-4 sm:px-6
                "
            >

                {{-- Bagian kiri --}}
                <div class="flex items-center gap-3">

                    {{-- Hamburger --}}
                    <button
                        id="hamburgerButton"
                        type="button"
                        class="
                            lg:hidden
                            w-10 h-10
                            flex items-center justify-center
                            rounded-lg
                            text-gray-600
                            hover:bg-gray-100
                            transition
                        "
                        aria-label="Buka menu"
                    >

                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>

                    </button>


                    {{-- Judul halaman --}}
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800">
                        @yield('header_title')
                    </h1>

                </div>


                {{-- User --}}
                <div class="flex items-center gap-3">

                    {{-- Avatar --}}
                    <div
                        class="
                            w-9 h-9
                            rounded-full
                            bg-indigo-100
                            flex items-center justify-center
                            text-indigo-600
                            font-semibold
                            text-sm
                        "
                    >
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>


                    {{-- Nama user --}}
                    <div class="hidden sm:block">

                        <p class="text-sm font-medium text-gray-700">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-xs text-gray-400">
                            Administrator
                        </p>

                    </div>


                    {{-- Logout --}}
                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="
                                hidden sm:block
                                px-3 py-2
                                text-sm font-medium
                                text-gray-600
                                border border-gray-200
                                rounded-lg
                                hover:bg-gray-100
                                hover:text-gray-900
                                transition
                            "
                        >
                            Logout
                        </button>
                    </form>

                </div>

            </header>

            {{-- PAGE CONTENT --}}

            <div class="p-4 sm:p-6">

                @yield('content')

            </div>

        </main>

    </div>

    {{-- HAMBURGER SCRIPT --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const hamburgerButton =
                document.getElementById('hamburgerButton');

            const sidebar =
                document.getElementById('sidebar');

            const sidebarOverlay =
                document.getElementById('sidebarOverlay');


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


            hamburgerButton.addEventListener('click', function () {

                const isClosed =
                    sidebar.classList.contains('-translate-x-full');

                if (isClosed) {

                    openSidebar();

                } else {

                    closeSidebar();

                }

            });


            // Klik area di luar sidebar
            sidebarOverlay.addEventListener('click', function () {

                closeSidebar();

            });


            // Tutup sidebar ketika menu diklik di mobile
            const sidebarLinks =
                sidebar.querySelectorAll('a');

            sidebarLinks.forEach(function (link) {

                link.addEventListener('click', function () {

                    if (window.innerWidth < 1024) {

                        closeSidebar();

                    }

                });

            });


            // Jika layar kembali ke desktop
            window.addEventListener('resize', function () {

                if (window.innerWidth >= 1024) {

                    sidebarOverlay.classList.add('hidden');

                    document.body.classList.remove('overflow-hidden');

                }

            });

        });
    </script>

</body>

</html>