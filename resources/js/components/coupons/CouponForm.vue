<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">
                {{ isEdit ? $t('edit_coupon') : $t('add_coupon') }}
            </h2>
            <router-link
                to="/dashboard/coupons"
                class="text-gray-600 hover:text-gray-900"
            >
                <span class="rtl:hidden">&larr;</span> {{ $t('back_to_list') }} <span class="hidden rtl:inline">&rarr;</span>
            </router-link>
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('code') }}</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input
                             v-model="form.code"
                             type="text"
                             required
                             class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md rtl:rounded-r-md rtl:rounded-l-none border-gray-300 focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm border"
                        />
                         <button
                            type="button"
                            @click="generateCode"
                            class="inline-flex items-center px-3 py-2 border border-l-0 rtl:border-r-0 rtl:border-l border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md rtl:rounded-l-md rtl:rounded-r-none hover:bg-gray-100"
                        >
                            {{ $t('generate_code') }}
                        </button>
                    </div>
                </div>

                <!-- Type -->
                <div>
                     <label class="block text-sm font-medium text-gray-700">{{ $t('type') }}</label>
                     <select
                        v-model="form.type"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm"
                    >
                        <option value="fixed">{{ $t('fixed') }}</option>
                        <option value="percentage">{{ $t('percentage') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Value -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('value') }}</label>
                    <input
                        v-model="form.value"
                        type="number"
                        step="0.01"
                        min="0"
                        required
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm"
                    />
                </div>

                <!-- Expiry Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('expiry_date') }}</label>
                    <input
                        v-model="form.expiry_date"
                        type="date"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm"
                    />
                </div>
            </div>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Usage Limit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('usage_limit') }}</label>
                    <input
                        v-model="form.usage_limit"
                        type="number"
                        min="1"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm"
                    />
                </div>

                <!-- Status -->
                <div class="flex items-center mt-6">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="h-4 w-4 text-secondary-orange focus:ring-secondary-orange border-gray-300 rounded"
                    />
                    <label for="is_active" class="ml-2 rtl:ml-0 rtl:mr-2 block text-sm text-gray-900">
                        {{ $t('active') }}
                    </label>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Restrictions -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Categories -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('specific_categories') }}</label>
                     <select
                        v-model="form.category_ids"
                        multiple
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 h-48"
                    >
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                             {{ typeof cat.name === 'object' ? (cat.name[$i18n.locale] || cat.name.en) : cat.name }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">{{ $t('hold_ctrl_multiselect') }}</p>
                </div>

                 <!-- Products -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('specific_products') }}</label>
                     <select
                        v-model="form.product_ids"
                        multiple
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange sm:text-sm border p-2 h-48"
                    >
                        <option
                            v-for="prod in products"
                            :key="prod.id"
                            :value="prod.id"
                        >
                            {{ typeof prod.name === 'object' ? (prod.name[$i18n.locale] || prod.name.en) : prod.name }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">{{ $t('hold_ctrl_multiselect') }}</p>
                </div>
            </div>

            <div class="flex justify-end pt-5">
                <button
                    type="submit"
                    :disabled="submitting"
                    class="bg-secondary-orange text-white px-4 py-2 rounded-md shadow hover:bg-orange-700 transition-colors disabled:opacity-50"
                >
                    {{ submitting ? $t('saving') : $t('save') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, reactive, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';

export default {
    setup() {
        const { t } = useI18n();
        const route = useRoute();
        const router = useRouter();
        const isEdit = computed(() => !!route.params.id);
        const submitting = ref(false);
        const products = ref([]);
        const categories = ref([]);

        const form = reactive({
            code: '',
            type: 'fixed',
            value: '',
            expiry_date: '',
            usage_limit: '',
            is_active: true,
            product_ids: [],
            category_ids: []
        });

        const fetchOptions = async () => {
             try {
                const [prodRes, catRes] = await Promise.all([
                    axios.get("/api/products?per_page=100"), // Simple limit for now, ideally search based
                    axios.get("/api/categories")
                ]);
                products.value = prodRes.data.data || prodRes.data;
                categories.value = catRes.data;
            } catch (err) {
                console.error("Failed to load options", err);
            }
        };

        const fetchCoupon = async () => {
            if (!isEdit.value) return;
            try {
                const response = await axios.get(`/api/coupons/${route.params.id}`);
                const data = response.data;
                Object.assign(form, {
                    code: data.code,
                    type: data.type,
                    value: data.value,
                    expiry_date: data.expiry_date,
                    usage_limit: data.usage_limit,
                    is_active: !!data.is_active,
                    product_ids: data.products ? data.products.map(p => p.id) : [],
                    category_ids: data.categories ? data.categories.map(c => c.id) : []
                });
            } catch (error) {
                console.error('Error fetching coupon:', error);
                alert(t('failed_load'));
            }
        };

        const generateCode = () => {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            form.code = result;
        };

        const submitForm = async () => {
            submitting.value = true;
            try {
                if (isEdit.value) {
                    await axios.put(`/api/coupons/${route.params.id}`, form);
                    alert(t('coupon_updated'));
                } else {
                    await axios.post('/api/coupons', form);
                    alert(t('coupon_created'));
                }
                router.push('/dashboard/coupons');
            } catch (error) {
                console.error('Error saving coupon:', error);
                alert(t('failed_save') + ': ' + (error.response?.data?.message || error.message));
            } finally {
                submitting.value = false;
            }
        };

        onMounted(() => {
            fetchOptions();
            fetchCoupon();
        });

        return {
            form,
            isEdit,
            submitting,
            products,
            categories,
            submitForm,
            generateCode
        };
    }
}
</script>
