@extends('layouts.app')

@section('title', 'Ijen Geopark - Beranda')

@section('content')
    <section class="hero-index">
        <div class="hero-text">
            <h1>Ijen Geopark</h1>
            <p>Find the majestic of Java</p>
        </div>
    </section>

    <section class="ijen-geopark-intro">
        <div class="ijen-container">
            <h2 class="ijen-title">Welcome to Ijen UNESCO Global Geopark</h2>
            <p class="ijen-subtitle">Go ahead and say just a little more about what you do.</p>
            
            <p class="ijen-description">
                Spanning across the Banyuwangi and Bondowoso regencies, Ijen Geopark is a 
                world-class destination recognized by UNESCO. It is a living laboratory of the 
                Earth's evolution, offering a unique blend of mesmerizing volcanic landscapes, 
                lush highland ecosystems, and the enduring heritage of the local communities. 
                Whether you are an adventurer, a nature lover, or a culture enthusiast, Ijen 
                offers an unforgettable journey into the heart of Java.
            </p>
        </div>
    </section>

    <!-- BAGIAN KARTU ARTIKEL (DINAMIS DARI DATABASE) -->
    <section class="cards-section">
        <h2 style="margin-bottom: 20px;">Artikel Terbaru</h2>
        <div class="cards-container" id="artikel-container">
            @include('partials.artikel_list')
        </div>
    </section>

    <section class="cards-section">
        <div class="section-header">
            <h2>Customer Stories</h2>
            <a href="{{ url('/customer-stories') }}" class="btn-view-more">
                Lihat Lebih Banyak &rarr;
            </a>
        </div>

        <div class="cards-container">
            @forelse($stories_data as $story)
            <a href="{{ url('/customer-stories/' . $story->id) }}" class="story-link-wrapper">
                <div class="card story-card">
                    
                    <div class="card-img" style="background-image: url('@php
                        if ($story->image && strpos($story->image, 'http') === 0) {
                            echo htmlspecialchars($story->image);
                        } else if ($story->image) {
                            echo asset("uploads/" . $story->image);
                        } 
                    @endphp');"></div>
                    
                    <div class="card-body">
                        <h3>"{{ htmlspecialchars($story->title) }}"</h3>
                        <p class="story-desc">{{ htmlspecialchars($story->description) }}</p>
                        <p class="visitor-name">&mdash; {{ htmlspecialchars($story->visitor_name) }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div class="empty-stories">
                <p>Belum ada customer story.</p>
            </div>
            @endforelse
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
