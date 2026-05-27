@extends('layouts.app')

@section('title', htmlspecialchars($artikel->judul) . ' - Ijen Geopark')

@section('content')
    @php
        // Logika gambar (URL vs Fisik)
        if (strpos($artikel->gambar, 'http') === 0) {
            $sumber_gambar = htmlspecialchars($artikel->gambar);
        } else {
            $sumber_gambar = asset("uploads/" . $artikel->gambar);
        }
    @endphp

    <!-- <header>
        <nav>
            <div class="logo">Ijen Geopark</div>
            <div class="nav-right">
                <button id="theme-toggle" class="theme-btn" title="Toggle dark theme">☀️</button>
                <a href="#" class="contact-link">Contact</a>
            </div>
        </nav>
    </header> -->
    
    <!-- BAGIAN HERO GAMBAR UTAMA -->
    <section class="hero-article" style="width: 100%; height: 400px; overflow: hidden; position: relative;">
            <img src="{{ $sumber_gambar }}" style="
                position: absolute;
                width: {{ $artikel->css_width ?: 100 }}%;
                height: {{ $artikel->css_height ?: 100 }}%;
                left: {{ $artikel->css_left ?: 0 }}%;
                top: {{ $artikel->css_top ?: 0 }}%;
                max-width: none;
                object-fit: cover;
            ">
    </section>

    <!-- BAGIAN KONTEN ARTIKEL -->
    <main class="article-content">
        <!-- Label / Eyebrow -->
        <p class="eyebrow">{{ htmlspecialchars($artikel->label) }}</p>
        
        <!-- Judul Utama -->
        <h1 class="main-headline">{{ htmlspecialchars($artikel->judul) }}</h1>
        
        <div class="article-text">
            <!-- nl2br digunakan agar enter di textarea form admin berubah menjadi tag <br> yang dibaca oleh HTML -->
            <p>{!! nl2br(htmlspecialchars($artikel->konten)) !!}</p>
        </div>
    </main>

    <script src="{{ asset('script.js') }}"></script>
@endsection
