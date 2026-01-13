@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-primary-dark mb-8">{{ __('Shopping Cart') }}</h1>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

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
                                @foreach($cart as $id => $details)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}">
                                            </div>
                                            <div class="mx-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $details['name'] }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $details['type'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $details['price'] }} {{ __('SAR') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center border border-gray-300 rounded-md">
                                            <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="decrease" data-id="{{ $id }}">
                                                -
                                            </button>
                                            <input type="text" readonly class="w-12 text-center border-none focus:ring-0 text-sm text-gray-900 quantity-input-{{ $id }}" value="{{ $details['quantity'] }}">
                                            <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="increase" data-id="{{ $id }}">
                                                +
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" id="cart-item-total-{{ $id }}">{{ $details['price'] * $details['quantity'] }} {{ __('SAR') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Remove') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="block md:hidden divide-y divide-gray-200">
                        @foreach($cart as $id => $details)
                        <div class="p-4 flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img class="h-16 w-16 rounded-lg object-cover" src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}">
                                    </div>
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900">{{ $details['name'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $details['type'] }}</p>
                                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $details['price'] }} {{ __('SAR') }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center border border-gray-300 rounded-md">
                                    <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="decrease" data-id="{{ $id }}">
                                        -
                                    </button>
                                    <input type="text" readonly class="w-12 text-center border-none focus:ring-0 text-sm text-gray-900 quantity-input-{{ $id }}" value="{{ $details['quantity'] }}">
                                    <button type="button" class="px-3 py-2 text-gray-600 hover:bg-gray-100 quantity-btn" data-action="increase" data-id="{{ $id }}">
                                        +
                                    </button>
                                </div>
                                <div class="text-base font-bold text-primary-dark" id="cart-item-total-mobile-{{ $id }}">
                                    {{ $details['price'] * $details['quantity'] }} {{ __('SAR') }}
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
                    <div class="flow-root">
                        <dl class="-my-4 text-sm divide-y divide-gray-200">
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Subtotal') }}</dt>
                                <dd class="font-medium text-gray-900" id="cart-subtotal">{{ $total }} {{ __('SAR') }}</dd>
                            </div>
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">{{ __('Shipping') }}</dt>
                                <dd class="font-medium text-gray-900">{{ __('Calculated at checkout') }}</dd>
                            </div>
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-base font-medium text-gray-900">{{ __('Order Total') }}</dt>
                                <dd class="font-bold text-xl text-primary-dark" id="cart-total">{{ $total }} {{ __('SAR') }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-6">
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