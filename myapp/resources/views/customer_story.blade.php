@extends('layouts.app')

@section('title', htmlspecialchars($story->title) . ' - Customer Stories - Ijen Geopark')

@section('content')
    @php
        // Logika gambar (URL vs Fisik)
        if ($story->image && strpos($story->image, 'http') === 0) {
            $sumber_gambar = htmlspecialchars($story->image);
        } else if ($story->image) {
            $sumber_gambar = asset("uploads/" . $story->image);
        } else {
            $sumber_gambar = 'https://via.placeholder.com/800x400?text=No+Image';
        }
    @endphp

    <!-- BAGIAN HERO GAMBAR UTAMA -->
    <section class="hero-article" style="width: 100%; height: 400px; overflow: hidden; position: relative;">
        <img src="{{ $sumber_gambar }}" style="
            width: 100%;
            height: 100%;
            object-fit: cover;
        ">
    </section>

    <!-- BAGIAN KONTEN CUSTOMER STORY -->
    <main class="article-content">
        <!-- Label Eyebrow -->
        <!-- <p class="eyebrow">Customer Story</p> -->
        
        <!-- Judul/Quote Utama -->
        <h1 class="main-headline">{{ htmlspecialchars($story->title) }}</h1>
        
        <!-- Nama Pengunjung -->
        <p style="font-size: 16px; color: #666; margin-bottom: 20px; font-style: italic;">— {{ htmlspecialchars($story->visitor_name) }}</p>
        
        <div class="article-text">
            <!-- nl2br digunakan agar enter di textarea form admin berubah menjadi tag <br> yang dibaca oleh HTML -->
            <p>{!! nl2br(htmlspecialchars($story->description)) !!}</p>
        </div>

        <!-- Tombol Kembali
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
            <a href="{{ url('/customer-stories') }}" style="
                display: inline-block;
                padding: 12px 24px;
                background: #333;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                transition: background 0.3s;
            " onmouseover="this.style.background='#555'" onmouseout="this.style.background='#333'">
                ← Kembali ke Customer Stories
            </a>
        </div> -->
    </main>

    <script src="{{ asset('script.js') }}"></script>
@endsection
