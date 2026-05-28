@extends('layouts.app')

@section('title', 'Customer Stories - Ijen Geopark')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('customer_stories.css') }}">
@endsection

@section('content')
    <!-- Hero Section for Customer Stories -->
    <section class="hero-stories">
        <div class="hero-text">
            <h1>Customer Stories</h1>
            <p>Hear from those who have witnessed the majestic of Java</p>
        </div>
    </section>

    <!-- Stories Grid (Menggunakan struktur Card dari CSS Anda) -->
    <section class="cards-section customer-stories-section">
        <div class="cards-container">
            @forelse($stories as $story)
            <!-- Story -->
            <a href="{{ url('/customer-stories/' . $story->id) }}" class="story-link">
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
                        <p class="story-excerpt">{{ htmlspecialchars($story->description) }}</p>
                        <p class="visitor-name">— {{ htmlspecialchars($story->visitor_name) }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div class="empty-state">
                <p>Belum ada customer story. Silakan hubungi admin untuk menambahkan.</p>
            </div>
            @endforelse
        </div>
    </section>
@endsection
