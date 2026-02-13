{{-- Updated: 2026-02-12 01:25 - Mobile Filter Fixed --}}
@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-primary-dark mb-6">{{ __('All Products') }}</h1>

        <div class="flex flex-col md:flex-row gap-6">
            
            <!-- MOBILE FILTER IMPLEMENTATION (Visible only on small screens) -->
            <div class="md:hidden mb-4">
                <!-- Filter Toggle Button -->
                <button type="button" onclick="openMobileFilter()" id="mobile-filter-open" class="flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    {{ __('Filter Products') }}
                </button>

                <!-- Filter Drawer Overlay -->
                <div id="mobile-filter-overlay" onclick="closeMobileFilter()" class="fixed inset-0 bg-black bg-opacity-30 z-40 hidden transition-opacity duration-300 opacity-0"></div>

                <!-- Filter Drawer Content (Centered Modal) -->
                <div id="mobile-filter-drawer" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none hidden opacity-0 transition-opacity duration-300 ease-in-out">
                    <!-- Modal Container -->
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md pointer-events-auto transform scale-95 transition-transform duration-300" style="max-height: 80vh; display: flex; flex-direction: column;" id="mobile-filter-container">
                        
                        <form action="{{ route('products.index') }}" method="GET" style="max-height: 80vh; display: flex; flex-direction: column;">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-4 border-b" style="flex-shrink: 0;">
                                <h3 class="font-bold text-lg text-gray-800">{{ __('Filter Products') }}</h3>
                                <button type="button" onclick="closeMobileFilter()" id="mobile-filter-close" class="p-2 bg-gray-100 rounded-full text-gray-500 hover:bg-gray-200 transition-colors">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Scrollable Content -->
                            <div class="p-4 space-y-4" style="overflow-y: scroll; flex: 1;">
                                
                                <!-- Categories Accordion -->
                                <div class="border rounded-lg overflow-hidden">
                                    <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('mobile-cat-accordion', 'mobile-cat-icon')">
                                        <span class="font-bold text-gray-700">{{ __('Categories') }}</span>
                                        <svg id="mobile-cat-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="mobile-cat-accordion" class="hidden bg-white border-t max-h-60 overflow-y-auto">
                                        <div class="p-4">
                                            <ul class="space-y-3">
                                                <li>
                                                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                        <input type="radio" name="category" value="" class="form-radio text-secondary-orange focus:ring-secondary-orange h-4 w-4 mr-2" {{ !request('category') ? 'checked' : '' }}>
                                                        {{ __('All') }}
                                                    </label>
                                                </li>
                                                @foreach($categories as $category)
                                                <li>
                                                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                        <input type="radio" name="category" value="{{ $category->slug }}" class="form-radio text-secondary-orange focus:ring-secondary-orange h-4 w-4 mr-2" {{ request('category') == $category->slug ? 'checked' : '' }}>
                                                        {{ $category->name }}
                                                    </label>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Type Accordion -->
                                <div class="border rounded-lg overflow-hidden">
                                    <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('mobile-type-accordion', 'mobile-type-icon')">
                                        <span class="font-bold text-gray-700">{{ __('Type') }}</span>
                                        <svg id="mobile-type-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="mobile-type-accordion" class="hidden bg-white p-4 border-t">
                                        <ul class="space-y-3">
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="type" value="book" class="form-radio text-secondary-orange focus:ring-secondary-orange h-4 w-4 mr-2" {{ request('type') == 'book' ? 'checked' : '' }}>
                                                    {{ __('Books') }}
                                                </label>
                                            </li>
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="type" value="supply" class="form-radio text-secondary-orange focus:ring-secondary-orange h-4 w-4 mr-2" {{ request('type') == 'supply' ? 'checked' : '' }}>
                                                    {{ __('School Supplies') }}
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Translator Accordion -->
                                <div class="border rounded-lg overflow-hidden">
                                    <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('mobile-translator-accordion', 'mobile-translator-icon')">
                                        <span class="font-bold text-gray-700">{{ __('Translators') }}</span>
                                        <svg id="mobile-translator-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="mobile-translator-accordion" class="hidden bg-white p-4 border-t max-h-48 overflow-y-auto">
                                        <ul class="space-y-3">
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="translator" value="" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ !request('translator') ? 'checked' : '' }}>
                                                    {{ __('All') }}
                                                </label>
                                            </li>
                                            @foreach($translators as $translator)
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="translator" value="{{ $translator->id }}" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ request('translator') == $translator->id ? 'checked' : '' }}>
                                                    {{ $translator->name }}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <!-- Price Accordion -->
                                <div class="border rounded-lg overflow-hidden">
                                    <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('mobile-price-accordion', 'mobile-price-icon')">
                                        <span class="font-bold text-gray-700">{{ __('Price') }}</span>
                                        <svg id="mobile-price-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="mobile-price-accordion" class="hidden bg-white p-4 border-t">
                                        <div class="flex items-center space-x-4 space-x-reverse">
                                            <div class="flex-1">
                                                <label class="text-xs text-gray-500 mb-1 block">{{ __('From') }}</label>
                                                <input type="number" name="min_price" value="{{ request('min_price') }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-secondary-orange focus:border-secondary-orange" placeholder="0">
                                            </div>
                                            <div class="flex-1">
                                                <label class="text-xs text-gray-500 mb-1 block">{{ __('To') }}</label>
                                                <input type="number" name="max_price" value="{{ request('max_price') }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-secondary-orange focus:border-secondary-orange" placeholder="50000">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Authors Accordion -->
                                <div class="border rounded-lg overflow-hidden">
                                    <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('mobile-author-accordion', 'mobile-author-icon')">
                                        <span class="font-bold text-gray-700">{{ __('Authors') }}</span>
                                        <svg id="mobile-author-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="mobile-author-accordion" class="hidden bg-white p-4 border-t max-h-48 overflow-y-auto">
                                        <ul class="space-y-3">
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="author" value="" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ !request('author') ? 'checked' : '' }}>
                                                    {{ __('All') }}
                                                </label>
                                            </li>
                                            @foreach($authors as $author)
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="author" value="{{ $author->id }}" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ request('author') == $author->id ? 'checked' : '' }}>
                                                    {{ $author->name }}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <!-- Publisher Accordion -->
                                <div class="border rounded-lg overflow-hidden">
                                    <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('mobile-publisher-accordion', 'mobile-publisher-icon')">
                                        <span class="font-bold text-gray-700">{{ __('Publisher') }}</span>
                                        <svg id="mobile-publisher-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="mobile-publisher-accordion" class="hidden bg-white p-4 border-t max-h-48 overflow-y-auto">
                                        <ul class="space-y-3">
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="publisher" value="" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ !request('publisher') ? 'checked' : '' }}>
                                                    {{ __('All') }}
                                                </label>
                                            </li>
                                            @foreach($publishers as $publisher)
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="publisher" value="{{ $publisher->id }}" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ request('publisher') == $publisher->id ? 'checked' : '' }}>
                                                    {{ $publisher->name }}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <!-- Translator Accordion -->
                                <div class="border rounded-lg overflow-hidden">
                                    <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('mobile-translator-accordion', 'mobile-translator-icon')">
                                        <span class="font-bold text-gray-700">{{ __('Translators') }}</span>
                                        <svg id="mobile-translator-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="mobile-translator-accordion" class="hidden bg-white p-4 border-t max-h-48 overflow-y-auto">
                                        <ul class="space-y-3">
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="translator" value="" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ !request('translator') ? 'checked' : '' }}>
                                                    {{ __('All') }}
                                                </label>
                                            </li>
                                            @foreach($translators as $translator)
                                            <li>
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="radio" name="translator" value="{{ $translator->id }}" class="form-radio text-secondary-orange h-4 w-4 mr-2" {{ request('translator') == $translator->id ? 'checked' : '' }}>
                                                    {{ $translator->name }}
                                                </label>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="p-4 border-t bg-gray-50" style="flex-shrink: 0;">
                                <button type="submit" class="w-full py-3 bg-secondary-orange hover:bg-orange-600 text-white font-bold rounded-lg shadow-md transition-colors">
                                    {{ __('Apply Filter') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- DESKTOP FILTER SIDEBAR (Hidden on mobile, Visible separately on desktop) -->
            <div class="hidden md:block flex-shrink-0" style="width: 320px; min-width: 320px; max-width: 320px;">
                <div class="bg-white p-4 rounded-lg shadow-sm sticky top-24 space-y-4 w-full">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-xl text-primary-dark">{{ __('Filter By') }}</h3>
                        <a href="{{ route('products.index') }}" class="text-xs text-gray-500 hover:text-secondary-orange underline">{{ __('Clear All') }}</a>
                    </div>

                    <!-- Categories Accordion -->
                    <div class="border rounded-lg overflow-hidden">
                        <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('desktop-cat-accordion', 'desktop-cat-icon')">
                            <span class="font-bold text-gray-700">{{ __('Categories') }}</span>
                            <svg id="desktop-cat-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="desktop-cat-accordion" class="hidden bg-white p-4 border-t overflow-hidden">
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('products.index') }}" class="flex items-center text-sm {{ !request('category') && !request('type') ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ !request('category') && !request('type') ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(!request('category') && !request('type')) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ __('All') }}
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="flex items-center text-sm {{ request('category') == $category->slug ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ request('category') == $category->slug ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(request('category') == $category->slug) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ $category->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Type Accordion -->
                    <div class="border rounded-lg overflow-hidden">
                        <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('desktop-type-accordion', 'desktop-type-icon')">
                            <span class="font-bold text-gray-700">{{ __('Type') }}</span>
                            <svg id="desktop-type-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="desktop-type-accordion" class="hidden bg-white p-4 border-t overflow-hidden">
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('products.index', ['type' => 'book']) }}" class="flex items-center text-sm {{ request('type') == 'book' ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ request('type') == 'book' ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(request('type') == 'book') <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ __('Books') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('products.index', ['type' => 'supply']) }}" class="flex items-center text-sm {{ request('type') == 'supply' ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ request('type') == 'supply' ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(request('type') == 'supply') <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ __('School Supplies') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Price Accordion -->
                    <div class="border rounded-lg overflow-hidden">
                        <button type="button" class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('desktop-price-accordion', 'desktop-price-icon')">
                            <span class="font-bold text-gray-700">{{ __('Price') }}</span>
                            <svg id="desktop-price-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="desktop-price-accordion" class="hidden bg-white p-4 border-t overflow-hidden">
                            <form action="{{ route('products.index') }}" method="GET">
                                <!-- Preserve existing filters -->
                                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                                @if(request('author')) <input type="hidden" name="author" value="{{ request('author') }}"> @endif
                                @if(request('publisher')) <input type="hidden" name="publisher" value="{{ request('publisher') }}"> @endif
                                @if(request('translator')) <input type="hidden" name="translator" value="{{ request('translator') }}"> @endif
                                
                                <div class="flex items-center gap-4">
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs text-gray-500 mb-1 block">{{ __('From') }}</label>
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="w-full max-w-full border-gray-300 rounded-md text-sm focus:ring-secondary-orange focus:border-secondary-orange" placeholder="0" min="0">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label class="text-xs text-gray-500 mb-1 block">{{ __('To') }}</label>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="w-full max-w-full border-gray-300 rounded-md text-sm focus:ring-secondary-orange focus:border-secondary-orange" placeholder="50000" min="0">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="w-full py-2 bg-secondary-orange hover:bg-orange-600 text-white font-bold rounded-lg shadow-sm transition-colors text-sm">
                                        {{ __('Apply Filter') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Authors Accordion -->
                    <div class="border rounded-lg overflow-hidden">
                        <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('desktop-author-accordion', 'desktop-author-icon')">
                            <span class="font-bold text-gray-700">{{ __('Authors') }}</span>
                            <svg id="desktop-author-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="desktop-author-accordion" class="hidden bg-white p-4 border-t max-h-60 overflow-y-auto">
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['author' => null]) }}" class="flex items-center text-sm {{ !request('author') ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ !request('author') ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(!request('author')) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ __('All') }}
                                    </a>
                                </li>
                                @foreach($authors as $author)
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['author' => $author->id]) }}" class="flex items-center text-sm {{ request('author') == $author->id ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ request('author') == $author->id ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(request('author') == $author->id) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ $author->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Publisher Accordion -->
                    <div class="border rounded-lg overflow-hidden">
                        <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('desktop-publisher-accordion', 'desktop-publisher-icon')">
                            <span class="font-bold text-gray-700">{{ __('Publisher') }}</span>
                            <svg id="desktop-publisher-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="desktop-publisher-accordion" class="hidden bg-white p-4 border-t max-h-60 overflow-y-auto">
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['publisher' => null]) }}" class="flex items-center text-sm {{ !request('publisher') ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ !request('publisher') ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(!request('publisher')) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ __('All') }}
                                    </a>
                                </li>
                                @foreach($publishers as $publisher)
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['publisher' => $publisher->id]) }}" class="flex items-center text-sm {{ request('publisher') == $publisher->id ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ request('publisher') == $publisher->id ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(request('publisher') == $publisher->id) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ $publisher->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Translator Accordion -->
                    <div class="border rounded-lg overflow-hidden">
                        <button class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors" onclick="toggleAccordion('desktop-translator-accordion', 'desktop-translator-icon')">
                            <span class="font-bold text-gray-700">{{ __('Translators') }}</span>
                            <svg id="desktop-translator-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="desktop-translator-accordion" class="hidden bg-white p-4 border-t max-h-60 overflow-y-auto">
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['translator' => null]) }}" class="flex items-center text-sm {{ !request('translator') ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ !request('translator') ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(!request('translator')) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ __('All') }}
                                    </a>
                                </li>
                                @foreach($translators as $translator)
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['translator' => $translator->id]) }}" class="flex items-center text-sm {{ request('translator') == $translator->id ? 'text-secondary-orange font-bold' : 'text-gray-600 hover:text-secondary-orange' }}">
                                        <span class="w-4 h-4 rounded-full border mr-2 flex items-center justify-center {{ request('translator') == $translator->id ? 'border-secondary-orange bg-secondary-orange' : 'border-gray-300' }}">
                                            @if(request('translator') == $translator->id) <span class="w-2 h-2 rounded-full bg-white"></span> @endif
                                        </span>
                                        {{ $translator->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Filter Script -->
            <script>
                function toggleAccordion(contentId, iconId) {
                    const content = document.getElementById(contentId);
                    const icon = document.getElementById(iconId);
                    
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        content.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                    }
                }

                function openMobileFilter() {
                    const overlay = document.getElementById('mobile-filter-overlay');
                    const drawer = document.getElementById('mobile-filter-drawer');
                    const container = document.getElementById('mobile-filter-container');
                    
                    if (!overlay || !drawer || !container) return;

                    // Show overlay
                    overlay.classList.remove('hidden', 'opacity-0');
                    
                    // Show drawer (but keep pointer-events-none, only container is clickable)
                    drawer.classList.remove('hidden', 'opacity-0');
                    
                    // Lock scroll
                    document.body.style.overflow = 'hidden';
                    
                    setTimeout(() => {
                        container.classList.remove('scale-95');
                        container.classList.add('scale-100');
                    }, 10);
                }

                function closeMobileFilter() {
                    const overlay = document.getElementById('mobile-filter-overlay');
                    const drawer = document.getElementById('mobile-filter-drawer');
                    const container = document.getElementById('mobile-filter-container');
                    
                    if (!overlay || !drawer || !container) return;

                    container.classList.remove('scale-100');
                    container.classList.add('scale-95');
                    
                    overlay.classList.add('opacity-0');
                    drawer.classList.add('opacity-0');
                    
                    setTimeout(() => {
                        overlay.classList.add('hidden');
                        drawer.classList.add('hidden');
                        document.body.style.overflow = ''; // Unlock scroll
                    }, 300);
                }

                // Add close on click outside for the drawer background
                document.addEventListener('DOMContentLoaded', function() {
                    const drawer = document.getElementById('mobile-filter-drawer');
                    if (drawer) {
                        drawer.addEventListener('click', (e) => {
                            if (e.target === drawer) {
                                closeMobileFilter();
                            }
                        });
                    }
                });
            </script>

            <!-- Product Grid -->
            <div class="w-full md:flex-1">
                @if($products->count() > 0)
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
                @else
                <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500">{{ __('No products found.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection