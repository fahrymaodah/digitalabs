<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <x-seo 
        :title="$title ?? null"
        :description="$description ?? null"
        :keywords="$keywords ?? null"
        :image="$ogImage ?? null"
        :type="$ogType ?? 'website'"
        :author="$author ?? null"
        :publishedTime="$publishedTime ?? null"
        :canonical="$canonical ?? null"
    />

    {{-- Additional meta from pages --}}
    @stack('meta')

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Custom Styles -->
    <style>
        :root {
            --color-primary: #f97316;
            --color-primary-dark: #ea580c;
            --color-primary-light: #fb923c;
            --color-primary-50: #fff7ed;
            --color-primary-100: #ffedd5;
        }
        
        /* Prevent content overflow on mobile */
        .prose img,
        .prose iframe,
        .prose video,
        .prose embed,
        .prose object {
            max-width: 100% !important;
            height: auto !important;
        }
        
        .prose pre {
            overflow-x: auto !important;
            max-width: 100% !important;
        }
        
        .prose table {
            display: block;
            overflow-x: auto;
            width: 100%;
        }
    </style>

    @stack('styles')
    
    {{-- JSON-LD Structured Data --}}
    @stack('jsonld')
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <!-- Navbar -->
    @include('components.public.navbar')

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.public.footer')

    <!-- Mobile Menu Overlay -->
    <div x-data="{ open: false }" x-show="open" x-cloak
         class="fixed inset-0 z-50 lg:hidden"
         @open-mobile-menu.window="open = true"
         @close-mobile-menu.window="open = false">
        <!-- Backdrop -->
        <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80" @click="$dispatch('close-mobile-menu')"></div>

        <!-- Menu Panel -->
        <div x-show="open" x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 w-full max-w-sm bg-white shadow-xl">
            <div class="flex items-center justify-between p-4 border-b">
                <span class="text-xl font-bold text-orange-500">DigitaLabs</span>
                <button @click="$dispatch('close-mobile-menu')" class="p-2 text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ url('/') }}" class="block px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition">Home</a>
                <a href="{{ url('/courses') }}" class="block px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition">Courses</a>
                <a href="{{ url('/blog') }}" class="block px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition">Blog</a>
                <a href="{{ url('/affiliate') }}" class="block px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition">Affiliate</a>
                <hr class="my-4">
                @if(auth('user')->check())
                    <a href="{{ url('/dashboard') }}" class="block px-4 py-3 bg-orange-500 text-white text-center rounded-lg hover:bg-orange-600 transition">Dashboard</a>
                @else
                    <a href="{{ url('/dashboard/login') }}" class="block px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition">Login</a>
                    <a href="{{ url('/dashboard/register') }}" class="block px-4 py-3 bg-orange-500 text-white text-center rounded-lg hover:bg-orange-600 transition">Daftar Gratis</a>
                @endif
            </nav>
        </div>
    </div>

    @livewireScripts

    @stack('scripts')
</body>
</html>
