<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', __('Abjadia Store')) }}</title>

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
</head>

<body class="font-sans antialiased">
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
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-primary-dark">
                            {{ __('Abjadia') }}
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



                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                        <a href="{{ route('lang.set', app()->getLocale() == 'en' ? 'ar' : 'en') }}" class="text-gray-700 hover:text-primary-dark font-medium">
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

                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-primary-dark relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-secondary-orange text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">{{ array_sum(array_column(session('cart', []), 'quantity')) }}</span>
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
        <div id="mobile-menu-drawer" class="fixed inset-y-0 ltr:left-0 rtl:right-0 w-64 bg-white z-[70] transform ltr:-translate-x-full rtl:translate-x-full transition-transform duration-300 ease-in-out shadow-xl">
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

            @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-primary-dark text-white pt-10 pb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">{{ __('Abjadia Store') }}</h3>
                        <p class="text-gray-300 text-sm">{{ __('Your one-stop shop for books and school supplies.') }}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">{{ __('Quick Links') }}</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#" class="hover:text-secondary-orange">{{ __('About Us') }}</a></li>
                            <li><a href="#" class="hover:text-secondary-orange">{{ __('Contact Us') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">{{ __('Categories') }}</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#" class="hover:text-secondary-orange">{{ __('Books') }}</a></li>
                            <li><a href="#" class="hover:text-secondary-orange">{{ __('School Supplies') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">{{ __('Contact') }}</h4>
                        <p class="text-sm text-gray-300">info@abjadia.com</p>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
                    &copy; {{ date('Y') }} {{ __('Abjadia Store') }}. {{ __('All rights reserved.') }}
                </div>
            </div>
        </footer>
    </div>
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
                    .then(response => response.json())
                    .then(data => {
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