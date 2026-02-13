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
{{ $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('images/logo.png') }}
@endsection

@section('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('images/logo.png') }}",
  "description": "{{ $product->seo_description ?: Str::limit($product->description, 250) }}",
  "sku": "{{ $product->isbn ?: $product->id }}",
  "offers": {
    "@@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "{{ $settings->currency ?? 'SAR' }}",
    "price": "{{ $product->discount_price ?: $product->price }}",
    "availability": "https://schema.org/{{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}"
  }
}
</script>
@endsection

@section('content')
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Custom breakpoint at 1022px to trigger split layout --}}
        <div class="flex flex-col min-[1022px]:flex-row-reverse min-[1022px]:gap-x-12 items-start">
            
            <!-- Image Gallery (Right side on >= 1022px in LTR/RTL depends on DOM) -->
            {{-- We use flex-row-reverse on 1022px to put the FIRST child (Image) on the RIGHT even in LTR if requested. In RTL, it will be naturally Right if row-reverse is handled. Actually, in RTL, flex-row-reverse puts 1st child on the LEFT. --}}
            {{-- User wants Image RIGHT and Details LEFT at 1022px. --}}
            {{-- In RTL: flex-row puts 1st child on RIGHT. --}}
            {{-- Let's use simple order classes for better control. --}}
            
            <div class="w-full min-[1022px]:w-1/3 min-[1022px]:order-2">
                <div class="sticky top-24">
                    <div class="w-full max-w-md mx-auto aspect-[4/5] bg-gray-50 rounded-3xl overflow-hidden shadow-inner border border-gray-100">
                        @if($product->image)
                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center transition-transform duration-700 hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <span class="text-xs font-black uppercase tracking-widest">{{ __('no_image') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info (Left side on >= 1022px) -->
            <div class="w-full min-[1022px]:w-2/3 mt-10 min-[1022px]:mt-0 min-[1022px]:order-1">
                <nav class="mb-6">
                    <ol class="flex items-center space-x-2 rtl:space-x-reverse text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <li><a href="/" class="hover:text-secondary-orange transition-colors">{{ __('home') }}</a></li>
                        <li><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></li>
                        @if($product->category)
                        <li><a href="/products?category={{ $product->category->id }}" class="hover:text-secondary-orange transition-colors">{{ $product->category->name }}</a></li>
                        <li><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></li>
                        @endif
                    </ol>
                </nav>

                <h1 class="text-3xl md:text-5xl font-black tracking-tight text-gray-900 leading-tight mb-4">{{ $product->name }}</h1>

                <div class="mt-6">
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl font-black text-secondary-orange tracking-tighter">
                            {{ $product->discount_price ?: $product->price }}
                            <span class="text-lg uppercase ml-1">{{ __($settings->currency ?? 'SAR') }}</span>
                        </span>
                        @if($product->discount_price)
                            <span class="text-xl text-gray-300 line-through font-bold">
                                {{ $product->price }} {{ __($settings->currency ?? 'SAR') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">{{ __('description') }}</h3>
                    <div class="text-base text-gray-600 leading-relaxed max-w-none">
                        {{ $product->description }}
                    </div>
                </div>

                <div class="mt-10 border-t border-gray-100 pt-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">{{ __('specifications') }}</h3>
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <!-- Author -->
                        @if($product->authors->count() > 0)
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                            <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('author') }}</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $product->authors->pluck('name')->join(', ') }}</dd>
                        </div>
                        @endif

                        <!-- Translator -->
                        @if($product->translators->count() > 0)
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                            <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('translator') }}</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $product->translators->pluck('name')->join(', ') }}</dd>
                        </div>
                        @endif

                        <!-- Publisher -->
                        @if($product->publisher || $product->publisher_name)
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                            <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('publisher') }}</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $product->publisher ? $product->publisher->name : $product->publisher_name }}</dd>
                        </div>
                        @endif

                        <!-- ISBN -->
                        @if($product->isbn)
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                            <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('isbn') }}</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $product->isbn }}</dd>
                        </div>
                        @endif

                        <!-- Pages -->
                        @if($product->pages)
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                            <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('pages') }}</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $product->pages }}</dd>
                        </div>
                        @endif

                        <!-- Publication Year -->
                        @if($product->publication_year)
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                            <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('publication_year') }}</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $product->publication_year }}</dd>
                        </div>
                        @endif

                        <!-- Brand (for supplies) -->
                        @if($product->type == 'supply' && $product->brand)
                        <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                            <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('brand') }}</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $product->brand->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                @if($product->type == 'supply' && $product->variants->count() > 0)
                <div class="mt-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">{{ __('options') }}</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($product->variants as $variant)
                        <button class="border-2 border-gray-100 rounded-2xl py-3 px-6 text-sm font-black hover:border-secondary-orange hover:bg-orange-50/50 hover:text-secondary-orange transition-all active:scale-95">
                            {{ $variant->attribute_value }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-12 flex items-center gap-4">
                    <form action="{{ route('cart.store') }}" method="POST" class="ajax-cart-form flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full bg-secondary-orange text-white rounded-2xl py-5 px-8 flex items-center justify-center text-base font-black uppercase tracking-widest hover:bg-orange-600 transition-all duration-300 shadow-2xl shadow-orange-100 active:scale-95">
                            {{ __('add_to_cart') }}
                        </button>
                    </form>

                    <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="ajax-wishlist-form">
                        @csrf
                        <button type="submit" class="bg-white border-2 border-gray-100 rounded-2xl py-5 px-5 flex items-center justify-center {{ Auth::check() && Auth::user()->favorites->contains('product_id', $product->id) ? 'text-red-500' : 'text-gray-400' }} hover:bg-gray-50 hover:text-red-500 transition-all duration-300 shadow-sm active:scale-95" title="{{ __('add_to_favorites') }}">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="{{ Auth::check() && Auth::user()->favorites->contains('product_id', $product->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <div class="mt-24 border-t border-gray-100 pt-16">
            <h2 class="text-3xl font-black text-gray-900 mb-10 tracking-tight">{{ __('related_products') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
