@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-[#161615] min-h-screen py-12 font-sans">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-gray-500 dark:text-gray-400 mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-secondary-orange transition-colors">{{ __('Home') }}</a>
                </li>
                <li><span class="text-gray-300">/</span></li>
                <li class="text-gray-900 dark:text-white font-medium" aria-current="page">{{ $page->title }}</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <header class="mb-10 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">
                {{ $page->title }}
            </h1>
            <div class="w-20 h-1.5 bg-secondary-orange mx-auto rounded-full"></div>
        </header>

        <!-- Cover Image -->
        <div class="mb-12 relative rounded-2xl overflow-hidden shadow-lg aspect-video group">
             <img 
                src="{{ asset('images/categories/stationery.png') }}" 
                alt="{{ $page->title }}" 
                class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-60"></div>
        </div>

        <!-- Content -->
        <div class="prose prose-lg prose-slate dark:prose-invert max-w-none 
            prose-p:text-gray-600 dark:prose-p:text-gray-300 prose-p:leading-loose
            prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white
            prose-a:text-secondary-orange prose-a:no-underline hover:prose-a:underline">
            {!! $page->content !!}
        </div>

        <!-- Share / Footer -->
        <div class="mt-16 pt-8 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                {{ __('Published on Abjadia Store') }}
            </div>
            <div class="flex space-x-4 rtl:space-x-reverse text-gray-400">
                <a href="#" class="hover:text-secondary-orange transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                <a href="#" class="hover:text-secondary-orange transition-colors"><i class="fab fa-facebook text-xl"></i></a>
                <a href="#" class="hover:text-secondary-orange transition-colors"><i class="fab fa-whatsapp text-xl"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection
