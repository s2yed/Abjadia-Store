<template>
    <div class="space-y-6" v-if="order">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $t('order_id') }} #{{ order.id }}</h2>
                    <span :class="statusBadgeClass" class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide">
                        {{ $t(order.status) }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mt-1">{{ formatDate(order.created_at) }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <router-link
                    to="/dashboard/orders"
                    class="text-gray-500 hover:text-gray-700 font-medium text-sm transition-colors"
                >
                    &larr; {{ $t('back_to_list') }}
                </router-link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Customer & Address -->
            <div class="lg:col-span-2 space-y-6">
                 <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-800">{{ $t('order_items') }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 font-medium">
                                    <th class="px-6 py-3 text-left rtl:text-right">{{ $t('product') }}</th>
                                    <th class="px-6 py-3 text-center">{{ $t('quantity') }}</th>
                                    <th class="px-6 py-3 text-right rtl:text-left">{{ $t('price') }}</th>
                                    <th class="px-6 py-3 text-right rtl:text-left">{{ $t('total') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="item in order.items" :key="item.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="ml-0">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ item.product_name || (item.product ? (typeof item.product.name === 'object' ? item.product.name[$i18n.locale] || item.product.name.en : item.product.name) : $t('deleted_product')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">{{ item.quantity }}</td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-600">{{ formatPrice(item.price) }}</td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">{{ formatPrice(item.price * item.quantity) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Status Update -->
                 <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-4">{{ $t('update_status') }}</h3>
                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                        <select
                            v-model="order.status"
                            class="block w-full max-w-xs pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm rounded-md"
                        >
                            <option value="pending">{{ $t('pending') }}</option>
                            <option value="processing">{{ $t('processing') }}</option>
                            <option value="shipped">{{ $t('shipped') }}</option>
                            <option value="completed">{{ $t('completed') }}</option>
                            <option value="cancelled">{{ $t('cancelled') }}</option>
                        </select>
                        <button 
                            @click="updateStatus" 
                            class="bg-secondary-orange text-white px-4 py-2 rounded-md shadow hover:bg-orange-700 transition-colors text-sm font-medium"
                        >
                            {{ $t('update') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Info -->
            <div class="space-y-6">
                <!-- Order Summary -->
                 <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">{{ $t('summary') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>{{ $t('subtotal') }}</span>
                            <span>{{ formatPrice(order.total_price) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>{{ $t('shipping') }}</span>
                            <span>{{ $t('free') }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between items-center">
                            <span class="font-bold text-gray-900 text-base">{{ $t('total') }}</span>
                            <span class="font-bold text-secondary-orange text-xl">{{ formatPrice(order.total_price) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Information Cards -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="mr-2 rtl:mr-0 rtl:ml-2">👤</span> {{ $t('customer') }}
                    </h3>
                    <div class="text-sm space-y-2 text-gray-600">
                        <p><span class="font-medium text-gray-900">{{ $t('name') }}:</span> {{ getUserName() }}</p>
                        <p><span class="font-medium text-gray-900">{{ $t('email') }}:</span> {{ order.user ? order.user.email : "-" }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="mr-2 rtl:mr-0 rtl:ml-2">📍</span> {{ $t('shipping_address') }}
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ getAddress() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div v-else-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-secondary-orange"></div>
    </div>
    
    <!-- Error State -->
    <div v-else class="text-center py-12 bg-white rounded-lg shadow scale-95 mx-auto max-w-lg mt-10">
        <p class="text-red-500 text-lg">{{ $t('failed_load') }}</p>
        <button @click="fetchOrder" class="mt-4 text-secondary-orange hover:underline font-medium">{{ $t('try_again') }}</button>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute } from "vue-router";
import { ref, onMounted, computed } from "vue";
import { useI18n } from 'vue-i18n';

export default {
    setup() {
        const { t } = useI18n();
        const route = useRoute();
        const order = ref(null);
        const loading = ref(true);

        const fetchOrder = async () => {
            try {
                const response = await axios.get(
                    `/api/orders/${route.params.id}`
                );
                order.value = response.data;
            } catch (err) {
                console.error(err);
            } finally {
                loading.value = false;
            }
        };

        const updateStatus = async () => {
            try {
                await axios.put(`/api/orders/${order.value.id}`, {
                    status: order.value.status,
                });
                alert(t('order_status_updated'));
            } catch (err) {
                const msg = err.response?.data?.message || err.message || t('try_again');
                alert(msg);
                console.error(err);
            }
        };

        const formatPrice = (price) => {
            const locale = document.documentElement.lang === 'ar' ? 'ar-SA' : 'en-US';
            const currencyLabel = t('currency');
            return new Intl.NumberFormat(locale, { style: 'decimal', minimumFractionDigits: 2 }).format(price || 0) + ' ' + currencyLabel;
        };

        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const locale = document.documentElement.lang === 'ar' ? 'ar-SA' : 'en-US';
            return new Date(dateString).toLocaleDateString(locale, {
                year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
            });
        };

        const getUserName = () => {
            if (order.value.shipping_address) {
                const parts = order.value.shipping_address.split("|");
                return parts.length > 2 ? parts[2].trim() : (order.value.user ? order.value.user.name : t('guest'));
            }
            return order.value.user ? order.value.user.name : t('guest');
        };

        const getPhone = () => {
             if (order.value.shipping_address) {
                const parts = order.value.shipping_address.split("|");
                return parts.length > 1 ? parts[1].trim() : "-";
            }
            return "-";
        };

        const getAddress = () => {
             if (order.value.shipping_address) {
                const parts = order.value.shipping_address.split("|");
                return parts.length > 0 ? parts[0].trim() : t('not_available');
            }
            return t('not_available');
        };

        const statusBadgeClass = computed(() => {
            const status = order.value?.status;
            switch(status) {
                case 'completed': return 'bg-green-100 text-green-800';
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                case 'processing': return 'bg-blue-100 text-blue-800';
                case 'shipped': return 'bg-indigo-100 text-indigo-800';
                case 'cancelled': return 'bg-red-100 text-red-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        });

        onMounted(fetchOrder);

        return { 
            order, 
            loading, 
            updateStatus, 
            formatPrice, 
            formatDate, 
            statusBadgeClass,
            getUserName, 
            getPhone, 
            getAddress,
            fetchOrder
        };
    },
};
</script>

<style scoped>
.text-primary-dark {
    color: #2c3e50;
}
</style>
