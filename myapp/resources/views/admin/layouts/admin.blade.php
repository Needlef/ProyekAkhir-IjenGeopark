<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - Ijen Geopark')</title>
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>
<body>

    <!-- Navigasi Samping -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ url('admin/dashboard') }}">🏠 Dashboard</a>
        <a href="{{ url('admin/kelola_artikel') }}">📝 Kelola Artikel</a>
        <a href="{{ url('admin/kelola_faq') }}">❓ Kelola FAQ</a>
        <a href="{{ url('admin/kelola_customer_stories') }}">⭐ Kelola Customer Stories</a>
        <a href="{{ url('admin/kelola_akun') }}">👤 Kelola Akun</a>
        <a href="{{ url('admin/logout') }}" class="logout-btn"> Keluar (Logout)</a>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>

</body>
</html>
