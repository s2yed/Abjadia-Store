@extends('layouts.app')

@section('content')
<div id="app">
    <favorites-list-component 
        :initial-favorites="{{ json_encode($favorites) }}"
        :settings="{{ json_encode($settings) }}"
        :user="{{ json_encode(Auth::user()) }}"
    ></favorites-list-component>
</div>
@endsection