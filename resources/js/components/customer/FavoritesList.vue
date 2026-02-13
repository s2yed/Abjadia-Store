<template>
    <CustomerLayout :user="user">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $t('Your Favorites') }}</h1>
                <p class="text-xs text-gray-400 font-medium mt-1">{{ $t('The selection of products you love.') }}</p>
            </div>
            <div v-if="favorites.total > 0" class="bg-orange-50 text-secondary-orange px-4 py-1.5 rounded-full font-black text-[9px] uppercase tracking-widest shadow-sm">
                {{ favorites.total }} {{ $t('Items') }}
            </div>
        </div>

        <div v-if="favorites.data && favorites.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <ProductCard 
                v-for="favorite in favorites.data" 
                :key="favorite.id"
                :product="favorite.product"
                :is-favorite="true"
                :currency="settings?.currency || 'SAR'"
                @toggle-favorite="removeFavorite"
                @add-to-cart="addToCart"
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-24 bg-white/50 backdrop-blur-sm rounded-3xl border border-dashed border-gray-200">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8">
                <span class="text-5xl">❤️</span>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $t('Your wishlist is empty') }}</h3>
            <p class="text-gray-400 font-medium mb-10">{{ $t('Start adding products you love to your wishlist to keep track of them.') }}</p>
            <a href="/" class="inline-flex items-center px-10 py-5 bg-secondary-orange text-white font-black rounded-2xl hover:bg-orange-600 transition-all duration-300 shadow-2xl shadow-orange-200 transform hover:scale-105 active:scale-95">
                {{ $t('Explore Store') }}
            </a>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import CustomerLayout from './CustomerLayout.vue';
import ProductCard from '../ui/ProductCard.vue';
import { useI18n } from 'vue-i18n';
import { useNotification } from '../../stores/notification';
import { useCart } from '../../stores/cart';

const props = defineProps(['initialFavorites', 'user', 'settings']);
const favorites = ref(props.initialFavorites);
const { t } = useI18n();
const notification = useNotification();
const { increment, open, refresh } = useCart();

const removeFavorite = async (productId) => {
    try {
        await axios.post(`/favorites/${productId}`);
        favorites.value.data = favorites.value.data.filter(f => f.product.id !== productId);
        favorites.value.total--;
        notification.success(t('Removed from favorites'));
    } catch (error) {
        console.error('Failed to remove from favorites:', error);
        notification.error(t('Failed to remove from favorites'));
    }
};

const addToCart = async (productId) => {
    try {
        await axios.post('/cart', { product_id: productId });
        increment();
        await refresh();
        open();
        notification.success(t('Added to cart successfully'));
    } catch (error) {
        console.error('Failed to add to cart:', error);
        notification.error(t('Failed to add to cart'));
    }
};
</script>
