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
        <div class="header-hero__hero">
            <div class="header-hero__hero--left">
                <div class="header-hero__hero--left-tag">Live & Historical Data</div>
                <h1 class="header-hero__hero--left-title">30+ Years of<br />FX Market Data</h1>
                <p class="header-hero__hero--left-description">Interactive charts, historical API, and real-time
                    conversion for 50+ currencies</p>
                <div class="header-hero__hero--left-buttons">
                    <a href="/charts" class="btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path
                                d="M2 2V12.6667C2 13.0203 2.14048 13.3594 2.39052 13.6095C2.64057 13.8595 2.97971 14 3.33333 14H14"
                                stroke="#030213" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 11.3333V6" stroke="#030213" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8.66667 11.3333V3.33334" stroke="#030213" stroke-width="1.33333"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.33333 11.3333V9.33334" stroke="#030213" stroke-width="1.33333"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Explore Data
                    </a>
                    <a href="/api-docs" class="btn">View API Docs</a>
                </div>
            </div>
            <div class="header-hero__hero--right">
                <div class="header-hero__hero--right-grid-box">
                    <h3 class="header-hero__hero--right-grid-box-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path d="M5.33331 1.33333V3.99999" stroke="#00D3F2" stroke-width="1.33333"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10.6667 1.33333V3.99999" stroke="#00D3F2" stroke-width="1.33333"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M12.6667 2.66667H3.33333C2.59695 2.66667 2 3.26363 2 4.00001V13.3333C2 14.0697 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0697 14 13.3333V4.00001C14 3.26363 13.403 2.66667 12.6667 2.66667Z"
                                stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2 6.66667H14" stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Coverage
                    </h3>
                    <p class="header-hero__hero--right-grid-box-figure">1994 - 2026</p>
                    <p class="header-hero__hero--right-grid-box-desc">32 years of data</p>
                </div>
                <div class="header-hero__hero--right-grid-box">
                    <h3 class="header-hero__hero--right-grid-box-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <g clip-path="url(#clip0_1_382)">
                                <path
                                    d="M14.36 10H11.3333C10.9797 10 10.6406 10.1405 10.3905 10.3905C10.1405 10.6406 10 10.9797 10 11.3333V14.36"
                                    stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M4.66663 2.22667V3.33334C4.66663 3.86377 4.87734 4.37248 5.25241 4.74755C5.62749 5.12262 6.13619 5.33334 6.66663 5.33334C7.02025 5.33334 7.35939 5.47381 7.60943 5.72386C7.85948 5.97391 7.99996 6.31305 7.99996 6.66667C7.99996 7.4 8.59996 8 9.33329 8C9.68691 8 10.0261 7.85953 10.2761 7.60948C10.5262 7.35943 10.6666 7.02029 10.6666 6.66667C10.6666 5.93334 11.2666 5.33334 12 5.33334H14.1133"
                                    stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M7.33337 14.6333V12C7.33337 11.6464 7.19289 11.3072 6.94284 11.0572C6.69279 10.8071 6.35365 10.6667 6.00003 10.6667C5.64641 10.6667 5.30727 10.5262 5.05722 10.2761C4.80718 10.0261 4.6667 9.68695 4.6667 9.33333V8.66666C4.6667 8.31304 4.52622 7.9739 4.27618 7.72385C4.02613 7.4738 3.68699 7.33333 3.33337 7.33333H1.3667"
                                    stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M8.00004 14.6667C11.6819 14.6667 14.6667 11.6819 14.6667 8C14.6667 4.3181 11.6819 1.33333 8.00004 1.33333C4.31814 1.33333 1.33337 4.3181 1.33337 8C1.33337 11.6819 4.31814 14.6667 8.00004 14.6667Z"
                                    stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_1_382">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                        Currencies
                    </h3>
                    <p class="header-hero__hero--right-grid-box-figure">50+</p>
                    <p class="header-hero__hero--right-grid-box-desc">Worldwide coverage</p>
                </div>
                <div class="header-hero__hero--right-grid-box">
                    <h3 class="header-hero__hero--right-grid-box-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path
                                d="M8 5.33334C11.3137 5.33334 14 4.43791 14 3.33334C14 2.22877 11.3137 1.33334 8 1.33334C4.68629 1.33334 2 2.22877 2 3.33334C2 4.43791 4.68629 5.33334 8 5.33334Z"
                                stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M2 3.33334V12.6667C2 13.1971 2.63214 13.7058 3.75736 14.0809C4.88258 14.456 6.4087 14.6667 8 14.6667C9.5913 14.6667 11.1174 14.456 12.2426 14.0809C13.3679 13.7058 14 13.1971 14 12.6667V3.33334"
                                stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M2 8C2 8.53043 2.63214 9.03914 3.75736 9.41421C4.88258 9.78929 6.4087 10 8 10C9.5913 10 11.1174 9.78929 12.2426 9.41421C13.3679 9.03914 14 8.53043 14 8"
                                stroke="#00D3F2" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Pairs
                    </h3>
                    <p class="header-hero__hero--right-grid-box-figure">2,584+</p>
                    <p class="header-hero__hero--right-grid-box-desc">Forex combinations</p>
                </div>
                <div class="header-hero__hero--right-grid-box">
                    <h3 class="header-hero__hero--right-grid-box-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path
                                d="M13.3333 8.66667C13.3333 12 11 13.6667 8.22663 14.6333C8.0814 14.6826 7.92365 14.6802 7.77996 14.6267C4.99996 13.6667 2.66663 12 2.66663 8.66667V4C2.66663 3.82319 2.73686 3.65362 2.86189 3.5286C2.98691 3.40358 3.15648 3.33334 3.33329 3.33334C4.66663 3.33334 6.33329 2.53334 7.49329 1.52C7.63453 1.39934 7.8142 1.33304 7.99996 1.33304C8.18572 1.33304 8.36539 1.39934 8.50663 1.52C9.67329 2.54 11.3333 3.33334 12.6666 3.33334C12.8434 3.33334 13.013 3.40358 13.138 3.5286C13.2631 3.65362 13.3333 3.82319 13.3333 4V8.66667Z"
                                stroke="#00D492" stroke-width="1.33333" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Status
                    </h3>
                    <p class="header-hero__hero--right-grid-box-figure">Live</p>
                    <p class="header-hero__hero--right-grid-box-desc">All systems go</p>
                </div>
            </div>

        </div>
    </div>
</header>