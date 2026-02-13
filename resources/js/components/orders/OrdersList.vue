<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('orders') }}</h2>
            <div class="w-1/3">
                <input
                    type="text"
                    v-model="search"
                    @input="handleSearch"
                    :placeholder="$t('search_orders')"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-dark focus:border-primary-dark sm:text-sm"
                >
            </div>
        </div>

        <!-- Error State -->
        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>

        <!-- Orders Table -->
        <div v-if="!loading && !error" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('order_id') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('customer') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('date') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('total') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('status') }}</th>
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="order in orders" :key="order.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-primary-dark"
                                >#{{ order.id }}</span
                            >
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{
                                    order.shipping_address
                                        ? order.shipping_address
                                              .split("|")[2]
                                              .trim()
                                        : order.user
                                        ? order.user.name
                                        : $t('guest')
                                }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ order.user ? order.user.email : "-" }}
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(order.created_at) }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"
                        >
                            {{ order.total_price }} {{ $t('currency') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"
                            >
                                {{ $t(order.status) }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium"
                        >
                            <router-link
                                :to="`/dashboard/orders/${order.id}`"
                                class="text-secondary-orange hover:text-orange-700"
                                >{{ $t('view') }}</router-link
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="totalPages > 0"
            class="mt-6 flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-100"
        >
            <button
                @click="fetchOrders(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                &larr; {{ $t('previous') }}
            </button>
            <span class="text-sm font-medium text-gray-700">
                {{ $t('page') }} {{ currentPage }} {{ $t('of') }} {{ totalPages }}
            </span>
            <button
                @click="fetchOrders(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                {{ $t('next') }} &rarr;
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            orders: [],
            loading: true,
            error: null,
            currentPage: 1,
            totalPages: 1,
            search: '',
            debounceTimeout: null,
        };
    },
    methods: {
        handleSearch() {
             if (this.debounceTimeout) clearTimeout(this.debounceTimeout);
             this.debounceTimeout = setTimeout(() => {
                 this.fetchOrders(1);
             }, 300);
        },
        async fetchOrders(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get(`/api/orders?page=${page}`, {
                    params: { search: this.search }
                });
                this.orders = response.data.data;
                this.currentPage = response.data.current_page;
                this.totalPages = response.data.last_page;
            } catch (err) {
                this.error = this.$t('failed_load');
                console.error(err);
            } finally {
                this.loading = false;
            }
        },
        formatDate(dateString) {
            const locale = document.documentElement.lang === 'ar' ? 'ar-SA' : 'en-US';
            return new Date(dateString).toLocaleDateString(locale);
        }
    },
    mounted() {
        this.fetchOrders();
    },
};
</script>

<style scoped>
.text-primary-dark {
    color: #2c3e50;
}
.text-secondary-orange {
    color: #e67e22;
}
</style>
