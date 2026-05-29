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
        <a href="{{ url('admin/dashboard') }}">
            <img src="{{ asset('ui/home_icon.png') }}" width="18" height="18" alt="Dashboard">
            <span>Dashboard</span>
        </a>
        <a href="{{ url('admin/kelola_artikel') }}">
            <img src="{{ asset('ui/article_icon.png') }}" width="18" height="18" alt="Artikel">
            <span>Kelola Artikel</span>
        </a>
        <a href="{{ url('admin/kelola_faq') }}">
            <img src="{{ asset('ui/faq_icon.png') }}" width="18" height="18" alt="FAQ">
            <span>Kelola FAQ</span>
        </a>
        <a href="{{ url('admin/kelola_customer_stories') }}">
            <img src="{{ asset('ui/star_icon.png') }}" width="18" height="18" alt="Stories">
            <span>Kelola Stories</span>
        </a>
        <a href="{{ url('admin/kelola_akun') }}">
            <img src="{{ asset('ui/account_icon.png') }}" width="18" height="18" alt="Akun">
            <span>Kelola Akun</span>
        </a>
        <a href="{{ url('admin/logout') }}" class="logout-btn">
            <img src="{{ asset('ui/logout_icon.png') }}" width="18" height="18" alt="Keluar">
            <span>Keluar</span>
        </a>
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
