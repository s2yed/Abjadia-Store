@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="max-w-4xl mx-auto mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
            {{ $page->title }}
        </h1>
        <div class="mt-4 w-24 h-1 bg-secondary-orange mx-auto rounded-full"></div>
    </div>

    <!-- Content Card -->
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden ring-1 ring-gray-900/5 dark:ring-white/10">
        
        <!-- Optional: Hero Image (if available or generic) -->
        <div class="h-32 w-full bg-gradient-to-r from-secondary-orange to-orange-400 opacity-90 relative overflow-hidden flex items-center justify-center">
            <svg class="w-16 h-16 text-white opacity-20 transform rotate-12" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
            </svg>
            <div class="absolute inset-0 bg-black opacity-10"></div>
        </div>

        <div class="px-6 py-8 sm:p-10 lg:p-12">
            <!-- Article Content -->
            <article class="prose prose-lg prose-slate dark:prose-invert mx-auto max-w-none">
                {!! $page->content !!}
            </article>
        </div>

        <!-- Footer / Sharing -->
        <div class="px-6 py-6 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ __('Updated') }} {{ $page->updated_at->diffForHumans() }}</span>
            </div>
            
            <div class="flex space-x-4">
                <!-- Social Icons Placeholder -->
            </div>
        </div>
    </div>

    <!-- Back to Home -->
    <div class="max-w-4xl mx-auto mt-8 text-center">
        <a href="{{ route('home') }}" class="inline-flex items-center text-secondary-orange hover:text-orange-600 font-medium transition-colors group">
            <svg class="w-5 h-5 mr-2 rtl:rotate-180 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('Back to Home') }}
        </a>
    </div>
</div>

<style>
    /* Ensure text visibility regardless of theme */
    .prose {
        color: #374151; /* gray-700 */
    }
    .dark .prose {
        color: #d1d5db; /* gray-300 */
    }
    .prose h1, .prose h2, .prose h3, .prose h4, .prose strong {
        color: #111827; /* gray-900 */
    }
    .dark .prose h1, .dark .prose h2, .dark .prose h3, .dark .prose h4, .dark .prose strong {
        color: #f9fafb; /* gray-50 */
    }
    .prose a {
        color: #f75500;
        text-decoration: none;
    }
    .prose a:hover {
        text-decoration: underline;
    }
    /* Fix lists in RTL */
    .rtl .prose ul, .rtl .prose ol {
        padding-left: 0;
        padding-right: 1.625em;
    }
    .rtl .prose li {
        padding-left: 0;
        padding-right: 0.375em;
    }
</style>
@endsection
