@extends('layouts.app')

@section('content')
<!-- Hero Section with Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .swiper {
        width: 100%;
        height: 500px;
    }

    .swiper-wrapper {
        height: 100%;
        /* Ensure wrapper takes full height */
    }

    .swiper-slide {
        background: #333;
        /* Fallback color */
        height: 100%;
        /* Force full height */
        width: 100%;
        /* Force full width */
        position: relative;
        overflow: hidden;
    }

    .swiper-slide img {
        position: absolute;
        /* Absolute positioning to fill container */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 10;
        /* Image at base level */
    }

    /* Content Overlay */
    .slide-content-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        /* Slight darkness */
        z-index: 20;
        /* Above image */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 1rem;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #fff;
        z-index: 30;
        /* Above overlay */
    }

    .swiper-pagination-bullet-active {
        background: #fff;
    }
</style>

<div class="relative bg-gray-900 overflow-hidden h-[500px]">
    @if(isset($banners['hero_image']) && $banners['hero_image']->isNotEmpty())
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($banners['hero_image'] as $banner)
            <div class="swiper-slide">
                <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}">
                <!-- Overlay Text (Optional) -->
                @if($banner->title || $banner->description)
                <div class="slide-content-overlay">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-4 opacity-0 translate-y-4 transition-all duration-700 delay-300 transform">{{ $banner->title }}</h1>
                    @if($banner->description)
                    <p class="text-lg md:text-2xl text-gray-200 mb-8 max-w-2xl opacity-0 translate-y-4 transition-all duration-700 delay-500 transform">{{ $banner->description }}</p>
                    @endif
                    @if($banner->link && $banner->link != '#')
                    <a href="{{ $banner->link }}" class="inline-block px-8 py-3 border border-transparent text-base font-medium rounded-md text-gray-900 bg-white hover:bg-gray-100 md:py-4 md:text-lg transition opacity-0 translate-y-4 transition-all duration-700 delay-700 transform">
                        {{ __('Explore Now') }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
    @else
    <!-- Fallback Static Hero if no banners -->
    <div class="max-w-7xl mx-auto h-full flex items-center">
        <div class="relative z-10 pb-8 bg-gray-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 px-4 sm:px-6 lg:px-8">
            <main class="mt-10 mx-auto max-w-7xl sm:mt-12 md:mt-16 lg:mt-20 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block">{{ __('Books & School Supplies') }}</span>
                        <span class="block text-secondary-orange">{{ __('For a Better Future') }}</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        {{ __('Discover a wide range of books, stationery, and school essentials. Everything you need for academic success, delivered to your doorstep.') }}
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('products.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-secondary-orange hover:bg-orange-600 md:py-4 md:text-lg">
                                {{ __('Shop Now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="absolute inset-y-0 right-0 w-1/2 bg-gray-800 hidden lg:block">
            <img src="{{ asset('images/banners/hero_placeholder.jpg') }}" alt="{{ __('Hero') }}" class="w-full h-full object-cover opacity-50">
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            on: {
                init: function() {
                    animateSlide(this.slides[this.activeIndex]);
                },
                slideChange: function() {
                    // Reset all
                    document.querySelectorAll('.swiper-slide h1, .swiper-slide p, .swiper-slide a.inline-block').forEach(el => {
                        el.classList.remove('opacity-100', 'translate-y-0');
                        el.classList.add('opacity-0', 'translate-y-4');
                    });

                    // Animate active
                    setTimeout(() => {
                        animateSlide(this.slides[this.activeIndex]);
                    }, 100);
                }
            }
        });

        function animateSlide(slide) {
            if (!slide) return;
            slide.querySelectorAll('h1, p, a.inline-block').forEach(el => {
                el.classList.remove('opacity-0', 'translate-y-4');
                el.classList.add('opacity-100', 'translate-y-0');
            });
        }
    });
</script>

<!-- Categories Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8 text-gray-900">{{ __('Browse by Category') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            <a href="#" class="group block text-center">
                <div class="bg-gray-100 rounded-lg p-6 mb-4 transition transform group-hover:-translate-y-1 group-hover:shadow-lg h-32 flex items-center justify-center">
                    @if($category->image)
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-24 w-24 object-contain">
                    @else
                    <div class="h-20 w-full flex items-center justify-center text-4xl">
                        @if($category->slug == 'books') 📚
                        @elseif($category->slug == 'stationery') ✏️
                        @else 📦
                        @endif
                    </div>
                    @endif
                </div>
                <span class="text-sm font-medium text-gray-900 group-hover:text-primary-600">{{ $category->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 1. Best Selling Section (Bg: White) -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">{{ __('Best Selling') }}</h2>
            <a href="#" class="text-primary-600 hover:text-primary-700 font-semibold">{{ __('View All') }} &rarr;</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            @foreach($bestSelling as $product)
            <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- Banner Separator 1 -->
<!-- Banner Separator 1 -->
@if(isset($banners['separator_1']) && $banners['separator_1']->isNotEmpty())
@foreach($banners['separator_1'] as $banner)
<div class="w-full h-64 md:h-80 overflow-hidden relative group">
    <a href="{{ $banner->link ?? '#' }}" class="block h-full w-full">
        <img src="{{ asset($banner->image_path) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $banner->title }}">
    </a>
</div>
@endforeach
@else
<div class="w-full h-64 md:h-80 overflow-hidden">
    {{-- Fallback --}}
    <img src="{{ asset('images/banners/back_to_school.png') }}" onerror="this.onerror=null; this.src='https://placehold.co/1200x400/2563eb/FFF?text=Back+to+School';" class="w-full h-full object-cover" alt="{{ __('Back to School') }}">
</div>
@endif

<!-- 2. Most Viewed Section (Bg: Gray-50) -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">{{ __('Most Viewed') }}</h2>
            <a href="#" class="text-primary-600 hover:text-primary-700 font-semibold">{{ __('View All') }} &rarr;</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            @foreach($mostViewed as $product)
            <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- Banner Separator 2 -->
<!-- Banner Separator 2 -->
@if(isset($banners['separator_2']) && $banners['separator_2']->isNotEmpty())
@foreach($banners['separator_2'] as $banner)
<div class="w-full h-64 md:h-80 overflow-hidden relative group">
    <a href="{{ $banner->link ?? '#' }}" class="block h-full w-full">
        <img src="{{ asset($banner->image_path) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $banner->title }}">
        @if($banner->title)
        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
            <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tight">{{ $banner->title }}</h2>
        </div>
        @endif
    </a>
</div>
@endforeach
@else
<div class="w-full h-64 md:h-80 overflow-hidden relative bg-amber-100">
    <img src="{{ asset('images/banners/reading_festival.png') }}" onerror="this.style.display='none'" class="w-full h-full object-cover absolute inset-0" alt="{{ __('Reading Festival') }}">
    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-r from-amber-500 to-orange-400 opacity-90" id="fallback-banner-2">
        <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tight">{{ __('Reading Festival') }} 📚</h2>
    </div>
</div>
@endif

<!-- 3. Recommended Section (Bg: Amber-50) -->
<section class="py-16 bg-amber-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-amber-900">{{ __('Recommended For You') }}</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            @foreach($recommended as $product)
            <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- Banner Separator 3 -->
<!-- Banner Separator 3 -->
@if(isset($banners['separator_3']) && $banners['separator_3']->isNotEmpty())
@foreach($banners['separator_3'] as $banner)
<div class="w-full h-64 md:h-80 overflow-hidden relative group">
    <a href="{{ $banner->link ?? '#' }}" class="block h-full w-full">
        <img src="{{ asset($banner->image_path) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $banner->title }}">
        @if($banner->title)
        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
            <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tight">{{ $banner->title }}</h2>
        </div>
        @endif
    </a>
</div>
@endforeach
@else
<div class="w-full h-64 md:h-80 overflow-hidden relative bg-blue-100">
    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-r from-blue-500 to-cyan-400 opacity-90">
        <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tight">{{ __('Art & Creativity') }} 🎨</h2>
    </div>
</div>
@endif

<!-- 4. Suggestions Section (Bg: Blue-50) -->
<section class="py-16 bg-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-blue-900">{{ __('Suggestions') }}</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            @foreach($suggestions as $product)
            <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endsection