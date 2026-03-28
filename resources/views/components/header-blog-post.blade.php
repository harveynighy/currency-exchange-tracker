@props([
    'heroTag' => null,
    'heroTitle' => null,
    'heroDescription' => null,
])

<header class="header-hero">
    <div class="header-hero__container">
        <div class="header-hero__nav">
            <div class="header-hero__logo">
                <a href="/" class="">
                    <img src="{{ asset('IF-FX-Platform.png') }}" alt="Infinite Finances" class="">
                </a>
            </div>
            <div class="header-hero__links">
                <a href="/charts" class="">Historical
                    Charts</a>
                <a href="/blog" class="">Blog</a>
                <a href="/api-docs" class="">API
                    Docs</a>
                @auth
                    <a href="/profile" class="">Profile</a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="">Logout</button>
                    </form>
                @else
                    <a href="/login" class="btn">Login</a>
                    <a href="/register" class="btn">Create account</a>
                @endauth
            </div>
            <button type="button" class="header-hero__menu-toggle" data-mobile-nav-toggle
                aria-controls="home-mobile-menu" aria-expanded="false" aria-label="Toggle navigation menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <path d="M4 7H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M4 12H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M4 17H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </button>
        </div>
        <div id="home-mobile-menu" class="header-hero__mobile-menu" aria-hidden="true">
            <a href="/charts">Historical Charts</a>
            <a href="/blog">Blog</a>
            <a href="/api-docs">API Docs</a>
            @auth
                <a href="/profile">Profile</a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="/login" class="btn">Login</a>
                <a href="/register" class="btn">Create account</a>
            @endauth
        </div>
    </div>
</header>
