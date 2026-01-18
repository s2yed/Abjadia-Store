@extends('emails.layout')

@section('content')
    <h2>{{ __('Welcome, :name!', ['name' => $user->name]) }}</h2>
    
    <p>{{ __('Thank you for joining :app. We are excited to have you on board!', ['app' => config('app.name')]) }}</p>
    
    <p>{{ __('Feel free to browse our collection of books and supplies.') }}</p>
    
    <div style="text-align: center;">
        <a href="{{ route('home') }}" class="button">{{ __('Start Shopping') }}</a>
    </div>
@endsection
