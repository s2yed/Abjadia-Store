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
                            <!-- Cash on Delivery Option -->
                            <div class="relative">
                                <input class="peer sr-only" id="payment_cod" type="radio" name="payment_method" value="COD" checked>
                                <label class="flex cursor-pointer rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:border-secondary-orange peer-checked:border-secondary-orange peer-checked:ring-1 peer-checked:ring-secondary-orange transition-all" for="payment_cod">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <div class="ml-4 rtl:ml-0 rtl:mr-4">
                                                <p class="font-medium text-gray-900">{{ __('Cash on Delivery (COD)') }}</p>
                                                <p class="text-xs text-gray-500">{{ __('Pay when you receive your order') }}</p>
                                            </div>
                                        </div>
                                        <div class="h-5 w-5 rounded-full border border-gray-300 flex items-center justify-center peer-checked:border-secondary-orange peer-checked:bg-secondary-orange">
                                            <div class="h-2 w-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </div>
                                    </div>
                                </label>
                                <!-- Custom Check Circle (using CSS via peer-checked on input) -->
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full border border-gray-300 peer-checked:border-secondary-orange peer-checked:bg-secondary-orange hidden"></div>
                            </div>

                            <!-- Credit Card Option -->
                            <div class="relative">
                                <input class="peer sr-only" id="payment_card" type="radio" name="payment_method" value="Card">
                                <label class="flex cursor-pointer rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:border-secondary-orange peer-checked:border-secondary-orange peer-checked:ring-1 peer-checked:ring-secondary-orange transition-all" for="payment_card">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            <div class="ml-4 rtl:ml-0 rtl:mr-4">
                                                <p class="font-medium text-gray-900">{{ __('Credit Card / Mada / Apple Pay') }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                     <!-- Visa -->
                                                     <svg class="h-6 w-auto" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.554 30.56H21.57L23.454 12.333H20.529C19.7 12.333 18.91 12.82 18.599 13.62L15.939 27.53L15.422 25.02L18.554 30.56ZM30.939 12.333H28.47C28.2 12.333 28.02 12.52 27.91 12.78L23.869 30.56H26.91L27.52 27.27H30.86L31.17 30.56H34.02L30.939 12.333ZM28.28 24.89L29.6 17.75L30.33 24.89H28.28ZM39.929 12.333L37.199 30.56H40.099L42.829 12.333H39.929ZM13.88 12.333L10.02 30.56H12.91L15.17 19.33L17.26 30.56H20.37L13.88 12.333ZM6.96 12.333L6.75 12.44C6.549 12.492 6.368 12.553 6.18 12.63C4.16 13.75 1.7 16.71 1.7 16.71L2.1 18.42C2.1 18.42 4.09 16.32 6.64 15.14C7.03 14.96 7.21 15.02 7.35 15.65L7.79 17.8L3.25 30.56H6.38L8.63 19.86L10.97 30.56H14.1L6.96 12.333Z" fill="#1434CB"/></svg>
                                                     
                                                     <!-- Mastercard -->
                                                     <svg class="h-6 w-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_1_2)"><path d="M15.244 12C15.244 14.773 13.91 17.246 11.854 18.823C13.067 19.571 14.498 20 16.035 20C20.438 20 24 16.418 24 12C24 7.582 20.438 4 16.035 4C14.498 4 13.067 4.429 11.854 5.177C13.91 6.754 15.244 9.227 15.244 12Z" fill="#FF5F00"/><path d="M7.965 4C3.562 4 0 7.582 0 12C0 16.418 3.562 20 7.965 20C9.502 20 10.933 19.571 12.146 18.823C10.09 17.246 8.756 14.773 8.756 12C8.756 9.227 10.09 6.754 12.146 5.177C10.933 4.429 9.502 4 7.965 4Z" fill="#EB001B"/><path d="M15.244 12C15.244 14.773 13.91 17.246 11.854 18.823C10.641 17.892 9.71 16.641 9.176 15.207C8.948 14.595 8.815 13.844 8.784 13.064 C8.766 12.712 8.756 12.358 8.756 12 C8.756 9.227 10.09 6.754 12.146 5.177C13.359 6.108 14.29 7.359 14.824 8.793C15.052 9.405 15.185 10.156 15.216 10.936C15.234 11.288 15.244 11.642 15.244 12Z" fill="#F79E1B"/></g><defs><clipPath id="clip0_1_2"><rect width="24" height="24" fill="white"/></clipPath></defs></svg>
                                                     
                                                     <!-- Mada -->
                                                     <svg class="h-6 w-auto" viewBox="0 0 50 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.05 15.05H8.69V11.23H6.36V15.05H0V0.949997H6.36V8.56H8.69V0.949997H15.05V15.05ZM18.23 15.05H24.59V12.92H22.42C21.93 12.92 21.57 12.56 21.57 12.07V11.08C21.57 10.59 21.93 10.23 22.42 10.23H24.59V8.04999H18.23V15.05ZM24.59 5.92V0.949997H18.23V4.99H20.35C20.89 4.99 21.28 4.6 21.28 4.06V2.94C21.28 2.45 20.92 2.09 20.43 2.09H14.12V7H24.59V5.92ZM34.13 0.949997H27.77V15.05H34.13V0.949997ZM37.31 15.05H43.67V12.92H41.5C41.01 12.92 40.65 12.56 40.65 12.07V11.08C40.65 10.59 41.01 10.23 41.5 10.23H43.67V8.04999H37.31V15.05ZM43.67 5.92V0.949997H37.31V4.99H39.43C39.97 4.99 40.36 4.6 40.36 4.06V2.94C40.36 2.45 40 2.09 39.51 2.09H33.2V7H43.67V5.92ZM46.85 15.05H49.98V12.87H46.85V15.05ZM46.85 10.75H49.98V0.949997H46.85V10.75Z" fill="#005C97"/></svg>
                                                     
                                                     <!-- Apple Pay -->
                                                     <svg class="h-6 w-auto" viewBox="0 0 38 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.56 6.84C5.59 8.65 7.15 9.7 7.19 9.72C7.18 9.76 6.94 10.55 6.36 11.39C5.87 12.11 5.35 12.82 4.49 12.83C3.65 12.85 3.37 12.33 2.39 12.33C1.4 12.33 1.11 12.83 0.29 12.85C-0.58 12.86 -1.08 12.16 -1.55 11.47C-2.52 10.08 -3.27 7.54 -2.31 5.88C-1.83 5.06 -0.98 4.54 -0.09 4.52C0.75 4.51 1.54 5.09 2.02 5.09C2.49 5.09 3.19 4.45 4.22 4.49C4.65 4.51 5.83 4.66 6.64 5.84C6.56 5.89 5.54 6.13 5.56 6.84ZM4.11 3C4.55 2.44 4.86 1.67 4.77 0.9C4.08 0.93 3.25 1.37 2.76 1.93C2.31 2.44 1.93 3.23 2.04 3.99C2.8 4.05 3.65 3.56 4.11 3Z" fill="black" transform="translate(3.27 1.1)"/><path d="M12.95 2.22H11.02V12.5H9.37V2.22H7.44V0.83H12.95V2.22ZM17.16 3.98C17.51 3.55 18 3.34 18.59 3.34C19.25 3.34 19.78 3.59 20.17 4.09C20.57 4.59 20.76 5.3 20.76 6.24V12.5H19.21V10.82C18.84 11.23 18.42 11.53 17.96 11.75C17.49 11.96 17 12.06 16.48 12.06C15.75 12.06 15.16 11.83 14.69 11.38C14.23 10.92 14 10.33 14 9.61C14 8.91 14.24 8.36 14.73 7.94C15.22 7.52 15.91 7.31 16.8 7.31H19.16V6.66C19.16 6.14 19.03 5.73 18.77 5.43C18.52 5.13 18.17 4.98 17.73 4.98C16.84 4.98 16.27 5.37 16.03 6.15H14.51C14.61 5.33 15.01 4.68 15.7 4.22C16.08 3.97 16.57 3.84 17.16 3.98ZM19.16 9.47V8.52H17C16.48 8.52 16.11 8.62 15.89 8.81C15.68 9 15.57 9.25 15.57 9.57C15.57 9.9 15.68 10.16 15.89 10.36C16.11 10.55 16.42 10.65 16.81 10.65C17.3 10.65 17.72 10.53 18.12 10.28C18.51 10.04 18.78 9.8 19 9.51C19.11 9.49 19.16 9.48 19.16 9.47ZM25.86 3.34V4.76L23.49 10.3C23.27 10.81 23.01 11.24 22.7 11.6C22.4 11.96 22 12.23 21.5 12.41C21.18 12.53 20.84 12.57 20.48 12.55V11.16C20.67 11.17 20.85 11.15 21 11.11C21.2 11.04 21.36 10.92 21.49 10.74C21.62 10.56 21.75 10.3 21.88 9.93L21.95 9.74L19.49 3.34H21.19L22.84 8.01L24.57 3.34H25.86ZM29.6 12.5H28.05V0.83H29.6V12.5ZM35.35 8.16H31.54C31.57 9.09 31.86 9.77 32.42 10.21C32.97 10.64 33.64 10.86 34.42 10.86C35.09 10.86 35.61 10.7 35.97 10.4C36.33 10.09 36.56 9.69 36.65 9.17H38.07C37.93 10.05 37.54 10.76 36.91 11.31C36.27 11.85 35.45 12.12 34.44 12.12C33.27 12.12 32.32 11.75 31.59 11.01C30.87 10.27 30.51 9.25 30.51 7.96C30.51 6.64 30.87 5.6 31.6 4.85C32.32 4.09 33.25 3.71 34.39 3.71C35.53 3.71 36.42 4.09 37.11 4.85C37.8 5.61 38.15 6.66 38.15 8L38.14 8.16H35.35ZM34.42 4.97C33.78 4.97 33.22 5.17 32.74 5.56C32.27 5.95 31.96 6.49 31.81 7.18H36.71C36.62 6.51 36.35 5.98 35.91 5.58C35.47 5.17 34.97 4.97 34.42 4.97Z" fill="black"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Checkmark indicator -->
                                        <svg class="h-5 w-5 text-secondary-orange opacity-0 peer-checked:opacity-100 transition-opacity" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
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