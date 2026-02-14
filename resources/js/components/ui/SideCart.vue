<template>
    <div>
        <!-- Overlay -->
        <div v-if="state.isOpen" 
             @click="close"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] transition-opacity duration-300">
        </div>

        <!-- Sidebar -->
        <div :class="[
            'fixed top-0 bottom-0 w-full md:w-96 bg-white z-[70] shadow-2xl transition-transform duration-500 ease-in-out p-0 flex flex-col',
            $i18n.locale === 'ar' ? (state.isOpen ? 'left-0' : '-left-full') : (state.isOpen ? 'right-0' : '-right-full')
        ]" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
            
            <!-- Header -->
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    {{ $t('Shopping Cart') }}
                </h2>
                <button @click="close" class="text-gray-500 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <div v-if="state.items.length === 0" class="flex flex-col items-center justify-center h-full text-gray-400 gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-lg">{{ $t('Your cart is empty') }}</p>
                </div>

                <div v-else v-for="item in state.items" :key="item.id" class="flex gap-4 bg-white p-3 rounded-lg border hover:border-primary-light transition-all shadow-sm">
                    <img :src="item.image.startsWith('http') || item.image.startsWith('/storage') || item.image.startsWith('storage') ? (item.image.startsWith('http') || item.image.startsWith('/') ? item.image : '/' + item.image) : '/storage/' + item.image" class="w-16 h-16 object-cover rounded-md border">
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800">{{ item.name }}</h3>
                        <p class="text-xs text-gray-500">{{ item.type }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm font-bold text-primary-dark">
                                {{ item.price }} {{ state.currency }}
                            </span>
                            <span class="text-xs text-gray-400">× {{ item.quantity }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div v-if="state.items.length > 0" class="p-6 border-t bg-gray-50 space-y-4 shadow-inner">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>{{ $t('Subtotal') }}</span>
                    <span class="text-primary-dark">{{ state.subtotal }} {{ state.currency }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="close" class="text-center py-3 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                        {{ $t('Continue Shopping') }}
                    </button>
                    <a href="/checkout" class="text-center py-3 bg-primary-dark text-white rounded-lg text-sm font-bold hover:bg-black transition-colors shadow-md">
                        {{ $t('Checkout') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useCart } from '../../stores/cart';
import { onMounted } from 'vue';

const { state, close, refresh } = useCart();

onMounted(() => {
    refresh();
});
</script>
