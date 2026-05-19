@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin - Ijen Geopark')

@section('content')
    <div class="card">
        <h1>Selamat Datang, {{ auth()->user()->username }}!</h1>
        <p>Ini adalah pusat kendali sistem manajemen konten (CMS) Ijen Geopark.</p>
        <p>Silakan gunakan menu di sebelah kiri untuk menambah, mengubah, atau menghapus data yang akan ditampilkan di halaman utama pengunjung.</p>
        <br>
        <p>Total Artikel: <strong>{{ $total_artikel }}</strong></p>
        <p>Total FAQ: <strong>{{ $total_faq }}</strong></p>
    </div>
@endsection
