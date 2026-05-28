@extends('layouts.app')

@section('title', 'Informasi Pertanyaan dan Kontak - Ijen Geopark')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('contact.css') }}">
@endsection

@section('content')
    @php
        // VARIABEL UNTUK MENGUBAH GAMBAR
        // Ubah teks di dalam tanda kutip dengan nama file gambar Anda (misal: 'gambar-baru.jpg' atau 'assets/foto.png')
        $url_gambar = asset('uploads/75878.avif');
    @endphp

    <div class="contact-page-container">
        <!-- Bagian Kontak -->
        <section class="contact-section">
            <div class="contact-image">
                <!-- Memanggil variabel PHP $url_gambar untuk menampilkan gambar -->
                <img src="{{ $url_gambar }}" alt="Ilustrasi Kontak">
            </div>
            <div class="contact-info">
                <h1>Informasi Pertanyaan<br>dan Kontak</h1>
                
                <div class="contact-items">
                    <div class="contact-item">
                        <h3>Nomor Hubung</h3>
                        <p>+6212345678</p>
                    </div>
                    <div class="contact-item">
                        <h3>Email</h3>
                        <p>email@gmail.com</p>
                    </div>
                    <div class="contact-item">
                        <h3>X</h3>
                        <p>@Xacc</p>
                    </div>
                    <div class="contact-item">
                        <h3>Instagram</h3>
                        <p>@IGacc</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
