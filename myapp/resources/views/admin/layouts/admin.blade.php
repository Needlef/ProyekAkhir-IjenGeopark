<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - Ijen Geopark')</title>
    <style>
        body { font-family: sans-serif; margin: 0; background: #f4f4f4; display: flex; }
        .sidebar { width: 250px; background: #222; color: white; min-height: 100vh; padding: 20px; box-sizing: border-box; }
        .sidebar h2 { margin-top: 0; font-size: 20px; border-bottom: 1px solid #444; padding-bottom: 10px; }
        .sidebar a { display: block; color: #ddd; text-decoration: none; padding: 10px 0; border-bottom: 1px solid #333; }
        .sidebar a:hover { color: white; padding-left: 5px; transition: 0.3s; }
        .content { flex: 1; padding: 30px; }
        .card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .logout-btn { color: #ff4d4d !important; font-weight: bold; margin-top: 20px; }
        /* Style for table */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #eee; }
        .action-btn { display: inline-block; padding: 6px 12px; border: none; cursor: pointer; text-decoration: none; color: white; border-radius: 3px; font-size: 14px; line-height: 1.5; font-family: inherit; text-align: center; }
        .btn-edit { background: orange; }
        .btn-delete { background: red; }
        .btn-add { background: green; margin-bottom: 10px; display: inline-block; }
        /* Style for form */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group textarea, .form-group input[type="number"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { height: 150px; }
        button[type="submit"] { background: #333; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>

    <!-- Navigasi Samping -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ url('admin/dashboard') }}">🏠 Dashboard</a>
        <a href="{{ url('admin/kelola_artikel') }}">📝 Kelola Artikel</a>
        <a href="{{ url('admin/kelola_faq') }}">❓ Kelola FAQ</a>
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
