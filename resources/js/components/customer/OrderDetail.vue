<template>
    <CustomerLayout :user="user">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <nav class="flex mb-4 text-[10px] text-gray-400 gap-2 items-center font-black uppercase tracking-widest">
                    <a href="/orders" class="hover:text-secondary-orange transition-colors">{{ $t('Order History') }}</a>
                    <svg class="w-3 h-3 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-gray-900">{{ $t('Order #') }}{{ order.id }}</span>
                </nav>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $t('Order Details') }}</h1>
            </div>
            <div class="flex items-center gap-4">
                <span :class="getStatusClass(order.status)" class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                    {{ $t(order.status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Items -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ $t('Order Items') }}</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <div v-for="item in order.items" :key="item.id" class="p-6 flex gap-6 hover:bg-gray-50 transition-all duration-300 group">
                            <div class="h-20 w-20 flex-shrink-0 bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm group-hover:shadow-lg transition-shadow duration-500">
                                <img v-if="getProductImage(item)" :src="getProductImage(item)" :alt="item.product_name" class="h-full w-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                                <div v-else class="h-full w-full bg-gray-50 flex items-center justify-center text-gray-300 text-[10px] font-black uppercase tracking-widest">
                                     {{ $t('No Image') }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-base font-black text-gray-900 truncate tracking-tight group-hover:text-secondary-orange transition-colors">{{ item.product_name }}</h4>
                                    <span class="text-base font-black text-gray-900 tracking-tighter">{{ formatPrice(item.price) }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                    <span class="bg-gray-100/80 px-2.5 py-0.5 rounded-full text-gray-500">{{ $t('Qty') }}: {{ item.quantity }}</span>
                                    <span v-if="item.product && item.product.category" class="w-1 h-1 bg-gray-200 rounded-full"></span>
                                    <span v-if="item.product && item.product.category" class="text-secondary-orange">{{ item.product.category.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping and Payment -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-5 flex items-center gap-3">
                             <div class="w-7 h-7 bg-orange-50 rounded-lg flex items-center justify-center text-secondary-orange text-xs">📍</div>
                             {{ $t('Shipping') }}
                        </h3>
                        <div class="text-gray-500 font-medium leading-relaxed bg-gray-50/50 p-5 rounded-2xl text-xs border border-gray-50">
                            <template v-if="order.shipping_address">
                                <div v-for="(line, idx) in order.shipping_address.split('|')" :key="idx">{{ line }}</div>
                            </template>
                            <span v-else class="italic text-gray-300">{{ $t('N/A') }}</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                        <h3 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-5 flex items-center gap-3">
                             <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 text-xs">💳</div>
                             {{ $t('Payment') }}
                        </h3>
                         <div class="bg-gray-50/50 p-5 rounded-2xl space-y-3.5 border border-gray-50">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $t('Status') }}</span>
                                <span class="font-black text-green-600 bg-green-50 px-2.5 py-0.5 rounded-full uppercase text-[9px]">{{ $t('Paid') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $t('Method') }}</span>
                                <span class="font-black text-gray-900 font-mono">Card</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="space-y-6">
                <div class="bg-secondary-orange text-white rounded-[40px] shadow-2xl shadow-orange-200 p-8 overflow-hidden relative">
                    <!-- Deco Pattern -->
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-orange-400/30 rounded-full blur-3xl"></div>

                    <h3 class="text-xs font-black mb-8 relative z-10 uppercase tracking-widest">{{ $t('Summary') }}</h3>
                    <div class="space-y-4 relative z-10 font-bold">
                        <div class="flex justify-between items-center opacity-70">
                            <span class="text-[9px] uppercase tracking-widest">{{ $t('Subtotal') }}</span>
                            <span class="tracking-tighter text-xs">{{ formatPrice(order.subtotal_after_discount || order.total_price) }}</span>
                        </div>
                        <div class="flex justify-between items-center opacity-70">
                            <span class="text-[9px] uppercase tracking-widest">{{ $t('Shipping') }}</span>
                            <span class="tracking-tighter text-xs">{{ order.shipping_cost > 0 ? formatPrice(order.shipping_cost) : $t('Free') }}</span>
                        </div>
                        <div class="h-px bg-white/10 my-4"></div>
                        <div class="flex justify-between items-end">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em]">{{ $t('Total') }}</span>
                            <div class="text-right">
                                <div class="text-2xl font-black leading-none tracking-tighter">{{ formatPrice(order.total_price) }}</div>
                                <div class="text-[8px] opacity-50 mt-2 font-black uppercase tracking-widest">{{ $t('Incl. VAT') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-5 group hover:shadow-xl transition-all duration-500">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-xl group-hover:scale-105 transition-transform duration-500">
                         📅
                    </div>
                    <div>
                        <div class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-0.5">{{ $t('Placed on') }}</div>
                        <div class="text-gray-900 font-black text-base tracking-tight">{{ formatDate(order.created_at) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref } from 'vue';
import CustomerLayout from './CustomerLayout.vue';

const props = defineProps(['order', 'settings', 'user']);

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
        month: 'long',
        day: 'numeric'
    });
};

const formatPrice = (price) => {
    return `${Number(price).toLocaleString()} ${props.settings?.currency || 'SAR'}`;
};

const getProductImage = (item) => {
    if (!item.product || !item.product.image) return null;
    const img = item.product.image;
    if (img.startsWith('http')) return img;
    if (img.startsWith('/storage/') || img.startsWith('storage/')) return img.startsWith('/') ? img : `/${img}`;
    if (img.startsWith('images/')) return `/${img}`;
    return `/storage/${img}`;
};
</script>
