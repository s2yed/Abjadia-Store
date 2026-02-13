<template>
    <article class="bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 group flex flex-col h-full transform hover:-translate-y-1" itemscope itemtype="https://schema.org/Product">
        <!-- Photo -->
        <div class="aspect-[4/5] relative overflow-hidden bg-gray-50">
            <a :href="`/products/${product.slug || product.id}`" :aria-label="translate(product.name)" class="block w-full h-full">
                <img v-if="product.image" 
                    :src="getImageUrl(product.image)" 
                    :alt="translate(product.name)" 
                    itemprop="image"
                    loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $t('No Image') }}</span>
                </div>
            </a>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <!-- Favorite Button -->
            <button @click="$emit('toggle-favorite', product.id)" 
                class="absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center shadow-xl hover:bg-white transition-all duration-300 transform scale-0 group-hover:scale-100 z-10"
                :class="isFavorite ? 'text-red-500' : 'text-gray-400 hover:text-red-500'"
                :aria-label="isFavorite ? $t('Remove from wishlist') : $t('Add to wishlist')">
                <svg class="w-5 h-5" :fill="isFavorite ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>

            <!-- Category Badge -->
            <div v-if="product.category" class="absolute bottom-4 left-4 z-10">
                <span class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest text-secondary-orange shadow-lg">
                    {{ translate(product.category.name) }}
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
                <a :href="`/products/${product.slug || product.id}`" class="block group">
                    <h3 class="text-sm font-black text-gray-900 mb-1.5 truncate group-hover:text-secondary-orange transition-colors" itemprop="name">
                        {{ translate(product.name) }}
                    </h3>
                </a>
                <p class="text-[11px] text-gray-400 font-medium line-clamp-2 mb-4 leading-relaxed" itemprop="description">
                    {{ translate(product.description) || authorText }}
                </p>
            </div>

            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <div class="flex flex-col">
                    <span v-if="product.discount_price" class="text-[10px] text-gray-300 line-through font-bold">{{ formatPrice(product.price) }}</span>
                    <span class="text-base font-black text-secondary-orange tracking-tighter">
                        <meta itemprop="price" :content="product.discount_price || product.price">
                        <meta itemprop="priceCurrency" :content="currency">
                        {{ formatPrice(product.discount_price || product.price) }}
                    </span>
                </div>
                
                <div class="flex gap-2">
                    <button @click="$emit('add-to-cart', product.id)" 
                        class="px-4 h-10 bg-secondary-orange text-white rounded-lg flex items-center justify-center hover:bg-orange-600 transition-all duration-300 shadow-md active:scale-95 gap-2"
                        :aria-label="$t('Add to Cart')">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest hidden sm:inline">{{ $t('Add') }}</span>
                    </button>
                    <a :href="`/products/${product.slug || product.id}`" 
                        class="w-10 h-10 bg-gray-50 text-gray-400 rounded-lg flex items-center justify-center hover:bg-gray-100 hover:text-gray-600 transition-all duration-300 active:scale-95 border border-gray-100"
                        :aria-label="$t('View Details')">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </article>
</template>

<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    product: { type: Object, required: true },
    isFavorite: { type: Boolean, default: false },
    currency: { type: String, default: 'SAR' }
});

const { t } = useI18n();

const translate = (field) => {
    if (!field) return '';
    if (typeof field === 'string') {
        try {
            const parsed = JSON.parse(field);
            const lang = document.documentElement.lang || 'en';
            return parsed[lang] || Object.values(parsed)[0] || field;
        } catch (e) {
            return field;
        }
    }
    if (typeof field === 'object') {
        const lang = document.documentElement.lang || 'en';
        return field[lang] || Object.values(field)[0] || '';
    }
    return field;
};

const authorText = computed(() => {
    return translate(props.product.author_name) || t('Premium Quality');
});

const getImageUrl = (image) => {
    if (!image) return '';
    if (image.startsWith('http')) return image;
    return `/storage/${image}`;
};

const formatPrice = (price) => {
    return `${Number(price).toLocaleString()} ${props.currency}`;
};
</script>
