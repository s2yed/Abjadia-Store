@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
<div class="min-h-screen bg-white py-16 px-4 sm:px-6 lg:px-8 transition-colors duration-500">
    
    <!-- Header Section -->
    <div class="max-w-4xl mx-auto mb-56 text-center animate-fade-in-down">
        <h1 class="text-5xl font-black lg:text-7xl tracking-tight mb-10">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#e67e22] via-[#f39c12] to-orange-400">
                {{ $page->title }}
            </span>
        </h1>
        <div class="flex justify-center items-center gap-4 sm:gap-6">
            <div class="h-1.5 w-12 sm:w-20 bg-secondary-orange/20 rounded-full"></div>
            <div class="h-1.5 w-16 sm:w-24 bg-secondary-orange rounded-full shadow-lg shadow-orange-500/20"></div>
            <div class="relative flex items-center justify-center">
                <div class="absolute inset-0 h-6 w-6 bg-secondary-orange/30 rounded-full animate-ping"></div>
                <div class="h-5 w-5 sm:h-6 sm:w-6 bg-secondary-orange rounded-full shadow-xl shadow-orange-500/40"></div>
            </div>
            <div class="h-1.5 w-16 sm:w-24 bg-secondary-orange rounded-full shadow-lg shadow-orange-500/20"></div>
            <div class="h-1.5 w-12 sm:w-20 bg-secondary-orange/20 rounded-full"></div>
        </div>
    </div>

    <!-- Content Card -->
    <div class="max-w-4xl mx-auto bg-white shadow-[0_32px_128px_-16px_rgba(0,0,0,0.06)] rounded-[3rem] overflow-hidden border border-slate-100 transform transition-all duration-700 group">
        
        <!-- Hero Decorative Area -->
        @if($page->image_path)
        <div class="h-[32rem] w-full relative overflow-hidden flex items-center justify-center">
            <img src="{{ asset('storage/' . $page->image_path) }}" 
                 alt="{{ $page->title }}" 
                 class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-1000">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        </div>
        @else
        <div class="h-64 w-full bg-slate-50 relative overflow-hidden flex items-center justify-center border-b border-slate-100">
            <!-- Dynamic abstract background -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 2px 2px, #e67e22 1px, transparent 0); background-size: 24px 24px;"></div>
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-orange-400/10 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-secondary-orange/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
            
            <div class="relative z-10 p-8 scale-110 shadow-2xl rounded-full bg-white border border-slate-100 transform group-hover:rotate-6 transition-transform duration-700">
                <svg class="w-16 h-16 text-secondary-orange drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        @endif

        <div class="px-8 py-16 sm:px-14 sm:py-24">
            <!-- Article Content -->
            <article class="prose prose-lg sm:prose-xl mx-auto max-w-none 
                prose-headings:font-black lg:prose-headings:text-5xl prose-headings:tracking-tight prose-headings:text-slate-900
                prose-p:text-slate-800 prose-p:leading-relaxed
                prose-a:text-secondary-orange prose-a:no-underline prose-a:font-bold prose-a:border-b-2 prose-a:border-transparent hover:prose-a:border-secondary-orange transition-all duration-300">
                {!! $page->content !!}
            </article>
        </div>

        <!-- Footer / Info -->
        <div class="px-8 py-10 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5 text-sm">
                <div class="p-3.5 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <svg class="w-6 h-6 text-secondary-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                   <span class="block font-bold text-slate-800">{{ __('Last updated') }}</span>
                   <span class="text-slate-500">{{ $page->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            
            <div class="flex gap-4">
                <span class="px-4 py-2 rounded-xl bg-white text-secondary-orange text-sm font-bold border border-slate-100">
                    {{ __('Official Page') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Back to Home -->
    <div class="max-w-4xl mx-auto mt-20 text-center animate-fade-in-up">
        <a href="{{ route('home') }}" class="inline-flex items-center px-10 py-5 rounded-2xl bg-white text-secondary-orange font-black shadow-xl shadow-orange-500/5 border border-slate-100 hover:shadow-orange-500/15 hover:-translate-y-1 transition-all duration-500 group">
            <svg class="w-6 h-6 mr-3 rtl:rotate-180 transform group-hover:-translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('Back to Home') }}
        </a>
    </div>
</div>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animate-fade-in-down {
        animation: fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* RTL Support for Typography */
    .rtl .prose ul, .rtl .prose ol {
        padding-left: 0;
        padding-right: 1.625em;
    }
</style>
@endsection


