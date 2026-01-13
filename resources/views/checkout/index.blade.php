@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-primary-dark mb-8">{{ __('Checkout') }}</h1>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Checkout Form -->
            <div class="w-full lg:w-2/3">
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('Shipping Information') }}</h2>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Full Name') }}</label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name" autocomplete="name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm" required>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone Number') }}</label>
                                <div class="mt-1">
                                    <input type="text" name="phone" id="phone" autocomplete="tel" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm" required>
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email" autocomplete="email" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm" required>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="address" class="block text-sm font-medium text-gray-700">{{ __('Address') }}</label>
                                <div class="mt-1">
                                    <div class="mt-1">
                                        <textarea id="address" name="address" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('Payment Method') }}</h2>
                        <div class="mt-4 space-y-4">
                            <div class="flex items-center">
                                <input id="payment_cod" name="payment_method" type="radio" value="COD" checked class="focus:ring-secondary-orange h-4 w-4 text-secondary-orange border-gray-300">
                                <label for="payment_cod" class="mx-3 block text-sm font-medium text-gray-700">
                                    {{ __('Cash on Delivery (COD)') }}
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="payment_card" name="payment_method" type="radio" value="Card" class="focus:ring-secondary-orange h-4 w-4 text-secondary-orange border-gray-300">
                                <label for="payment_card" class="mx-3 block text-sm font-medium text-gray-700">
                                    {{ __('Credit Card (Coming Soon)') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('Order Summary') }}</h2>
                    <ul class="divide-y divide-gray-200 mb-4">
                        @foreach($cart as $item)
                        <li class="py-4 flex">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md border border-gray-200 overflow-hidden">
                                @if(isset($item['image']) && $item['image'])
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">{{ __('No Image') }}</div>
                                @endif
                            </div>
                            <div class="mx-4 flex-1 flex flex-col">
                                <div>
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h3>{{ $item['name'] }}</h3>
                                        <p class="mx-4">{{ $item['price'] * $item['quantity'] }} {{ __('SAR') }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ $item['quantity'] }} x {{ $item['price'] }} {{ __('SAR') }}</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="flow-root border-t border-gray-200 pt-4">
                        <dl class="-my-4 text-sm divide-y divide-gray-200">
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Subtotal') }}</dt>
                                <dd class="font-medium text-gray-900">{{ $total }} {{ __('SAR') }}</dd>
                            </div>
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Shipping') }}</dt>
                                <dd class="font-medium text-gray-900">{{ __('Free') }}</dd>
                            </div>
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-base font-medium text-gray-900">{{ __('Order Total') }}</dt>
                                <dd class="font-bold text-xl text-primary-dark">{{ $total }} {{ __('SAR') }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-6">
                        <button type="submit" form="checkout-form" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-dark hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark">
                            {{ __('Place Order') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection