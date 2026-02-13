@extends('layouts.app')

@section('content')
<div id="app">
    <order-detail-component 
        :order="{{ json_encode($order) }}"
        :settings="{{ json_encode($settings) }}"
        :user="{{ json_encode(Auth::user()) }}"
    ></order-detail-component>
</div>
@endsection