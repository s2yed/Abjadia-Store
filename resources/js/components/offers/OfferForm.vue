<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">
                {{ isEdit ? $t('edit_offer') : $t('add_offer') }}
            </h2>
            <router-link to="/dashboard/offers" class="text-gray-600 hover:text-gray-900">
                {{ $t('back_to_list') }}
            </router-link>
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('name') }}</label>
                    <input v-model="form.name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-white text-black border focus:ring-secondary-orange" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('type') }}</label>
                    <select v-model="form.type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-white text-black border focus:ring-secondary-orange">
                        <option value="buy_x_get_y">{{ $t('offer_type_buy_x_get_y') }}</option>
                        <option value="percentage_off">{{ $t('offer_type_percentage_off') }}</option>
                        <option value="fixed_amount">{{ $t('offer_type_fixed_amount') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('priority') }}</label>
                    <input v-model="form.priority" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-white text-black border focus:ring-secondary-orange" />
                </div>
                <div class="flex items-center mt-6">
                    <input id="is_active" v-model="form.is_active" type="checkbox" class="h-4 w-4 text-green-600 border-gray-300 rounded" />
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">{{ $t('active') }}</label>
                </div>
            </div>

             <!-- Conditions Builder -->
            <div class="border rounded-md p-4 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $t('conditions') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('condition_type') }}</label>
                         <select v-model="form.conditions.type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-white border">
                            <option value="min_qty">{{ $t('condition_min_qty') }}</option>
                            <option value="min_subtotal">{{ $t('condition_min_subtotal') }}</option>
                        </select>
                     </div>

                    <div v-if="form.conditions.type === 'min_qty'">
                        <SearchableSelect
                            v-model="form.conditions.product_id"
                            :options="productOptions"
                            :label="$t('product')"
                            :placeholder="$t('search_products')"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('value') }}</label>
                        <input v-model="form.conditions.value" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-white border" />
                    </div>
                </div>
            </div>

            <!-- Actions Builder -->
            <div class="border rounded-md p-4 bg-blue-50">
                <h3 class="text-lg font-medium text-blue-900 mb-2">{{ $t('actions') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('action_type') }}</label>
                         <select v-model="form.actions.type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-white border">
                            <option value="percentage_off">{{ $t('action_percentage_off') }}</option>
                            <option value="fixed_amount">{{ $t('action_fixed_amount') }}</option>
                            <option value="free_product">{{ $t('action_free_product') }}</option>
                            <option value="free_shipping">{{ $t('action_free_shipping') }}</option>
                        </select>
                     </div>

                    <div v-if="form.actions.type === 'free_product'">
                        <SearchableSelect
                            v-model="form.actions.free_product_id"
                            :options="productOptions"
                            :label="$t('free_product')"
                            :placeholder="$t('search_products')"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $t('value') }}</label>
                        <input v-model="form.actions.value" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 bg-white border" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-5">
                <button type="submit" class="bg-secondary-orange text-white px-4 py-2 rounded-md shadow hover:bg-orange-700 transition-colors">
                    {{ isEdit ? $t('update') : $t('save') }}
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
import SearchableSelect from '../ui/SearchableSelect.vue';

export default {
    components: {
        SearchableSelect
    },
    setup() {
        const { t, locale } = useI18n();
        const route = useRoute();
        const router = useRouter();
        const isEdit = computed(() => !!route.params.id);
        const products = ref([]);
        
        const form = reactive({
            name: '',
            type: 'buy_x_get_y',
            priority: 0,
            is_active: true,
            conditions: {
                type: 'min_qty',
                product_id: null,
                value: 1
            },
            actions: {
                type: 'free_product',
                value: 1,
                free_product_id: null
            }
        });

        const productOptions = computed(() => {
            return products.value.map(p => ({
                id: p.id,
                label: typeof p.name === 'object' ? (p.name[locale.value] || p.name.en || p.name) : p.name
            }));
        });

        const fetchProducts = async () => {
            try {
                // Use list_only optimization
                const response = await axios.get('/api/products?list_only=1');
                products.value = response.data.data;
            } catch (err) {
                console.error(err);
            }
        };

        const fetchOffer = async () => {
            if (!isEdit.value) return;
            try {
                const { data } = await axios.get(`/api/offers/${route.params.id}`);
                Object.assign(form, data);
                 if (typeof form.conditions === 'string') form.conditions = JSON.parse(form.conditions);
                if (typeof form.actions === 'string') form.actions = JSON.parse(form.actions);
            } catch (err) {
                console.error(err);
            }
        };

        const submitForm = async () => {
             try {
                if (isEdit.value) {
                    await axios.put(`/api/offers/${route.params.id}`, form);
                } else {
                    await axios.post('/api/offers', form);
                }
                router.push('/dashboard/offers');
            } catch (err) {
                console.error(err);
                alert(t('failed_save'));
            }
        };

        onMounted(() => {
            fetchProducts();
            fetchOffer();
        });

        return {
            form,
            isEdit,
            products,
            productOptions,
            submitForm
        };
    }
}
</script>
