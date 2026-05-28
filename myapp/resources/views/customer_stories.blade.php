@extends('layouts.app')

@section('title', 'Customer Stories - Ijen Geopark')

@section('content')
    <!-- Hero Section for Customer Stories -->
    <section class="hero-stories">
        <div class="hero-text">
            <h1>Customer Stories</h1>
            <p>Hear from those who have witnessed the majestic of Java</p>
        </div>
    </section>

    <!-- Stories Grid (Menggunakan struktur Card dari CSS Anda) -->
    <section class="cards-section" style="max-width: 1200px; margin: 0 auto; padding: 80px 5%;">
        <div class="cards-container">
            @forelse($stories as $story)
            <!-- Story -->
            <a href="{{ url('/customer-stories/' . $story->id) }}" style="text-decoration: none; color: inherit;">
                <div class="card story-card">
                    <div class="card-img" style="background-image: url('@php
                        if ($story->image && strpos($story->image, 'http') === 0) {
                            echo htmlspecialchars($story->image);
                        } else if ($story->image) {
                            echo asset("uploads/" . $story->image);
                        } else {
                            echo 'https://via.placeholder.com/400?text=No+Image';
                        }
                    @endphp');"></div>
                    <div class="card-body">
                        <h3>"{{ htmlspecialchars($story->title) }}"</h3>
                        <p style="max-height: 80px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">{{ htmlspecialchars($story->description) }}</p>
                        <p class="visitor-name">— {{ htmlspecialchars($story->visitor_name) }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                <p>Belum ada customer story. Silakan hubungi admin untuk menambahkan.</p>
            </div>
            @endforelse
        </div>
    </section>
@endsection
