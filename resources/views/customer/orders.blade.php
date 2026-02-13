@extends('layouts.app')

@section('content')
<div id="app">
    <order-list-component 
        :initial-orders="{{ json_encode($orders) }}"
        currency="{{ $settings->currency ?? 'SAR' }}"
        :user="{{ json_encode(Auth::user()) }}"
    ></order-list-component>
</div>
@endsection
 