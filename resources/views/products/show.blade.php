@extends('layouts.app')

@section('title')
{{ $product->seo_title ?: $product->name . ' - ' . config('app.name') }}
@endsection

@section('meta_description')
{{ $product->seo_description ?: Str::limit($product->description, 160) }}
@endsection

@section('meta_keywords')
{{ $product->seo_keywords ?: ($product->category ? $product->category->name . ', abjadia' : 'abjadia, store') }}
@endsection

@section('og_image')
{{ $product->image ? asset($product->image) : asset('images/logo.png') }}
@endsection

@section('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ $product->image ? asset($product->image) : asset('images/logo.png') }}",
  "description": "{{ $product->seo_description ?: Str::limit($product->description, 250) }}",
  "sku": "{{ $product->isbn ?: $product->id }}",
  "offers": {
    "@@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "SAR",
    "price": "{{ $product->discount_price ?: $product->price }}",
    "availability": "https://schema.org/{{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}"
  }
}
</script>
@endsection

@section('content')
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
            <!-- Image Gallery -->
            <div class="flex flex-col-reverse">
                <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden sm:aspect-w-2 sm:aspect-h-3">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center">
                    @else
                        <div class="w-full h-96 flex items-center justify-center text-gray-400 text-2xl">{{ __('No Image') }}</div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <div class="mt-3">
                    <p class="text-3xl text-gray-900 font-bold text-secondary-orange">
                        @if($product->discount_price)
                            {{ $product->discount_price }} {{ __('SAR') }}
                            <span class="text-lg text-gray-400 line-through ml-2">{{ $product->price }} {{ __('SAR') }}</span>
                        @else
                            {{ $product->price }} {{ __('SAR') }}
                        @endif
                    </p>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">{{ __('Description') }}</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>

                @if($product->type == 'book')
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Author') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->authors->pluck('name')->join(', ') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('ISBN') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->isbn }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Publisher') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->publisher }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Pages') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->pages }}</dd>
                        </div>
                    </dl>
                </div>
                @endif

                @if($product->type == 'supply' && $product->variants->count() > 0)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">{{ __('Options') }}</h3>
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        @foreach($product->variants as $variant)
                        <button class="border rounded-md py-2 px-4 hover:border-secondary-orange focus:outline-none focus:ring-2 focus:ring-secondary-orange">
                            {{ $variant->attribute_value }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-8 flex gap-4">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-secondary-orange border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange">
                            {{ __('Add to Cart') }}
                        </button>
                    </form>
                    <button type="button" class="ml-4 bg-white border border-gray-300 rounded-md py-3 px-3 flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-red-500 transition-colors ajax-favorite-btn" data-product-id="{{ $product->id }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <div class="mt-16 border-t border-gray-200 pt-10">
            <h2 class="text-2xl font-bold text-primary-dark mb-6">{{ __('Related Products') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
