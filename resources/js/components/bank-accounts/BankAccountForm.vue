<template>
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ isEdit ? $t('edit_bank_account') : $t('add_bank_account') }}
            </h1>
            <router-link to="/dashboard/bank-accounts" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; {{ $t('back_to_list') }}
            </router-link>
        </div>

        <form @submit.prevent="saveAccount" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Account Type -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">{{ $t('account_type') }}</label>
                    <select
                        v-model="form.type"
                        required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none"
                    >
                        <option value="bank">{{ $t('bank') }}</option>
                        <option value="wallet">{{ $t('wallet') }}</option>
                        <option value="pos">{{ $t('pos') }}</option>
                        <option value="cash">{{ $t('cash') }}</option>
                    </select>
                </div>

                <!-- Account Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">{{ $t('account_name') }}</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none"
                    />
                </div>

                <!-- Bank/Wallet/POS Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">
                        {{ 
                            form.type === 'wallet' ? $t('wallet_name') : 
                            form.type === 'pos' ? $t('machine_name') : 
                            form.type === 'cash' ? $t('branch_name') : 
                            $t('bank_name') 
                        }}
                    </label>
                    <input
                        v-model="form.bank_name"
                        type="text"
                        required
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none"
                    />
                </div>

                <!-- Account/Wallet Number / Merchant ID -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">
                        {{ 
                            form.type === 'wallet' ? $t('wallet_number') : 
                            form.type === 'pos' ? $t('merchant_id') : 
                            $t('account_number') 
                        }}
                    </label>
                    <input
                        v-model="form.account_number"
                        type="text"
                        :required="['wallet', 'bank'].includes(form.type)"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none"
                    />
                </div>

                <!-- IBAN (Only for bank) -->
                <div v-if="form.type === 'bank'" class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">{{ $t('iban') }}</label>
                    <input
                        v-model="form.iban"
                        type="text"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-secondary-orange/20 focus:border-secondary-orange transition-all outline-none font-mono uppercase"
                    />
                </div>
            </div>

            <div class="flex items-center space-x-8 rtl:space-x-reverse border-t border-gray-50 pt-6">
                <!-- Is Active -->
                <label class="flex items-center cursor-pointer group">
                    <div class="relative">
                        <input v-model="form.is_active" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] rtl:after:left-auto rtl:after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary-orange"></div>
                    </div>
                    <span class="ml-3 rtl:ml-0 rtl:mr-3 text-sm font-medium text-gray-700">{{ $t('active') }}</span>
                </label>

                <!-- Is Online Default -->
                <label class="flex items-center cursor-pointer group">
                    <div class="relative">
                        <input v-model="form.is_online_default" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] rtl:after:left-auto rtl:after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </div>
                    <span class="ml-3 rtl:ml-0 rtl:mr-3 text-sm font-medium text-gray-700">{{ $t('online_default_account') }}</span>
                </label>
            </div>

            <div class="flex justify-end space-x-4 rtl:space-x-reverse border-t border-gray-50 pt-6">
                <router-link
                    to="/dashboard/bank-accounts"
                    class="px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors"
                >
                    {{ $t('cancel') }}
                </router-link>
                <button
                    type="submit"
                    :disabled="saving"
                    class="px-8 py-2 bg-secondary-orange text-white rounded-lg hover:bg-orange-600 transition-colors shadow-sm disabled:opacity-50 text-sm font-bold"
                >
                    {{ saving ? $t('saving') : $t('save') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useI18n } from 'vue-i18n';

export default {
    setup() {
        const { t } = useI18n();
        const route = useRoute();
        const router = useRouter();
        const notify = useNotification();
        const isEdit = ref(route.params.id !== undefined);
        const saving = ref(false);

        const form = ref({
            name: '',
            bank_name: '',
            account_number: '',
            iban: '',
            is_active: true,
            is_online_default: false,
            type: 'bank'
        });

        const fetchAccount = async () => {
            if (!isEdit.value) return;
            try {
                const response = await axios.get(`/api/bank-accounts/${route.params.id}`);
                form.value = { 
                    ...response.data,
                    is_active: !!response.data.is_active,
                    is_online_default: !!response.data.is_online_default
                };
            } catch (error) {
                notify.error(t('failed_load'));
                router.push('/dashboard/bank-accounts');
            }
        };

        const saveAccount = async () => {
            saving.value = true;
            try {
                if (isEdit.value) {
                    await axios.put(`/api/bank-accounts/${route.params.id}`, form.value);
                } else {
                    await axios.post('/api/bank-accounts', form.value);
                }
                notify.success(t('saved_successfully'));
                router.push('/dashboard/bank-accounts');
            } catch (error) {
                notify.error(t('failed_save'));
            } finally {
                saving.value = false;
            }
        };

        onMounted(fetchAccount);

        return { form, isEdit, saving, saveAccount };
    }
};
</script>
