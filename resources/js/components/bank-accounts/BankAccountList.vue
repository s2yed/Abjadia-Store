<template>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $t('bank_accounts') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $t('manage_bank_accounts_desc') }}</p>
            </div>
            <router-link
                to="/dashboard/bank-accounts/create"
                class="inline-flex items-center px-4 py-2 bg-secondary-orange text-white rounded-lg hover:bg-orange-600 transition-colors shadow-sm text-sm font-medium"
            >
                <span class="mr-2 rtl:mr-0 rtl:ml-2">+</span> {{ $t('add_new') }}
            </router-link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right">
                    <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                        <tr>
                            <th class="px-6 py-4">{{ $t('bank_name') }}</th>
                            <th class="px-6 py-4">{{ $t('account_type') }}</th>
                            <th class="px-6 py-4">{{ $t('account_name') }}</th>
                            <th class="px-6 py-4">{{ $t('account_number') }}</th>
                            <th class="px-6 py-4">{{ $t('iban') }}</th>
                            <th class="px-6 py-4 text-center">{{ $t('status') }}</th>
                            <th class="px-6 py-4 text-center">{{ $t('online_default') }}</th>
                            <th class="px-6 py-4 text-right rtl:text-left">{{ $t('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="account in accounts" :key="account.id" class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ account.bank_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">
                                    {{ $t(account.type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ account.name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-mono text-gray-600">{{ account.account_number || '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-mono text-gray-600">{{ account.iban || '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span 
                                    :class="account.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                    class="px-2 py-1 rounded-full text-xs font-medium"
                                >
                                    {{ account.is_active ? $t('active') : $t('inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span v-if="account.is_online_default" class="inline-flex items-center text-blue-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span v-else class="text-gray-300">-</span>
                            </td>
                            <td class="px-6 py-4 text-right rtl:text-left space-x-3 rtl:space-x-reverse">
                                <router-link
                                    :to="`/dashboard/bank-accounts/${account.id}/edit`"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                >
                                    {{ $t('edit') }}
                                </router-link>
                                <button
                                    @click="deleteAccount(account.id)"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium"
                                >
                                    {{ $t('delete') }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="accounts.length === 0 && !loading">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                {{ $t('no_data') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-secondary-orange"></div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useI18n } from 'vue-i18n';

export default {
    setup() {
        const { t } = useI18n();
        const accounts = ref([]);
        const loading = ref(true);

        const fetchAccounts = async () => {
            try {
                const response = await axios.get('/api/bank-accounts');
                accounts.value = response.data;
            } catch (error) {
                console.error('Failed to fetch bank accounts:', error);
            } finally {
                loading.value = false;
            }
        };

        const deleteAccount = async (id) => {
            if (confirm(t('confirm_delete'))) {
                try {
                    await axios.delete(`/api/bank-accounts/${id}`);
                    fetchAccounts();
                } catch (error) {
                    alert(t('failed_delete'));
                }
            }
        };

        onMounted(fetchAccounts);

        return { accounts, loading, deleteAccount };
    }
};
</script>
