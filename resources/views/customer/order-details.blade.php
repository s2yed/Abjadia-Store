@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-primary-dark text-white">
                        <h3 class="font-bold text-lg">{{ __('My Account') }}</h3>
                    </div>
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse mb-4">
                            <div class="h-10 w-10 rounded-full bg-secondary-orange text-white flex items-center justify-center font-bold text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    </div>
                    <nav class="flex flex-col p-2 space-y-1">
                        <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-secondary-orange rounded transition-colors {{ request()->routeIs('customer.profile') ? 'bg-orange-50 text-secondary-orange font-medium' : '' }}">
                            <span class="mr-3 rtl:ml-3 rtl:mr-0">👤</span> {{ __('My Profile') }}
                        </a>
                        <a href="{{ route('customer.orders') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-secondary-orange rounded transition-colors {{ request()->routeIs('customer.orders*') ? 'bg-orange-50 text-secondary-orange font-medium' : '' }}">
                            <span class="mr-3 rtl:ml-3 rtl:mr-0">📦</span> {{ __('My Orders') }}
                        </a>
                        <a href="{{ route('customer.favorites') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 hover:text-secondary-orange rounded transition-colors {{ request()->routeIs('customer.favorites') ? 'bg-orange-50 text-secondary-orange font-medium' : '' }}">
                            <span class="mr-3 rtl:ml-3 rtl:mr-0">❤️</span> {{ __('Favorites') }}
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-6">
                <!-- Header & Back -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Order # :id details', ['id' => $order->id]) }}</h2>
                    <a href="{{ route('customer.orders') }}" class="text-gray-500 hover:text-secondary-orange transition-colors flex items-center text-sm font-medium">
                        <span class="rtl:hidden">&larr;</span>
                        <span class="hidden rtl:inline">&rarr;</span>
                        <span class="mx-1">{{ __('Back to Order History') }}</span>
                    </a>
                </div>

                <!-- Order Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-gray-100 pb-6">
                            <div>
                                <div class="text-sm text-gray-500 uppercase tracking-wide mb-1">{{ __('Order Placed') }}</div>
                                <div class="font-medium text-gray-900">{{ $order->created_at->translatedFormat('F j, Y') }}</div>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <div class="text-sm text-gray-500 uppercase tracking-wide mb-1">{{ __('Total Amount') }}</div>
                                <div class="font-bold text-2xl text-secondary-orange">{{ number_format($order->total_price, 2) }} {{ __('SAR') }}</div>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ __($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="space-y-6">
                            <h3 class="font-semibold text-lg text-gray-800 border-b border-gray-50 pb-2">{{ __('Items') }}</h3>
                            @foreach($order->items as $item)
                            <div class="flex items-center py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors -mx-4 px-4 rounded">
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-white">
                                    @if($item->product && $item->product->image)
                                    <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : (Str::startsWith($item->product->image, 'images/') ? asset($item->product->image) : asset('storage/' . $item->product->image)) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover object-center">
                                    @else
                                    <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs text-center border rounded">
                                        {{ __('No Image') }}
                                    </div>
                                    @endif
                                </div>
                                <div class="mx-4 flex-1 flex flex-col">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                            <h3>{{ $item->product_name }}</h3>
                                            <p class="ml-4 rtl:ml-0 rtl:mr-4">{{ number_format($item->price, 2) }} {{ __('SAR') }}</p>
                                        </div>
                                        @if($item->product)
                                        <p class="mt-1 text-sm text-gray-500">{{ $item->product->category ? $item->product->category->name : '' }}</p>
                                        @else
                                        <p class="mt-1 text-sm text-red-400 italic">{{ __('No longer available') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex-1 flex items-end justify-between text-sm">
                                        <p class="text-gray-500">{{ __('Qty') }} {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Addresses & Summary Grid -->
                        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8">
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-3">{{ __('Shipping Address') }}</h3>
                                <div class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg">
                                    @if($order->shipping_address)
                                    @foreach(explode('|', $order->shipping_address) as $line)
                                    <div class="mb-1">{{ $line }}</div>
                                    @endforeach
                                    @else
                                    N/A
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-3">
                                <h3 class="font-semibold text-gray-800 mb-3">{{ __('Payment Summary') }}</h3>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <p>{{ __('Subtotal') }}</p>
                                    <p>{{ number_format($order->total_price, 2) }} {{ __('SAR') }}</p>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <p>{{ __('Shipping') }}</p>
                                    <p>{{ __('Free') }}</p>
                                </div>
                                <div class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t border-gray-100 mt-2">
                                    <p>{{ __('Grand Total') }}</p>
                                    <p class="text-secondary-orange text-xl">{{ number_format($order->total_price, 2) }} {{ __('SAR') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection