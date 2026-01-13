@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-primary-dark mb-6">{{ __('All Products') }}</h1>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar / Filter -->
            <div class="w-full md:w-1/4">
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <h3 class="font-bold text-lg mb-4 text-primary-dark">{{ __('Filter By') }}</h3>

                    <div class="mb-6">
                        <h4 class="font-medium mb-2">{{ __('Categories') }}</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="{{ route('products.index') }}" class="hover:text-secondary-orange {{ !request('category') && !request('type') ? 'text-secondary-orange font-bold' : '' }}">{{ __('All') }}</a></li>
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                    class="hover:text-secondary-orange {{ request('category') == $category->slug ? 'text-secondary-orange font-bold' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-medium mb-2">{{ __('Type') }}</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="{{ route('products.index', ['type' => 'book']) }}" class="hover:text-secondary-orange {{ request('type') == 'book' ? 'text-secondary-orange font-bold' : '' }}">{{ __('Books') }}</a></li>
                            <li><a href="{{ route('products.index', ['type' => 'supply']) }}" class="hover:text-secondary-orange {{ request('type') == 'supply' ? 'text-secondary-orange font-bold' : '' }}">{{ __('School Supplies') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="w-full md:w-3/4">
                @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
                @else
                <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500">{{ __('No products found.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection