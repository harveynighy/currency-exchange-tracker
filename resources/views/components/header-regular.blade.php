@props([
    'heroTag' => null,
    'heroTitle' => null,
    'heroDescription' => null,
])

<header class="header-hero regular">
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
            <div class="header-hero__hero__container">
                @if ($heroTag)
                    @if ($heroTag == 'charts')
                        <div class="header-hero__hero__tag tag-charts">DATA THROUGH 11 MAR 2026</div>
                    @elseif ($heroTag == 'blog')
                        <div class="header-hero__hero__tag tag-blog">ANALYSIS & COMMENTARY</div>
                    @elseif ($heroTag == 'faq')
                        <div class="header-hero__hero__tag tag-faq">HELP & SUPPORT</div>
                    @elseif ($heroTag == 'money-pages')
                        <div class="header-hero__hero__tag tag-money-pages">POPULAR PAIRS</div>
                    @elseif ($heroTag == 'policy')
                        <div class="header-hero__hero__tag tag-policy">
                            <svg width="11" height="13" viewBox="0 0 11 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.91634 7.00026C9.91634 9.91693 7.87467 11.3753 5.44801 12.2211C5.32094 12.2642 5.1829 12.2621 5.05717 12.2153C2.62467 11.3753 0.583008 9.91693 0.583008 7.00026V2.91693C0.583008 2.76222 0.644466 2.61385 0.753862 2.50445C0.863259 2.39506 1.01163 2.3336 1.16634 2.3336C2.33301 2.3336 3.79134 1.6336 4.80634 0.746931C4.92992 0.641347 5.08713 0.583336 5.24967 0.583336C5.41222 0.583336 5.56943 0.641347 5.69301 0.746931C6.71384 1.63943 8.16634 2.3336 9.33301 2.3336C9.48772 2.3336 9.63609 2.39506 9.74549 2.50445C9.85488 2.61385 9.91634 2.76222 9.91634 2.91693V7.00026Z"
                                    stroke="#8EC5FF" stroke-width="1.16667" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Legal & Privacy
                        </div>
                    @elseif ($heroTag == 'api-doc')
                        <div class="header-hero__hero__tag tag-api-doc">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <g clip-path="url(#clip0_1_2273)">
                                    <path d="M9.3335 10.5L12.8335 7L9.3335 3.5" stroke="#5EE9B5" stroke-width="1.16667"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M4.6665 3.5L1.1665 7L4.6665 10.5" stroke="#5EE9B5" stroke-width="1.16667"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_1_2273">
                                        <rect width="14" height="14" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>REST API v1
                        </div>
                    @endif
                @endif
                @if ($heroTitle)
                    <div class="header-hero__hero__title">{{ $heroTitle }}</div>
                @endif
                @if ($heroDescription)
                    <div class="header-hero__hero__description">{{ $heroDescription }}</div>
                @endif
            </div>
        </div>
</header>
