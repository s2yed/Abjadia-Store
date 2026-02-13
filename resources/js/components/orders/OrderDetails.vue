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

            <!-- Payment History -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 mt-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">{{ $t('payment_history') }}</h3>
                     <button @click="showPaymentModal = true" class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded-full hover:bg-blue-200 transition-colors">
                        + {{ $t('add_payment') }}
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 font-medium">
                                <th class="px-6 py-3 text-left rtl:text-right">{{ $t('amount') }}</th>
                                <th class="px-6 py-3 text-center">{{ $t('payment_method') }}</th>
                                <th class="px-6 py-3 text-center">{{ $t('status') }}</th>
                                <th class="px-6 py-3 text-center">{{ $t('date') }}</th>
                                <th class="px-6 py-3 text-right rtl:text-left">{{ $t('actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="payment in order.payments" :key="payment.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ formatPrice(payment.amount) }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $t(payment.payment_method) || payment.payment_method }}</td>
                                 <td class="px-6 py-4 text-center">
                                    <span :class="{'bg-green-100 text-green-800': payment.status === 'approved', 'bg-yellow-100 text-yellow-800': payment.status === 'pending', 'bg-red-100 text-red-800': payment.status === 'rejected'}" class="px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $t(payment.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ formatDate(payment.created_at) }}</td>
                                 <td class="px-6 py-4 text-right rtl:text-left text-sm">
                                    <button @click="deletePayment(payment.id)" class="text-red-600 hover:text-red-900">{{ $t('delete') }}</button>
                                </td>
                            </tr>
                            <tr v-if="!order.payments || order.payments.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">{{ $t('no_data') }}</td>
                            </tr>
                        </tbody>
                    </table>
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
                        <div class="flex justify-between text-gray-500 text-sm mt-2">
                             <span>{{ $t('paid_amount') }}</span>
                             <span class="text-green-600 font-medium">{{ formatPrice(order.paid_amount || 0) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500 text-sm">
                             <span>{{ $t('remaining_amount') }}</span>
                             <span class="text-red-600 font-medium">{{ formatPrice(order.remaining_amount || order.total_price) }}</span>
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
    <!-- Add Payment Modal -->
    <div v-if="showPaymentModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showPaymentModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left rtl:text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ $t('record_payment') }}</h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('amount') }}</label>
                            <input type="number" step="0.01" v-model="paymentForm.amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('payment_method') }}</label>
                            <select v-model="paymentForm.payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="cash">{{ $t('cash') || 'Cash' }}</option>
                                <option value="bank_transfer">{{ $t('bank_transfer') || 'Bank Transfer' }}</option>
                                <option value="credit_card">{{ $t('credit_card') || 'Credit Card' }}</option>
                                <option value="wallet">{{ $t('wallet') || 'Wallet' }}</option>
                            </select>
                        </div>
                         <div v-if="['bank_transfer', 'wallet', 'credit_card'].includes(paymentForm.payment_method)">
                            <label class="block text-sm font-medium text-gray-700">{{ $t('bank_account') }}</label>
                            <select v-model="paymentForm.bank_account_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option :value="null">{{ $t('select_account') }}</option>
                                <option v-for="account in activeBankAccounts" :key="account.id" :value="account.id">
                                    {{ account.bank_name }} - {{ account.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('status') }}</label>
                            <select v-model="paymentForm.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending">{{ $t('pending') }}</option>
                                <option value="approved">{{ $t('approved') }}</option>
                                <option value="rejected">{{ $t('rejected') }}</option>
                            </select>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('transaction_id') }}</label>
                            <input type="text" v-model="paymentForm.transaction_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('note') }}</label>
                            <textarea v-model="paymentForm.note" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="submitPayment" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 rtl:sm:ml-0 rtl:sm:mr-3 sm:w-auto sm:text-sm">
                        {{ $t('save') }}
                    </button>
                    <button @click="showPaymentModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 rtl:sm:ml-0 rtl:sm:mr-3 sm:w-auto sm:text-sm">
                        {{ $t('cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute } from "vue-router";
import { ref, onMounted, computed } from "vue";
import { useI18n } from 'vue-i18n';
import { useNotification } from '../../stores/notification';

export default {
    setup() {
        const { t } = useI18n();
        const route = useRoute();
        const notify = useNotification();
        const order = ref(null);
        const loading = ref(true);
        const showPaymentModal = ref(false);
        const activeBankAccounts = ref([]);
        const paymentForm = ref({
            amount: 0,
            payment_method: 'cash',
            status: 'approved',
            transaction_id: '',
            note: '',
            bank_account_id: null
        });

        const fetchBankAccounts = async () => {
            try {
                const response = await axios.get('/api/bank-accounts');
                activeBankAccounts.value = response.data.filter(acc => acc.is_active);
            } catch (err) {
                console.error(err);
            }
        };

        const fetchOrder = async () => {
            try {
                const response = await axios.get(
                    `/api/orders/${route.params.id}`
                );
                order.value = response.data;
                // Initialize default payment amount to remaining
                if (order.value) {
                     paymentForm.value.amount = order.value.remaining_amount || (order.value.paid_amount ? 0 : order.value.total_price);
                }
            } catch (err) {
                console.error(err);
            } finally {
                loading.value = false;
            }
        };

        const submitPayment = async () => {
            try {
                const payload = {
                    ...paymentForm.value,
                    order_id: order.value.id
                };
                await axios.post('/api/order-payments', payload);
                notify.success(t('payment_recorded'));
                showPaymentModal.value = false;
                fetchOrder(); // Refresh data
            } catch (err) {
                console.error(err);
                notify.error(t('failed_save'));
            }
        };

        const deletePayment = async (id) => {
            if (!confirm(t('confirm_delete_payment'))) return;
            try {
                await axios.delete(`/api/order-payments/${id}`);
                notify.success(t('payment_deleted'));
                fetchOrder(); // Refresh data
            } catch (err) {
                 console.error(err);
                notify.error(t('failed_delete'));
            }
        };

        const updateStatus = async () => {
            try {
                await axios.put(`/api/orders/${order.value.id}`, {
                    status: order.value.status,
                });
                notify.success(t('order_status_updated'));
            } catch (err) {
                const msg = err.response?.data?.message || err.message || t('try_again');
                notify.error(msg);
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

        onMounted(() => {
            fetchOrder();
            fetchBankAccounts();
        });

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
            fetchOrder,
            showPaymentModal,
            paymentForm,
            submitPayment,
            deletePayment,
            activeBankAccounts
        };
    },
};
</script>

<style scoped>
.text-primary-dark {
    color: #2c3e50;
}
</style>
