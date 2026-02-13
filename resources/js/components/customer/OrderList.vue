<template>
    <CustomerLayout :user="user">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $t('My Orders') }}</h1>
            <div class="bg-gray-100 text-gray-500 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                {{ $t('Total') }}: {{ orders.total }}
            </div>
        </div>

        <div v-if="orders.data && orders.data.length > 0" class="space-y-4">
            <div v-for="order in orders.data" :key="order.id" 
                class="bg-white rounded-3xl border border-gray-100 p-6 hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            <span class="text-xl font-black text-gray-900 tracking-tighter">{{ $t('Order #') }}{{ order.id }}</span>
                            <span :class="getStatusClass(order.status)" class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                                {{ $t(order.status) }}
                            </span>
                        </div>
                        <div class="flex items-center text-xs text-gray-400 font-bold gap-4 uppercase tracking-wider">
                            <span>📅 {{ formatDate(order.created_at) }}</span>
                            <span class="w-1 h-1 bg-gray-200 rounded-full"></span>
                            <span>📦 {{ order.items_count || 0 }} {{ $t('items') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-8 justify-between md:justify-end">
                        <div class="text-right rtl:text-left">
                            <div class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">{{ $t('Total Amount') }}</div>
                            <div class="text-xl font-black text-secondary-orange tracking-tighter">
                                {{ formatPrice(order.total_price) }}
                            </div>
                        </div>
                        <a :href="`/orders/${order.id}`" 
                            class="inline-flex items-center px-6 py-3 bg-gray-50 text-gray-700 font-black rounded-2xl hover:bg-secondary-orange hover:text-white transition-all duration-300 border border-gray-100 group-hover:border-transparent group-hover:shadow-xl group-hover:shadow-orange-100 text-xs uppercase tracking-widest">
                            {{ $t('View') }}
                            <svg class="w-4 h-4 ml-2 rtl:ml-0 rtl:mr-2 transform transition-transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="orders.last_page > 1" class="mt-12 flex justify-center items-center gap-3">
                <button v-for="link in orders.links" :key="link.label"
                    @click="handlePageChange(link.url)"
                    :disabled="!link.url || link.active"
                    v-html="link.label"
                    :class="[
                        'px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300',
                        link.active ? 'bg-secondary-orange text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-100 hover:text-secondary-orange hover:border-secondary-orange',
                        !link.url ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer transform active:scale-95'
                    ]">
                </button>
            </div>
        </div>

        <div v-else class="text-center py-24 bg-white/50 backdrop-blur-sm rounded-3xl border border-dashed border-gray-200">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8">
                <span class="text-5xl">📦</span>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $t('No orders yet') }}</h3>
            <p class="text-gray-400 font-medium mb-10">{{ $t('Your purchase history will appear here.') }}</p>
            <a href="/" class="inline-flex items-center px-10 py-5 bg-secondary-orange text-white font-black rounded-2xl hover:bg-orange-600 transition-all duration-300 shadow-2xl shadow-orange-200 transform hover:scale-105 active:scale-95">
                {{ $t('Shop Now') }}
            </a>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import CustomerLayout from './CustomerLayout.vue';

const props = defineProps(['initialOrders', 'currency', 'user']);
const orders = ref(props.initialOrders);

const getStatusClass = (status) => {
    const classes = {
        completed: 'bg-green-100 text-green-700',
        cancelled: 'bg-red-100 text-red-700',
        processing: 'bg-blue-100 text-blue-700',
        shipped: 'bg-indigo-100 text-indigo-700',
        pending: 'bg-yellow-100 text-yellow-700'
    };
    return classes[status] || 'bg-gray-100 text-gray-500';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString(document.documentElement.lang === 'ar' ? 'ar-SA' : 'en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const formatPrice = (price) => {
    return `${Number(price).toLocaleString()} ${props.currency || 'SAR'}`;
};

const handlePageChange = async (url) => {
    if (!url) return;
    try {
        const response = await axios.get(url);
        orders.value = response.data;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (error) {
        console.error('Failed to fetch orders:', error);
    }
};
</script>
