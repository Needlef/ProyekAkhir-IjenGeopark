<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ijen Geopark')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('ijen_geopark.css') }}">
    @yield('page-css')
</head>
<body>

    <header>
        <nav>
            <div class="logo"><a href="{{ url('/') }}" style="text-decoration:none; color:inherit;">Ijen Geopark</a></div>
            <div class="nav-right">
                @if(request()->is('/'))
                    <button id="btn-refresh-artikel" class="theme-btn" title="Refresh Artikel"><img src="{{ asset('ui/refresh_64dp_E3E3E3_FILL0_wght400_GRAD0_opsz48.png') }}" alt="Refresh"></button>
                @endif
                <button id="theme-toggle" class="theme-btn" title="Toggle dark theme"
                        data-sun-icon="{{ asset('ui/sunny_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.png') }}"
                        data-moon-icon="{{ asset('ui/moon_stars_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.png') }}">
                    <img src="{{ asset('ui/sunny_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.png') }}" alt="Theme Toggle">
                </button>
                <a href="{{ route('contact') }}" class="contact-link">Contact</a>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer>
        <div class="container">
            <div class="footer-brand">
                <h2>Ijen Geopark</h2>
                <p>Find the majestic of Java</p>
                <!-- <div class="social-icons">
                    <a href="#"><img src="https://e7.pngegg.com/pngimages/624/712/png-clipart-instagram-logo-logo-computer-icons-insta-miscellaneous-sticker.png" alt="Instagram"></a>
                    <-- <a href="#"><img src="https://png.pngtree.com/element_our/md/20180626/md_5b32227feb591.jpg" alt="LinkedIn"></a> -->
                    <!-- <a href="#"><img src="https://img.freepik.com/free-vector/twitter-new-2023-x-logo-white-background-vector_1017-45422.jpg?semt=ais_hybrid&w=740&q=80" alt="X"></a>
                </div> --> 
            </div>
            
            <div class="footer-links-container">
                <div class="footer-column">
                    <h4>Learn more</h4>
                    <ul><li><a href="{{ route('customer-stories') }}">Customer stories</a></li></ul>
                </div>
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul><li><a href="{{ route('contact') }}">Contact</a></li></ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('script.js') }}"></script>

</body>
</html>
