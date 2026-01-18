@extends('emails.layout')

@section('content')
    <h2>{{ __('Order Status Update') }}</h2>
    
    <p>{{ __('Hi :name,', ['name' => $order->user->name ?? 'Customer']) }}</p>
    
    <p>{{ __('The status of your order #:id has been updated to:', ['id' => $order->id]) }}</p>
    
    <div style="background-color: #fce7f3; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0;">
        <strong style="color: #9d174d; font-size: 18px;">{{ ucfirst($order->status) }}</strong>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('customer.orders.show', $order->id) }}" class="button">{{ __('Track Order') }}</a>
    </div>
@endsection
