@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-primary-dark mb-8">{{ __('Shopping Cart') }}</h1>


        @if(count($cart) > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <!-- Desktop Table View -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Product') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Price') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Quantity') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total') }}
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">{{ __('Remove') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($cartItems as $item)
                                <tr id="cart-item-row-{{ $item->id }}" class="transition-all duration-500">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-16 w-16">
                                                <img class="h-16 w-16 rounded-md object-cover border border-gray-100" src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                            </div>
                                            <div class="mx-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $item->type }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if(isset($item->is_free) && $item->is_free)
                                                <span class="text-green-600 font-bold">{{ __('Free') }}</span>
                                            @else
                                                {{ $item->price }} {{ $settings->currency ?? 'SAR' }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center rtl:flex-row-reverse border border-gray-300 rounded-md">
                                            @if((isset($item->is_free) && $item->is_free) || ($item->is_reward ?? false))
                                                <span class="px-3 py-1 text-sm text-gray-500">{{ $item->quantity }}</span>
                                            @else
                                                <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="decrease" data-id="{{ $item->id }}">
                                                    -
                                                </button>
                                                <input type="text" readonly class="w-12 text-center border-none focus:ring-0 text-sm text-gray-900 quantity-input-{{ $item->id }}" value="{{ $item->quantity }}">
                                                <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="increase" data-id="{{ $item->id }}">
                                                    +
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" id="cart-item-total-{{ $item->id }}">
                                            @if(isset($item->is_free) && $item->is_free)
                                                0 {{ $settings->currency ?? 'SAR' }}
                                            @else
                                                {{ $item->price * $item->quantity }} {{ $settings->currency ?? 'SAR' }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if(!($item->is_reward ?? false))
                                        <div class="flex justify-end">
                                            <button type="button" class="text-red-600 hover:text-red-900 remove-btn" data-id="{{ $item->id }}">{{ __('Remove') }}</button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="block md:hidden divide-y divide-gray-200" id="mobile-cart-items">
                        @foreach($cartItems as $item)
                        <div id="cart-item-mobile-row-{{ $item->id }}" class="p-4 flex flex-col gap-4 transition-all duration-500">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img class="h-16 w-16 rounded-md object-cover border border-gray-100" src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                    </div>
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900">{{ $item->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $item->type }}</p>
                                        <p class="text-sm font-bold text-gray-900 mt-1">
                                            @if(isset($item->is_free) && $item->is_free)
                                                <span class="text-green-600">{{ __('Free') }}</span>
                                            @else
                                                {{ $item->price }} {{ $settings->currency ?? 'SAR' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if(!($item->is_reward ?? false))
                                <button type="button" class="text-gray-400 hover:text-red-600 p-2 remove-btn" data-id="{{ $item->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                @endif
                            </div>

                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center rtl:flex-row-reverse border border-gray-300 rounded-md">
                                    @if((isset($item->is_free) && $item->is_free) || ($item->is_reward ?? false))
                                        <span class="px-3 py-2 text-sm text-gray-500">{{ $item->quantity }}</span>
                                    @else
                                        <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="decrease" data-id="{{ $item->id }}">
                                            -
                                        </button>
                                        <input type="text" readonly class="w-12 text-center border-none focus:ring-0 text-sm text-gray-900 quantity-input-{{ $item->id }}" value="{{ $item->quantity }}">
                                        <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="increase" data-id="{{ $item->id }}">
                                            +
                                        </button>
                                    @endif
                                </div>
                                <div class="text-base font-bold text-primary-dark" id="cart-item-total-mobile-{{ $item->id }}">
                                    @if(isset($item->is_free) && $item->is_free)
                                        0 {{ $settings->currency ?? 'SAR' }}
                                    @else
                                        {{ $item->price * $item->quantity }} {{ $settings->currency ?? 'SAR' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('Order Summary') }}</h2>
                    
                    @if($freeShippingThreshold > 0)
                    <div id="shipping-progress-container" class="mb-6 {{ (($result['free_shipping'] ?? false) || ($shippingEstimation['is_free'] ?? false)) ? 'hidden' : '' }}">
                        @php
                            $remaining = $freeShippingThreshold - $subtotal;
                            $percentage = min(100, max(0, ($subtotal / $freeShippingThreshold) * 100));
                        @endphp
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-semibold text-gray-700 uppercase tracking-wide" id="shipping-progress-text">
                                @if($remaining > 0)
                                    {{ __('Add :amount more for free shipping', ['amount' => $remaining . ' ' . ($settings->currency ?? 'SAR')]) }}
                                @else
                                    {{ __('You unlocked free shipping!') }}
                                @endif
                            </span>
                            <span class="text-xs font-bold text-primary-dark" id="shipping-progress-percent">{{ round($percentage) }}%</span>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-100">
                            <div id="shipping-progress-bar" style="width:{{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-secondary-orange transition-all duration-1000"></div>
                        </div>
                    </div>
                    @endif

                    <div class="flow-root">
                        <dl class="-my-4 text-sm divide-y divide-gray-200">
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Subtotal') }}</dt>
                                <dd class="font-medium text-gray-900" id="cart-subtotal">{{ $subtotal }} {{ $settings->currency ?? 'SAR' }}</dd>
                            </div>
                            <div class="py-4">
                                <label for="shipping_zone_id" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">{{ __('Ship to') }}</label>
                                <select id="shipping_zone_id" name="shipping_zone_id" class="block w-full px-3 py-2 rounded-md border-gray-300 focus:ring-primary-dark focus:border-primary-dark sm:text-sm border transition-colors">
                                    <option value="">{{ __('Select Zone for Estimation') }}</option>
                                    @foreach($shippingZones as $zone)
                                        <option value="{{ $zone->id }}" {{ $selectedZoneId == $zone->id ? 'selected' : '' }}>
                                            {{ is_array($zone->name) ? ($zone->name[app()->getLocale()] ?? $zone->name['en'] ?? '') : $zone->name }}
                                            @if($zone->coverage_areas)
                                                ({{ is_array($zone->coverage_areas) ? implode(', ', $zone->coverage_areas) : $zone->coverage_areas }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Shipping') }}</dt>
                                <dd class="font-medium text-gray-900" id="shipping-cost-display">
                                    @if($shippingEstimation['is_free'])
                                        <span class="text-green-600">{{ __('Free Shipping') }}</span>
                                    @else
                                        {{ $shippingEstimation['label'] }}
                                    @endif
                                </dd>
                            </div>

                            @if(isset($offerDiscount) && $offerDiscount > 0)
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Offers Discount') }}</dt>
                                <dd class="font-medium text-green-600" id="offer-discount">-{{ number_format($offerDiscount, 2) }} {{ $settings->currency ?? 'SAR' }}</dd>
                            </div>
                            @endif

                            @if(isset($couponDiscount) && $couponDiscount > 0)
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Coupon Discount') }}</dt>
                                <dd class="font-medium text-green-600" id="coupon-discount">-{{ number_format($couponDiscount, 2) }} {{ $settings->currency ?? 'SAR' }}</dd>
                            </div>
                            @endif
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-base font-medium text-gray-900">{{ __('Order Total') }}</dt>
                                <dd class="font-bold text-xl text-primary-dark" id="cart-total">{{ $totalWithShipping }} {{ $settings->currency ?? 'SAR' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-6">
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" id="apply-coupon-form">
                            @csrf
                            <label for="coupon_code" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Have a coupon?') }}</label>
                            <div class="flex gap-2">
                                <input type="text" name="code" id="coupon_code" value="{{ $couponCode ?? '' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border-gray-300 focus:ring-primary-dark focus:border-primary-dark sm:text-sm border" placeholder="{{ __('Enter code') }}">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    {{ __('Apply') }}
                                </button>
                            </div>
                        </form>

                        @if(isset($couponError))
                            <p class="mt-1 text-sm text-red-600">{{ $couponError }}</p>
                        @endif

                        @if($couponCode && !isset($couponError))
                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md flex items-center justify-between mb-4">
                                <div class="flex items-center text-green-700 text-sm">
                                    <svg class="h-4 w-4 mr-2 rtl:mr-0 rtl:ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ __('Coupon applied:') }} <span class="font-bold uppercase">{{ $couponCode }}</span></span>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors" title="{{ __('Remove Coupon') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                        <a href="{{ route('checkout.index') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-dark hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark">
                            {{ __('Proceed to Checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-lg shadow-sm">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('Your cart is empty') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('Start adding some books and supplies.') }}</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange">
                    <svg class="-ml-1 mr-2 rtl:-mr-1 rtl:ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Continue Shopping') }}
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@include('cart.scripts')
@endsection