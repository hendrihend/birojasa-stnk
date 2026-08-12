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
         <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl flex-shrink-0">
             <div class="p-6 border-b border-gray-800">
                <i class="fa-solid fa-shield-halved text-blue-400 text-2xl"></i>
                <div>
                    <h2 class="text-xl font-black tracking-wider text-blue-400 leading-none">BIRO JASA</h2>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Sistem Manajemen STNK</p>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-3 border-l-4 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-blue-400 font-bold border-blue-500' : 'text-gray-400 border-transparent hover:bg-gray-800 hover:text-white' }}">
                   <i class="fa-solid fa-chart-pie w-5 text-center text-lg"></i>
                   <span>Dashboard</span>
                </a>
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('clients.index') }}" class="flex items-center gap-3 px-6 py-3 border-l-4 transition-colors duration-200 {{ request()->routeIs('clients.*') ? 'bg-gray-800 text-blue-400 font-bold border-blue-500' : 'text-gray-400 border-transparent hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center text-lg"></i>
                    <span>Data Client</span>
                </a>
                @endif
                <a href="{{ route('vehicles.index') }}" class="flex items-center gap-3 px-6 py-3 border-l-4 transition-colors duration-200 {{ request()->routeIs('vehicles.*', 'documents.*') ? 'bg-gray-800 text-blue-400 font-bold border-blue-500' : 'text-gray-400 border-transparent hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-car w-5 text-center text-lg"></i>
                    <span>Data Kendaraan</span>
                </a>
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('stnk_records.index') }}" class="flex items-center gap-3 px-6 py-3 border-l-4 transition-colors duration-200 {{ request()->routeIs('stnk_records.*') ? 'bg-gray-800 text-blue-400 font-bold border-blue-500' : 'text-gray-400 border-transparent hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-regular fa-calendar-check w-5 text-center text-lg"></i>
                    <span>Manajemen Pajak</span>
                </a>
                @endif
                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-6 py-3 border-l-4 transition-colors duration-200 {{ request()->routeIs('transactions.*') ? 'bg-gray-800 text-blue-400 font-bold border-blue-500' : 'text-gray-400 border-transparent hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-signature w-5 text-center text-lg"></i>
                    <span>Transaksi</span>
                </a>
                @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-6 py-3 border-l-4 transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-gray-800 text-blue-400 font-bold border-blue-500' : 'text-gray-400 border-transparent hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-shield w-5 text-center text-lg"></i>
                   <span>Kelola User</span>
                </a>
                @endif
                <a href="{{ route('scan.qr') }}" class="flex items-center gap-3 px-6 py-3 border-l-4 transition-colors duration-200 {{ request()->routeIs('scan.qr') ? 'bg-gray-800 text-blue-400 font-bold border-blue-500' : 'text-gray-400 border-transparent hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center text-lg"></i>
                    <span>Scan QR Code</span>
                </a>
            </nav>
            <!-- Footer Sidebar -->
            <div class="p-4 border-t border-gray-800 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Biro Jasa STNK
            </div>
         </aside>

         <!-- Main content -->
          <main class="flex-1 flex flex-col h-screen overflow-y-auto overflow-x-hidden bg-gray-50">
            <div class="p-8 pb-4">
                <header class="flex justify-between items-center pb-5 border-b border-gray-200 mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">@yield('header_title')</h1>
                    <div class="flex items-center gap-5">
                        <div class="text-right">
                            <span class="block font-bold text-gray-700 leading-none">Halo, {{ Auth::user()->name }}</span>
                            <span class="text-[11px] font-bold text-blue-500 tracking-wider">{{ strtoupper(Auth::user()->role) }}</span>
                        </div>
                        <div class="h-8 w-px bg-gray-300"></div>
                        
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-gray-500 hover:text-red-600 font-bold transition duration-300">
                                <i class="fa-solid fa-power-off"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </header>
                @yield('content')
            </div>
          </main>
    </div>


    <script>
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
    </script>
    
</body>
</html>