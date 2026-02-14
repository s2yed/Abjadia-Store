@extends('layouts.app')

@section('title', $setting->site_name ?? __('Abjadia Store - Books and School Supplies'))
@section('meta_description', $setting->site_description ?? __('Discover a wide range of books, stationery, and school essentials at Abjadia Store. Everything you need for academic success.'))
@section('meta_keywords', $setting->site_keywords ?? 'abjadia, books, school supplies, stationery, kids books, educational supplies')

@section('content')
    {{-- Hero Section --}}
    <!-- Banners Count: {{ isset($banners['hero_image']) ? $banners['hero_image']->count() : 'NULL' }} -->
    @if(isset($banners['hero_image']) && $banners['hero_image']->count() > 0)
        <hero-slider-component :banners="{{ $banners['hero_image']->toJson() }}"></hero-slider-component>
    @else
        {{-- Fallback Static Hero if no banners --}}
        <div class="relative bg-primary-dark overflow-hidden h-[400px]">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1507842217121-9e859bd67629?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80" class="w-full h-full object-cover opacity-30">
            </div>
            <div class="relative max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ __('Welcome to Abjadia') }}</h1>
                <p class="mt-6 text-xl text-gray-300 max-w-3xl">{{ __('Your one-stop destination for all your reading and school supply needs.') }}</p>
                <div class="mt-10">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-secondary-orange hover:bg-orange-600">
                        {{ __('Shop Now') }}
                    </a>
                </div>
            </div>
        </div>
    @endif


<!-- Categories Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8 text-gray-900">{{ __('Browse by Category') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group block text-center">
                <div class="bg-gray-100 rounded-lg p-6 mb-4 transition transform group-hover:-translate-y-1 group-hover:shadow-lg h-32 flex items-center justify-center">
                    @if($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" class="h-24 w-24 object-contain">
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
        <img src="{{ $banner->image_path }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $banner->title }}">
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
        <img src="{{ $banner->image_path }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $banner->title }}">
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
        <img src="{{ $banner->image_path }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $banner->title }}">
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