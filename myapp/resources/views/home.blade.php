@extends('layouts.app')

@section('title', 'Ijen Geopark - Beranda')

@section('content')
    <section class="hero-index">
        <div class="hero-text">
            <h1>Ijen Geopark</h1>
            <p>Find the majestic of Java</p>
        </div>
    </section>

    <!-- BAGIAN KARTU ARTIKEL (DINAMIS DARI DATABASE) -->
    <section class="cards-section">
        <div class="cards-container">
            
            @if(count($artikel_data) > 0)
                @foreach($artikel_data as $row)
                    @php
                        // Cek Sumber Gambar (URL vs Fisik)
                        if (strpos($row->gambar, 'http') === 0) {
                            $bg_image = htmlspecialchars($row->gambar);
                        } else {
                            $bg_image = "/uploads/" . htmlspecialchars($row->gambar);
                        }
                    @endphp
                    
                        <div class="card">
                            <div style="width: 100%; aspect-ratio: 21/9; overflow: hidden; position: relative; border-radius: 12px 12px 0 0;">
                                <img src="{{ $bg_image }}" style="
                                    position: absolute;
                                    width: {{ $row->css_width ?: 100 }}%;
                                    height: {{ $row->css_height ?: 100 }}%;
                                    left: {{ $row->css_left ?: 0 }}%;
                                    top: {{ $row->css_top ?: 0 }}%;
                                    max-width: none;
                                    object-fit: cover;
                                " alt="{{ htmlspecialchars($row->judul) }}">
                            </div>

                            <div class="card-body">
                                <h3>{{ htmlspecialchars($row->judul) }}</h3>
                                <p>{{ htmlspecialchars($row->ringkasan) }}</p>
                                <a href="{{ url('artikel/' . $row->id) }}" class="cta-link">Call to action &rarr;</a>
                            </div>
                        </div>
                @endforeach
            @else
                <p style="text-align:center; width:100%; grid-column: 1 / -1;">Belum ada destinasi yang ditambahkan. Silakan login ke panel Admin.</p>
            @endif

        </div>
    </section>

    <!-- BAGIAN FAQ (DINAMIS DARI DATABASE) -->
    <section class="faq-section">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 20px;">Pertanyaan Sering Diajukan (FAQ)</h2>
            
            @if(count($faq_data) > 0)
                @foreach($faq_data as $faq)
                    <div class="faq-item">
                        <button class="faq-question">{{ htmlspecialchars($faq->pertanyaan) }}</button>
                        <div class="faq-answer">
                            <!-- nl2br digunakan agar baris baru di database (enter) menjadi tag <br> di HTML -->
                            <p>{!! nl2br(htmlspecialchars($faq->jawaban)) !!}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="text-align:center;">Belum ada FAQ yang ditambahkan.</p>
            @endif

        </div>
    </section>
@endsection
