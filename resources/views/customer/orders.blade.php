@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
            {{-- Sidebar --}}
            <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
                <nav class="space-y-1">
                    <a href="{{ route('customer.profile') }}" class="text-gray-900 hover:text-gray-900 hover:bg-gray-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                        <svg class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 rtl:-mr-1 rtl:ml-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="truncate">
                            {{ __('My Profile') }}
                        </span>
                    </a>

                    <a href="{{ route('customer.orders') }}" class="bg-gray-50 text-secondary-orange hover:bg-white group rounded-md px-3 py-2 flex items-center text-sm font-medium border-l-4 rtl:border-l-0 rtl:border-r-4 border-secondary-orange shadow-sm" aria-current="page">
                        <svg class="text-secondary-orange flex-shrink-0 -ml-1 mr-3 rtl:-mr-1 rtl:ml-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="truncate">
                            {{ __('My Orders') }}
                        </span>
                    </a>

                    <a href="{{ route('customer.favorites') }}" class="text-gray-900 hover:text-gray-900 hover:bg-gray-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                        <svg class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-3 rtl:-mr-1 rtl:ml-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="truncate">
                            {{ __('Favorites') }}
                        </span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left rtl:text-right text-red-600 hover:text-red-700 hover:bg-red-50 group rounded-md px-3 py-2 flex items-center text-sm font-medium">
                            <svg class="text-red-400 group-hover:text-red-500 flex-shrink-0 -ml-1 mr-3 rtl:-mr-1 rtl:ml-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="truncate">
                                {{ __('Sign out') }}
                            </span>
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="lg:col-span-9">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h1 class="text-xl font-bold text-gray-800 mb-4">{{ __('My Orders') }}</h1>

                        @if($orders->count() > 0)
                        <div class="space-y-4">
                            @foreach($orders as $order)
                            <div class="border border-gray-100 rounded-lg p-6 hover:shadow-md transition-shadow bg-white">
                                <div class="flex flex-col md:flex-row justify-between md:items-center">
                                    <div class="mb-4 md:mb-0">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="font-bold text-lg text-gray-800">{{ __('Order #') }}{{ $order->id }}</span>
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase
                                                        @if($order->status == 'completed') bg-green-100 text-green-800
                                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                        @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                                                        @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ __($order->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ __('Placed on') }} {{ $order->created_at->translatedFormat('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6 rtl:flex-row-reverse">
                                        <div class="text-right rtl:text-left">
                                            <div class="text-sm text-gray-500">{{ __('Total') }}</div>
                                            <div class="font-bold text-secondary-orange">{{ number_format($order->total_price, 2) }} {{ __('SAR') }}</div>
                                        </div>
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="bg-gray-50 text-gray-700 hover:bg-primary-dark hover:text-white px-4 py-2 rounded-md text-sm font-medium transition-colors border border-gray-200">
                                            {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="text-4xl mb-4">📦</div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('No orders yet') }}</h3>
                            <p class="mt-1 text-gray-500">{{ __('Start shopping properly to see your orders here.') }}</p>
                            <a href="/" class="mt-6 inline-flex items-center px-4 py-2 bg-secondary-orange border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:outline-none transition ease-in-out duration-150">
                                {{ __('Start Shopping') }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection