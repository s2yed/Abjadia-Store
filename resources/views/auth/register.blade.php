@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h1 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                {{ __('Create your account') }}
            </h1>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('register.store') }}" method="POST">
            @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="mx-3">
                        <h3 class="text-sm font-medium text-red-800">
                            {{ __('There were errors with your submission') }}
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pr-5 rtl:pr-0 rtl:pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="name" class="sr-only">{{ __('Full Name') }}</label>
                    <input id="name" name="name" type="text" autocomplete="name" required class="appearance-none rounded-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange focus:z-10 sm:text-sm" placeholder="{{ __('Full Name') }}">
                </div>
                <div>
                    <label for="email-address" class="sr-only">{{ __('Email address') }}</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange focus:z-10 sm:text-sm" placeholder="{{ __('Email address') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange focus:z-10 sm:text-sm" placeholder="{{ __('Password') }}">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="appearance-none rounded-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange focus:z-10 sm:text-sm" placeholder="{{ __('Confirm Password') }}">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary-orange hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange">
                    {{ __('Register') }}
                </button>
            </div>

            <div class="text-center text-sm">
                <a href="{{ route('login') }}" class="font-medium text-secondary-orange hover:text-orange-500">
                    {{ __('Already have an account?') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection