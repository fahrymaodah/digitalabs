<!-- Navbar -->
<header class="fixed top-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-100" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 20">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2">
                <img src="{{ asset('images/digitalabs-banner-dark.png') }}" alt="DigitaLabs" class="h-14">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ url('/') }}" class="px-4 py-2 text-gray-700 hover:text-orange-600 font-medium transition {{ request()->is('/') ? 'text-orange-600' : '' }}">
                    Home
                </a>
                <a href="{{ url('/courses') }}" class="px-4 py-2 text-gray-700 hover:text-orange-600 font-medium transition {{ request()->is('courses*') ? 'text-orange-600' : '' }}">
                    Courses
                </a>
                <a href="{{ url('/blog') }}" class="px-4 py-2 text-gray-700 hover:text-orange-600 font-medium transition {{ request()->is('blog*') ? 'text-orange-600' : '' }}">
                    Blog
                </a>
                <a href="{{ url('/affiliate') }}" class="px-4 py-2 text-gray-700 hover:text-orange-600 font-medium transition {{ request()->is('affiliate') ? 'text-orange-600' : '' }}">
                    Affiliate
                </a>
            </div>

            <!-- Desktop Auth Buttons -->
            <div class="hidden lg:flex items-center space-x-3">
                @if(auth('user')->check())
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-500/25 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ url('/dashboard/login') }}" class="px-5 py-2.5 text-gray-700 hover:text-orange-600 font-medium transition">
                        Login
                    </a>
                    <a href="{{ url('/dashboard/register') }}" class="px-5 py-2.5 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-500/25">
                        Daftar Gratis
                    </a>
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button @click="$dispatch('open-mobile-menu')" class="lg:hidden p-2 text-gray-700 hover:text-orange-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>
</header>

<!-- Spacer for fixed navbar -->
<div class="h-16 lg:h-20"></div>
