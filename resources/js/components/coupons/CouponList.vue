<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('coupon_list') }}</h2>
            <div class="mt-4 md:mt-0 flex space-x-4 rtl:space-x-reverse">
                <router-link
                    to="/dashboard/coupons/create"
                    class="bg-secondary-orange text-white px-4 py-2 rounded-md shadow hover:bg-orange-700 transition-colors flex items-center"
                >
                    <span class="mr-2 rtl:mr-0 rtl:ml-2 text-xl">+</span> {{ $t('add_coupon') }}
                </router-link>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-secondary-orange"></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="coupons.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ $t('no_data') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $t('add_first_item') }}</p>
        </div>

        <!-- Data Table -->
        <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('code') }}
                        </th>
                         <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('type') }}
                        </th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('value') }}
                        </th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('usage_limit') }}
                        </th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('expiry_date') }}
                        </th>
                         <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('status') }}
                        </th>
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="coupon in coupons" :key="coupon.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ coupon.code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                             {{ $t(coupon.type) }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            {{ coupon.type === 'percentage' ? coupon.value + '%' : formatPrice(coupon.value) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ coupon.used_count }} / {{ coupon.usage_limit || '∞' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ formatDate(coupon.expiry_date) }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="coupon.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ coupon.is_active ? $t('active') : $t('inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium">
                            <router-link
                                :to="`/dashboard/coupons/${coupon.id}/edit`"
                                class="text-indigo-600 hover:text-indigo-900 mr-4 rtl:mr-0 rtl:ml-4"
                            >
                                {{ $t('edit') }}
                            </router-link>
                            <button
                                @click="deleteCoupon(coupon.id)"
                                class="text-red-600 hover:text-red-900"
                            >
                                {{ $t('delete') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

         <!-- Pagination -->
        <div v-if="pagination.total > pagination.per_page" class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-700">
                {{ $t('page_of', { current: pagination.current_page, total: pagination.last_page}) }}
            </div>
            <div class="flex space-x-2 rtl:space-x-reverse">
                <button
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-3 py-1 border rounded text-sm disabled:opacity-50"
                >
                    {{ $t('previous') }}
                </button>
                <button
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-3 py-1 border rounded text-sm disabled:opacity-50"
                >
                    {{ $t('next') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';

export default {
    setup() {
        const { t } = useI18n();
        const coupons = ref([]);
        const loading = ref(true);
        const pagination = ref({});

        const fetchCoupons = async (page = 1) => {
            loading.value = true;
            try {
                const response = await axios.get(`/api/coupons?page=${page}`);
                coupons.value = response.data.data;
                pagination.value = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    per_page: response.data.per_page,
                    total: response.data.total
                };
            } catch (error) {
                console.error('Error fetching coupons:', error);
            } finally {
                loading.value = false;
            }
        };

        const deleteCoupon = async (id) => {
            if (!confirm(t('confirm_delete'))) return;
            
            try {
                await axios.delete(`/api/coupons/${id}`);
                fetchCoupons(pagination.value.current_page);
                alert(t('coupon_deleted'));
            } catch (error) {
                console.error('Error deleting coupon:', error);
                alert(t('failed_delete'));
            }
        };

        const changePage = (page) => {
            if (page >= 1 && page <= pagination.value.last_page) {
                fetchCoupons(page);
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
                year: 'numeric', month: 'long', day: 'numeric'
            });
        };

        onMounted(() => {
            fetchCoupons();
        });

        return {
            coupons,
            loading,
            pagination,
            deleteCoupon,
            changePage,
            formatPrice,
            formatDate
        };
    }
}
</script>
