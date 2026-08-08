<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Biro Jasa STNK</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="layout-container">
        <!-- Sidebar navigation -->
         <aside class="sidebar">
            <h2>Biro Jasa STNK</h2>
            <nav>
                <a href="{{ route('dashboard') }}">📊 Dashboard</a>
                <a href="{{ route('clients.index') }}">👤 Data Client</a>
                <a href="{{ route('vehicles.index') }}">🚗 Data Kendaraan</a>
                <a href="{{ route('transactions.index') }}">📝 Status Pengurusan</a>
                <a href="{{ route('stnk_records.index') }}">📅 Manajemen Pajak</a>
            </nav>
         </aside>

         <!-- Main content -->
          <main class="main-content">
            <header class="header">
                <h1>@yield('header_title')</h1>
                <div class="user-info">
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </header>
            @yield('content')
          </main>
    </div>
    
</body>
</html>