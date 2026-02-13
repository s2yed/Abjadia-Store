@extends('layouts.app')

@section('content')
<div id="app">
    <profile-component :initial-user="{{ json_encode([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'status' => $user->status,
    ]) }}"></profile-component>
</div>
@endsection