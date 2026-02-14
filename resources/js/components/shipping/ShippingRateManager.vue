<template>
    <div class="mt-8 border-t pt-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ $t('shipping_rates') }}</h3>
            <button @click="openModal" type="button" class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm hover:bg-blue-700 transition">
                + {{ $t('add_rate') }}
            </button>
        </div>

        <!-- Rate Calculation Explanation -->
        <div class="mb-6 bg-gray-50 border-r-4 border-gray-300 p-4 rounded-l-lg text-right" dir="rtl">
            <h4 class="text-sm font-bold text-gray-800 mb-2">أنواع حساب الأسعار (Calculation Types):</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <span class="text-xs font-black text-gray-700 block uppercase mb-1">حمولة بالوزن (Weight):</span>
                    <p class="text-[11px] text-gray-600 leading-snug">تحسب التكلفة بناءً على إجمالي وزن المنتجات. مثال: 0-5 كجم بـ 25 ريال.</p>
                </div>
                <div>
                    <span class="text-xs font-black text-gray-700 block uppercase mb-1">حسب السعر (Price):</span>
                    <p class="text-[11px] text-gray-600 leading-snug">تتغير التكلفة حسب مبلغ السلة. مثال: الطلبات بين 100-200 ريال شحنها 15 ريال.</p>
                </div>
                <div>
                    <span class="text-xs font-black text-gray-700 block uppercase mb-1">ثابت (Flat Rate):</span>
                    <p class="text-[11px] text-gray-600 leading-snug">تكلفة موحدة بغض النظر عن الوزن أو السعر. مثال: توصيل ثابت بـ 30 ريال.</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-200">
                <p class="text-[11px] text-indigo-700 font-bold">
                    * حد الشحن المجاني (Threshold): هو المبلغ الذي إذا وصل إليه إجمالي السلة، يصبح الشحن لهذه المنطقة مجانياً تلقائياً.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase">{{ $t('shipping_companies') }}</th>
                        <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase">{{ $t('calculation_type') }}</th>
                        <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase">{{ $t('min_value') }} - {{ $t('max_value') }}</th>
                        <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase">{{ $t('cost') }}</th>
                        <th class="px-4 py-2 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="rate in rates" :key="rate.id">
                        <td class="px-4 py-2 text-sm text-gray-900">{{ getTrans(rate.company?.name) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100">
                                {{ $t(rate.calculation_type + '_based') || $t(rate.calculation_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-500">
                            {{ rate.min_value }} - {{ rate.max_value || '∞' }}
                        </td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ rate.cost }}</td>
                        <td class="px-4 py-2 text-right rtl:text-left text-sm font-medium">
                            <button @click="editRate(rate)" type="button" class="text-indigo-600 hover:text-indigo-900 mx-1">{{ $t('edit') }}</button>
                            <button @click="deleteRate(rate.id)" type="button" class="text-red-600 hover:text-red-900 mx-1">{{ $t('delete') }}</button>
                        </td>
                    </tr>
                    <tr v-if="rates.length === 0">
                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">{{ $t('no_data') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left rtl:text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ isEditing ? $t('edit_rate') : $t('add_rate') }}
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('shipping_companies') }}</label>
                                <select v-model="form.shipping_company_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                        {{ getTrans(company.name) }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ $t('calculation_type') }}</label>
                                <select v-model="form.calculation_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="weight">{{ $t('weight_based') }}</option>
                                    <option value="price">{{ $t('price_based') }}</option>
                                    <option value="flat">{{ $t('flat_rate') }}</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('min_value') }}</label>
                                    <input type="number" step="0.01" v-model="form.min_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('max_value') }}</label>
                                    <input type="number" step="0.01" v-model="form.max_value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('cost') }}</label>
                                    <input type="number" step="0.01" v-model="form.cost" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('free_shipping_threshold') }}</label>
                                    <input type="number" step="0.01" v-model="form.free_shipping_threshold" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="saveRate" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 rtl:sm:ml-0 rtl:sm:mr-3 sm:w-auto sm:text-sm">
                            {{ $t('save') }}
                        </button>
                        <button @click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 rtl:sm:ml-0 rtl:sm:mr-3 sm:w-auto sm:text-sm">
                            {{ $t('cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        zoneId: {
            type: [Number, String],
            required: true
        }
    },
    data() {
        return {
            rates: [],
            companies: [],
            showModal: false,
            isEditing: false,
            editId: null,
            form: {
                shipping_company_id: '',
                calculation_type: 'weight',
                min_value: 0,
                max_value: null,
                cost: 0,
                free_shipping_threshold: null
            }
        };
    },
    methods: {
        getTrans(val) {
            if (!val) return "";
            if (typeof val === 'object') {
                return val[this.$i18n.locale] || val.en || "";
            }
            return val;
        },
        async fetchRates() {
            if (!this.zoneId) return;
            try {
                const { data } = await axios.get(`/api/shipping-rates?zone_id=${this.zoneId}`);
                this.rates = data;
            } catch (error) {
                console.error("Failed to load rates", error);
            }
        },
        async fetchCompanies() {
            try {
                const { data } = await axios.get('/api/shipping-companies');
                this.companies = data;
            } catch (error) {
                console.error("Failed to load companies", error);
            }
        },
        openModal() {
            this.isEditing = false;
            this.editId = null;
            this.form = {
                shipping_company_id: this.companies.length > 0 ? this.companies[0].id : '',
                calculation_type: 'weight',
                min_value: 0,
                max_value: null,
                cost: 0,
                free_shipping_threshold: null
            };
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        editRate(rate) {
            this.isEditing = true;
            this.editId = rate.id;
            this.form = {
                shipping_company_id: rate.shipping_company_id,
                calculation_type: rate.calculation_type,
                min_value: rate.min_value,
                max_value: rate.max_value,
                cost: rate.cost,
                free_shipping_threshold: rate.free_shipping_threshold
            };
            this.showModal = true;
        },
        async saveRate() {
            try {
                const payload = { ...this.form, shipping_zone_id: this.zoneId };
                if (this.isEditing) {
                    await axios.put(`/api/shipping-rates/${this.editId}`, payload);
                } else {
                    await axios.post('/api/shipping-rates', payload);
                }
                this.closeModal();
                this.fetchRates();
            } catch (error) {
                console.error("Failed to save rate", error);
                alert(this.$t('failed_save'));
            }
        },
        async deleteRate(id) {
            if (!confirm(this.$t('confirm_delete'))) return;
            try {
                await axios.delete(`/api/shipping-rates/${id}`);
                this.fetchRates();
            } catch (error) {
                console.error("Failed to delete rate", error);
                alert(this.$t('failed_delete'));
            }
        }
    },
    mounted() {
        this.fetchCompanies();
        if (this.zoneId) {
            this.fetchRates();
        }
    },
    watch: {
        zoneId(newVal) {
            if (newVal) {
                this.fetchRates();
            }
        }
    }
};
</script>
