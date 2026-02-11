@extends('layouts.auth')

@section('content')
<div class="max-w-md w-full space-y-8">
    <div>
        <h1 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            {{ __('Admin Login') }}
        </h1>
    </div>
    <form class="mt-8 space-y-6" action="{{ route('admin.login.store') }}" method="POST">
        @csrf
        <div class="rounded-md shadow-sm -space-y-px">
            <div>
                <label for="email-address" class="sr-only">{{ __('Email address') }}</label>
                <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange focus:z-10 sm:text-sm" placeholder="{{ __('Email address') }}">
            </div>
            <div>
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange focus:z-10 sm:text-sm" placeholder="{{ __('Password') }}">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-secondary-orange focus:ring-secondary-orange border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                    {{ __('Remember me') }}
                </label>
            </div>
        </div>

        <div>
            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary-orange hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-orange-500 group-hover:text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                {{ __('Sign in') }}
            </button>
        </div>
    </form>
</div>
@endsection