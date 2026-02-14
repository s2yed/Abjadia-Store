<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('offer_list') }}</h2>
            <div class="mt-4 md:mt-0">
                <router-link
                    to="/dashboard/offers/create"
                    class="bg-secondary-orange text-white px-4 py-2 rounded-md shadow hover:bg-orange-700 transition-colors flex items-center"
                >
                    <span class="mr-2 rtl:mr-0 rtl:ml-2 text-xl">+</span> {{ $t('add_offer') }}
                </router-link>
            </div>
        </div>

        <!-- System Explanation Card -->
        <div class="mb-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 rtl:ml-0 rtl:mr-3">
                    <h3 class="text-sm font-bold text-blue-800 mb-1">كيف يعمل نظام العروض؟</h3>
                    <p class="text-xs text-blue-700 leading-relaxed mb-2">
                        نظام العروض يتيح لك تطبيق خصومات تلقائية أو تقديم هدايا (مثل شحن مجاني) بناءً على شروط محددة. 
                        يتم التحقق من هذه العروض تلقائياً عند إضافة منتج للسلة.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="bg-white/50 p-2 rounded border border-blue-100">
                            <span class="text-[10px] font-black text-blue-800 block uppercase">مثال خصم القيمة:</span>
                            <span class="text-xs text-blue-600">اشترِ بقيمة 1000 ريال واحصل على خصم 100 ريال تلقائياً.</span>
                        </div>
                        <div class="bg-white/50 p-2 rounded border border-blue-100">
                            <span class="text-[10px] font-black text-blue-800 block uppercase">مثال شحن مجاني:</span>
                            <span class="text-xs text-blue-600">عروض خاصة لفترة محدودة تمنح العميل شحناً مجانياً لأي طلب.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('name') }}
                        </th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('type') }}
                        </th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('action_type') }}
                        </th>
                         <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $t('priority') }}
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
                    <tr v-for="offer in offers" :key="offer.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ offer.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $t('offer_type_' + offer.type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ offer.actions && offer.actions.type ? $t('action_' + offer.actions.type) : '-' }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ offer.priority }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="offer.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ offer.is_active ? $t('active') : $t('inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium">
                            <router-link
                                :to="`/dashboard/offers/${offer.id}/edit`"
                                class="text-indigo-600 hover:text-indigo-900 mr-4 rtl:mr-0 rtl:ml-4"
                            >
                                {{ $t('edit') }}
                            </router-link>
                            <button
                                @click="deleteOffer(offer.id)"
                                class="text-red-600 hover:text-red-900"
                            >
                                {{ $t('delete') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
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
        const offers = ref([]);

        const fetchOffers = async () => {
            try {
                const response = await axios.get('/api/offers');
                offers.value = response.data.data;
                console.log('Offers loaded:', offers.value);
                offers.value.forEach(o => {
                    console.log(`Offer ${o.id}: Type=${o.type}, ActionType=${o.actions?.type}, TranslationKey=offer_type_${o.type}`);
                });
            } catch (error) {
                console.error('Error fetching offers:', error);
            }
        };

        const deleteOffer = async (id) => {
            if (!confirm(t('confirm_delete'))) return;
            try {
                await axios.delete(`/api/offers/${id}`);
                fetchOffers();
            } catch (error) {
                console.error('Error deleting offer:', error);
            }
        };

        onMounted(() => {
            fetchOffers();
        });

        return {
            offers,
            deleteOffer
        };
    }
}
</script>
