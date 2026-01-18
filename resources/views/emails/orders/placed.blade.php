@extends('emails.layout')

@section('content')
    <h2>{{ __('Order Confirmed!') }}</h2>
    
    <p>{{ __('Hi :name,', ['name' => $order->user->name ?? $order->shipping_address]) }}</p>
    <p>{{ __('Your order #:id has been successfully placed.', ['id' => $order->id]) }}</p>
    
    <h3>{{ __('Order Summary') }}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Product') }}</th>
                <th>{{ __('Qty') }}</th>
                <th>{{ __('Price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }} {{ __('SAR') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">{{ __('Total') }}</td>
                <td style="font-weight: bold;">{{ $order->total_price }} {{ __('SAR') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="text-align: center;">
        <a href="{{ route('customer.orders.show', $order->id) }}" class="button">{{ __('View Order') }}</a>
    </div>
@endsection
