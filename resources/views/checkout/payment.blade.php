@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 text-center">{{ __('Complete Your Payment') }}</h2>
            </div>
            
            <div class="p-8">
                <div class="mb-8 text-center">
                    <p class="text-gray-600 mb-2">{{ __('Order Total') }}</p>
                    <p class="text-4xl font-bold text-primary-dark">{{ $order->total_price }} {{ __('SAR') }}</p>
                    <p class="text-sm text-gray-500 mt-2">{{ __('Order #') }}{{ $order->id }}</p>
                </div>

                <!-- Moyasar Payment Form -->
                <div class="mysr-form-wrapper max-w-md mx-auto"></div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('customer.orders.show', $order->id) }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                        {{ __('Cancel and Return to Order') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.14.0/moyasar.css">
@endpush

@push('scripts')
<script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
<script src="https://cdn.moyasar.com/mpf/1.14.0/moyasar.js"></script>
<script>
    Moyasar.init({
        element: '.mysr-form-wrapper',
        amount: {{ $order->total_price * 100 }}, // Amount in Halalas
        currency: 'SAR',
        description: 'Order #{{ $order->id }}',
        publishable_api_key: '{{ config("services.moyasar.key") }}',
        callback_url: '{{ route("moyasar.callback") }}',
        metadata: {
            'order_id': '{{ $order->id }}'
        },
        methods: ['creditcard', 'applepay', 'stcpay'],
        on_completed: function (payment) {
            // calculated_callback_url is handled by the library redirect usually, 
            // but we can also handle here if needed. 
            // The library will redirect to callback_url with query params.
        }
    });
</script>
@endpush
@endsection
