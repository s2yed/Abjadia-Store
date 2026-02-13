<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="currency" content="{{ $settings->currency ?? 'SAR' }}">

    @if($settings && $settings->favicon)
        <link rel="icon" href="{{ asset($settings->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <title>@yield('title', ($settings && $settings->site_name) ? $settings->site_name : config('app.name', __('Abjadia Store')))</title>
    <meta name="description" content="@yield('meta_description', ($settings && $settings->site_description) ? $settings->site_description : __('Your one-stop shop for books and school supplies.'))">
    <meta name="keywords" content="@yield('meta_keywords', ($settings && $settings->site_keywords) ? $settings->site_keywords : 'books, stationery, school supplies, abjadia')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', ($settings && $settings->site_name) ? $settings->site_name : config('app.name', __('Abjadia Store')))">
    <meta property="og:description" content="@yield('meta_description', ($settings && $settings->site_description) ? $settings->site_description : __('Your one-stop shop for books and school supplies.'))">
    <meta property="og:image" content="@yield('og_image', ($settings && $settings->logo) ? asset($settings->logo) : asset('images/logo.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', ($settings && $settings->site_name) ? $settings->site_name : config('app.name', __('Abjadia Store')))">
    <meta property="twitter:description" content="@yield('meta_description', ($settings && $settings->site_description) ? $settings->site_description : __('Your one-stop shop for books and school supplies.'))">
    <meta property="twitter:image" content="@yield('og_image', ($settings && $settings->logo) ? asset($settings->logo) : asset('images/logo.png'))">

    @yield('seo')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', 'Inter', sans-serif;
            background-color: #F8F9FA;
            color: #212529;
        }

        .text-primary-dark {
            color: #2C3E50;
        }

        .bg-primary-dark {
            background-color: #2C3E50;
        }

        .text-secondary-orange {
            color: #E67E22;
        }

        .bg-secondary-orange {
            background-color: #E67E22;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <!-- DIAGNOSTIC: layouts.app is active -->
    <div id="diagnostic-layout-active" style="display:none;"></div>
    {{-- Floating WhatsApp Chat --}}
    @if($settings && $settings->whatsapp_number)
    <div class="fixed bottom-6 left-6 z-50">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number) }}" 
           target="_blank" 
           class="flex items-center justify-center w-14 h-14 bg-[#25D366] text-white rounded-full shadow-lg hover:bg-[#128C7E] transition-all hover:scale-110 group focus:outline-none"
           title="{{ __('Contact us on WhatsApp') }}">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.448-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.439 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </a>
    </div>
    @endif
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo & Mobile Menu Toggle -->
                    <div class="flex-shrink-0 flex items-center">
                        <button id="mobile-menu-open" class="md:hidden p-2 rounded-md text-gray-700 hover:text-secondary-orange focus:outline-none mr-2 rtl:mr-0 rtl:ml-2">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <a href="{{ route('home') }}">
                            @if($settings && $settings->logo)
                                <img class="h-10 w-auto" src="{{ asset($settings->logo) }}" alt="{{ ($settings && $settings->site_name) ? $settings->site_name : __('Abjadia Store') }}">
                            @else
                                <h1 class="text-2xl font-bold text-secondary-orange">{{ __('Abjadia') }}</h1>
                            @endif
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-lg mx-8 hidden md:block">
                        <form action="{{ route('products.index') }}" method="GET" class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full border-gray-300 rounded-md focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm px-4 py-2" placeholder="{{ __('Search for books, supplies...') }}">
                            <button type="submit" class="absolute inset-y-0 right-0 px-3 flex items-center bg-secondary-orange rounded-r-md text-white hover:bg-orange-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>



                    <div class="flex items-center space-x-6 rtl:space-x-reverse">
                        <a href="{{ route('lang.set', app()->getLocale() == 'en' ? 'ar' : 'en') }}" class="text-gray-700 hover:text-primary-dark font-medium transition-colors">
                            {{ app()->getLocale() == 'en' ? 'العربية' : 'English' }}
                        </a>
                        @auth
                        @if(in_array(auth()->user()->role, ['admin', 'editor']))
                        <a href="{{ route('dashboard') }}" class="text-secondary-orange hover:text-orange-600 font-bold hidden md:inline-block">{{ __('Dashboard') }}</a>
                        @endif
                        <a href="{{ route('customer.profile') }}" class="text-gray-700 hover:text-primary-dark hidden md:inline-block">{{ __('My Account') }}</a>
                        @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-dark hidden md:inline-block">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-primary-dark hidden md:inline-block">{{ __('Register') }}</a>
                        @endauth

                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-primary-dark relative group p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span id="cart-count" class="absolute top-0 ltr:right-0 rtl:left-0 -mt-1.5 ltr:-mr-1.5 rtl:-ml-1.5 bg-secondary-orange text-white text-[10px] font-black rounded-full h-5 w-5 flex items-center justify-center shadow-lg border-2 border-white transform transition-transform group-hover:scale-110">
                                {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Secondary Category Navigation -->
        <nav class="bg-gray-50 border-b border-gray-200 hidden md:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-8 rtl:space-x-reverse h-10 overflow-x-auto no-scrollbar">
                    @foreach($navbarCategories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-xs font-semibold text-gray-600 hover:text-secondary-orange whitespace-nowrap uppercase tracking-wider transition-colors">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </nav>

        <!-- Mobile Drawer Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-[60] hidden transition-opacity duration-300 opacity-0"></div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu-drawer" class="fixed inset-y-0 ltr:left-0 rtl:right-0 w-64 bg-white z-[70] transform ltr:-translate-x-full rtl:translate-x-full transition-transform duration-300 ease-in-out shadow-xl md:hidden">
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <span class="text-xl font-bold text-primary-dark">{{ __('Menu') }}</span>
                    <button id="mobile-menu-close" class="p-2 text-gray-500 hover:text-secondary-orange transition-colors">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-grow overflow-y-auto no-scrollbar">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-2">{{ __('Categories') }}</p>
                    <div class="space-y-4">
                        @foreach($navbarCategories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="block text-base font-medium text-gray-700 hover:text-secondary-orange transition-colors py-1">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-auto pt-8 border-t border-gray-100 space-y-4">
                    @auth
                    @if(in_array(auth()->user()->role, ['admin', 'editor']))
                    <a href="{{ route('dashboard') }}" class="flex items-center text-secondary-orange font-bold hover:text-orange-600 transition-colors">
                        <svg class="h-5 w-5 mr-2 rtl:mr-0 rtl:ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        {{ __('Dashboard') }}
                    </a>
                    @endif
                    <a href="{{ route('customer.profile') }}" class="flex items-center text-gray-700 hover:text-secondary-orange transition-colors">
                        <svg class="h-5 w-5 mr-2 rtl:mr-0 rtl:ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('My Account') }}
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="flex items-center text-gray-700 hover:text-secondary-orange transition-colors">
                        <svg class="h-5 w-5 mr-2 rtl:mr-0 rtl:ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Login') }}
                    </a>
                    @endauth

                    <a href="{{ route('lang.set', app()->getLocale() == 'en' ? 'ar' : 'en') }}" class="flex items-center text-gray-700 hover:text-secondary-orange transition-colors">
                        <svg class="h-5 w-5 mr-2 rtl:mr-0 rtl:ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5a7 7 0 001.246-8.8m-2.787 3.518a4.499 4.499 0 013.209 5.413M3 20c1.343-2.685 4.411-5.333 8.23-5.333h1.54C16.589 14.667 19.657 17.315 21 20" />
                        </svg>
                        {{ app()->getLocale() == 'en' ? 'العربية' : 'English' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div class="block md:hidden bg-white p-4 shadow-sm">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full border-gray-300 rounded-md focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm px-4 py-2" placeholder="{{ __('Search for books, supplies...') }}">
                <button type="submit" class="absolute inset-y-0 right-0 px-3 flex items-center bg-secondary-orange rounded-r-md text-white hover:bg-orange-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <main class="flex-grow">
            @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <toast-component></toast-component>
        <side-cart-component></side-cart-component>
        @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-primary-dark text-white pt-10 pb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">{{ ($settings && $settings->site_name) ? $settings->site_name : __('Abjadia Store') }}</h3>
                        <p class="text-gray-300 text-sm">{{ ($settings && $settings->site_description) ? $settings->site_description : __('Your one-stop shop for books and school supplies.') }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">{{ __('Quick Links') }}</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="{{ route('pages.show', 'about-us') }}" class="hover:text-secondary-orange">{{ __('About Us') }}</a></li>
                            <li><a href="{{ route('pages.show', 'contact-us') }}" class="hover:text-secondary-orange">{{ __('Contact Us') }}</a></li>
                            <li><a href="{{ route('pages.show', 'terms-and-conditions') }}" class="hover:text-secondary-orange">{{ __('Terms of Service') }}</a></li>
                            <li><a href="{{ route('pages.show', 'privacy-policy') }}" class="hover:text-secondary-orange">{{ __('Privacy Policy') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">{{ __('Categories') }}</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories->take(5) as $footerCategory)
                                    <li><a href="{{ route('products.index', ['category' => $footerCategory->slug]) }}" class="hover:text-secondary-orange">{{ $footerCategory->name }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">{{ __('Contact') }}</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            @if($settings && $settings->contact_email)
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    <a href="mailto:{{ $settings->contact_email }}" class="hover:text-secondary-orange transition-colors">{{ $settings->contact_email }}</a>
                                </li>
                            @endif
                            @if($settings && $settings->contact_phone)
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $settings->contact_phone) }}" class="hover:text-secondary-orange transition-colors">{{ $settings->contact_phone }}</a>
                                </li>
                            @endif
                            @if($settings && $settings->whatsapp_number)
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number) }}" class="hover:text-secondary-orange transition-colors" target="_blank">{{ $settings->whatsapp_number }}</a>
                                </li>
                            @endif
                        </ul>

                        @if($settings && ($settings->social_facebook || $settings->social_twitter || $settings->social_instagram || $settings->social_linkedin || $settings->social_snapchat || $settings->social_youtube))
                        <div class="mt-4 flex flex-wrap gap-4">
                            @if($settings->social_facebook)
                                <a href="{{ $settings->social_facebook }}" class="text-gray-400 hover:text-white" target="_blank"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></a>
                            @endif
                            @if($settings->social_twitter)
                                <a href="{{ $settings->social_twitter }}" class="text-gray-400 hover:text-white" target="_blank"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                            @endif
                            @if($settings->social_instagram)
                                <a href="{{ $settings->social_instagram }}" class="text-gray-400 hover:text-white" target="_blank"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12s.014 3.667.072 4.947c.2 4.353 2.612 6.748 6.973 6.947 1.28.058 1.688.072 4.947.072s3.667-.014 4.948-.072c4.351-.2 6.748-2.612 6.947-6.947.058-1.28.072-1.688.072-4.947s-.014-3.667-.072-4.947c-.2-4.349-2.612-6.748-6.973-6.947C15.667.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                            @endif
                            @if($settings->social_linkedin)
                                <a href="{{ $settings->social_linkedin }}" class="text-gray-400 hover:text-white" target="_blank"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                            @endif
                            @if($settings->social_snapchat)
                                <a href="{{ $settings->social_snapchat }}" class="text-gray-400 hover:text-white" target="_blank"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.116 2.072C7.304 2.072 5.093 5.378 5.093 7.643c0 .762.247 1.492.68 2.091a.243.243 0 01.036.216.242.242 0 01-.161.152c-2.336.7-4.156 2.83-4.156 5.631 0 .298.242.54.54.54.408 0 .81-.03 1.203-.087a.241.241 0 01.198.05.241.241 0 01.077.194c.05 1.267.973 2.492 1.096 2.662.13.172.33.275.545.275.093 0 .185-.018.273-.054l2.257-.903a.242.242 0 01.272.046c1.1.921 2.536 1.428 4.04 1.428s2.94-.507 4.04-1.428a.242.242 0 01.272-.046l2.257.903c.088.036.18.054.273.054.215 0 .415-.103.545-.275.123-.17 1.046-1.395 1.096-2.662.01-.264.1-.493.3-.655a.242.242 0 01.245-.028c1.6.666 3.425.666 5.025.04a.242.242 0 00.15-.224 5.352 5.352 0 00-4.156-5.631.242.242 0 01-.161-.152.243.243 0 01.036-.216c.433-.6.68-1.33.68-2.091 0-2.265-2.21-5.571-7.022-5.571z"/></svg></a>
                            @endif
                            @if($settings->social_youtube)
                                <a href="{{ $settings->social_youtube }}" class="text-gray-400 hover:text-white" target="_blank"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186c-.272-1.018-1.074-1.821-2.091-2.093-1.841-.493-9.407-.493-9.407-.493s-7.567 0-9.407.493c-1.017.272-1.819 1.075-2.091 2.093-.501 1.868-.501 5.753-.501 5.753s0 3.886.501 5.754c.272 1.017 1.074 1.819 2.091 2.092 1.841.493 9.407.493 9.407.493s7.567 0 9.407-.493c1.017-.273 1.819-1.075 2.091-2.092.501-1.868.501-5.754.501-5.754s0-3.885-.501-5.753zm-14.165 9.382v-6.914l6.059 3.457-6.059 3.457z"/></svg></a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
                    &copy; {{ date('Y') }} {{ __('Abjadia Store') }}. {{ __('All rights reserved.') }}
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Add to Cart
        document.body.addEventListener('submit', function(e) {
            if (e.target && e.target.classList.contains('ajax-cart-form')) {
                e.preventDefault();
                const form = e.target;
                const url = form.action;
                const formData = new FormData(form);

                const button = form.querySelector('button');
                const originalContent = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to add to cart');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Use the global cart store if available
                        if (window.cartStore) {
                            window.cartStore.increment();
                            if (typeof window.cartStore.refresh === 'function') window.cartStore.refresh();
                            if (typeof window.cartStore.open === 'function') window.cartStore.open();
                        }

                        const cartCountElement = document.getElementById('cart-count');
                        if (cartCountElement && data.cartCount !== undefined) {
                            cartCountElement.textContent = data.cartCount;

                            // Checkmark feedback
                            button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                            button.classList.add('bg-green-500');
                            button.classList.remove('bg-secondary-orange');

                            setTimeout(() => {
                                button.innerHTML = originalContent;
                                button.classList.remove('bg-green-500');
                                button.classList.add('bg-secondary-orange');
                                button.disabled = false;
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Show error via global notification store
                        if (window.notification && typeof window.notification.error === 'function') {
                            window.notification.error(error.message || 'Failed to add to cart');
                        }
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    });
            }
        });

        // Handle Wishlist Toggle
        document.body.addEventListener('submit', function(e) {
            if (e.target && e.target.classList.contains('ajax-wishlist-form')) {
                e.preventDefault();
                const form = e.target;
                const button = form.querySelector('button');
                const svg = button.querySelector('svg');
                const url = form.action;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new FormData(form)
                    })
                    .then(response => {
                        if (response.status === 401) {
                            window.location.href = "{{ route('login') }}";
                            return;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.status) {
                            if (data.status === 'added') {
                                svg.setAttribute('fill', 'currentColor');
                                button.classList.add('text-red-500');
                                button.classList.remove('text-gray-400');
                            } else {
                                if (window.location.pathname === '/favorites') {
                                    const card = form.closest('.group');
                                    if (card) card.remove();
                                }
                                svg.setAttribute('fill', 'none');
                                button.classList.remove('text-red-500');
                                button.classList.add('text-gray-400');
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        // Mobile Menu Toggle Logic
        const mobileMenuOpenBtn = document.getElementById('mobile-menu-open');
        const mobileMenuCloseBtn = document.getElementById('mobile-menu-close');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');

        function toggleMobileMenu(isOpen) {
            if (isOpen) {
                mobileMenuOverlay.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenuOverlay.classList.remove('opacity-0');
                    mobileMenuDrawer.classList.remove('ltr:-translate-x-full', 'rtl:translate-x-full');
                    mobileMenuDrawer.classList.add('translate-x-0');
                }, 10);
                document.body.style.overflow = 'hidden';
            } else {
                mobileMenuOverlay.classList.add('opacity-0');
                mobileMenuDrawer.classList.add('ltr:-translate-x-full', 'rtl:translate-x-full');
                mobileMenuDrawer.classList.remove('translate-x-0');
                setTimeout(() => {
                    mobileMenuOverlay.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            }
        }

        if (mobileMenuOpenBtn) {
            mobileMenuOpenBtn.addEventListener('click', () => toggleMobileMenu(true));
        }
        if (mobileMenuCloseBtn) {
            mobileMenuCloseBtn.addEventListener('click', () => toggleMobileMenu(false));
        }
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', () => toggleMobileMenu(false));
        }
    });
</script>

</html>