@props(['product'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group h-full flex flex-col">
    <a href="{{ route('products.show', $product->slug) }}" class="block h-64 bg-gray-100 w-full flex items-center justify-center overflow-hidden relative">
        @if($product->image)
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
        <span class="text-gray-400">{{ __('No Image') }}</span>
        @endif

        <!-- Favorite Button - Top Left -->
        <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="ajax-wishlist-form absolute top-2 left-2 z-10">
            @csrf
            <button type="submit" class="p-2 bg-white/90 backdrop-blur-sm rounded-full hover:bg-white shadow-md transition-all {{ Auth::check() && Auth::user()->favorites->contains('product_id', $product->id) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}" title="{{ __('Add to Favorites') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="{{ Auth::check() && Auth::user()->favorites->contains('product_id', $product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </form>

        <!-- Discount Badge - Top Right -->
        @if($product->discount_price)
        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
        </div>
        @endif
    </a>

    <div class="p-5 flex flex-col flex-grow">
        <a href="{{ route('products.show', $product->slug) }}" class="block">
            <h3 class="text-lg font-bold text-gray-900 truncate mb-1 hover:text-primary-600 transition-colors">{{ $product->name }}</h3>
        </a>

        <p class="text-sm text-gray-500 mb-3 flex-grow line-clamp-2">
            {{ $product->description ? Str::limit($product->description, 60) : ($product->author_name ?? __('Premium Quality')) }}
        </p>

        <div class="flex items-end justify-between mt-auto">
            <div class="flex flex-col">
                @if($product->discount_price)
                <span class="text-sm text-gray-400 line-through">{{ $product->price }} {{ settings('currency', 'SAR') }}</span>
                <span class="text-primary-600 font-bold text-lg">{{ $product->discount_price }} {{ settings('currency', 'SAR') }}</span>
                @else
                <span class="text-primary-600 font-bold text-lg">{{ $product->price }} {{ settings('currency', 'SAR') }}</span>
                @endif
            </div>

            <!-- Add to Cart Button -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="ajax-cart-form">
                @csrf
                <button type="submit" class="p-2 bg-gray-50 rounded-full hover:bg-primary-50 text-gray-600 hover:text-primary-600 transition-colors" title="{{ __('Add to Cart') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>