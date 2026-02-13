<template>
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            {{ isEditMode ? $t('edit_shipping_zone') : $t('add_shipping_zone') }}
        </h2>
        
        <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('name_en') }}</label>
                    <input type="text" v-model="form.name.en" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 rtl:text-right" dir="auto">{{ $t('name_ar') }}</label>
                    <input type="text" v-model="form.name.ar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange p-2 border rtl:text-right" dir="rtl">
                </div>
            </div>

            <div>
                 <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('cities_list') }}</label>
                 <div class="mt-1 flex flex-wrap gap-2 p-2 border rounded-md border-gray-300 focus-within:ring-2 focus-within:ring-secondary-orange focus-within:border-secondary-orange bg-white min-h-[42px]">
                     <div v-for="(city, index) in form.coverage_areas" :key="index" class="inline-flex items-center bg-orange-100 text-orange-800 text-xs font-bold px-2.5 py-1 rounded-full group">
                         {{ city }}
                         <button type="button" @click="removeCity(index)" class="ml-1.5 inline-flex items-center p-0.5 rounded-full text-orange-400 hover:bg-orange-200 hover:text-orange-500 transition-colors">
                             <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                 <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                             </svg>
                         </button>
                     </div>
                     <input 
                        type="text" 
                        v-model="currentCity" 
                        @keydown.enter.prevent="addCity" 
                        @keydown.comma.prevent="addCity"
                        @keydown.delete="handleDelete"
                        class="flex-1 outline-none border-none focus:ring-0 p-0 text-sm min-w-[120px]" 
                        :placeholder="$t('add_city_hint')"
                     >
                     <button 
                        type="button" 
                        @click="addCity" 
                        :disabled="!currentCity.trim()"
                        class="ml-auto inline-flex items-center px-2 py-1 border border-transparent text-xs font-bold rounded text-secondary-orange bg-orange-50 hover:bg-orange-100 focus:outline-none transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                     >
                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ $t('add') }}
                     </button>
                 </div>
                 <p class="text-xs text-gray-500 mt-2">{{ $t('tags_input_hint') }}</p>
            </div>

            <div class="flex items-center">
                <input id="is_active" type="checkbox" v-model="form.is_active" class="h-4 w-4 text-secondary-orange focus:ring-secondary-orange border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    {{ $t('active') }}
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <router-link to="/dashboard/shipping-zones" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">{{ $t('cancel') }}</router-link>
                <button type="submit" :disabled="loading" class="px-4 py-2 bg-secondary-orange text-white rounded-md hover:bg-orange-700 disabled:opacity-50">
                    {{ loading ? $t('saving') : (isEditMode ? $t('update') : $t('save')) }}
                </button>
            </div>
        </form>

        <ShippingRateManager v-if="isEditMode" :zoneId="$route.params.id" />
    </div>
</template>

<script>
import axios from 'axios';
import ShippingRateManager from './ShippingRateManager.vue';

export default {
    components: {
        ShippingRateManager
    },
    data() {
        return {
            form: {
                name: { en: '', ar: '' },
                coverage_areas: [],
                is_active: true
            },
            currentCity: '',
            loading: false
        }
    },
    computed: {
        isEditMode() {
            return !!this.$route.params.id;
        }
    },
    methods: {
        addCity() {
            const city = this.currentCity.trim();
            if (city && !this.form.coverage_areas.includes(city)) {
                this.form.coverage_areas.push(city);
            }
            this.currentCity = '';
        },
        removeCity(index) {
            this.form.coverage_areas.splice(index, 1);
        },
        handleDelete(e) {
            // Delete last tag if input is empty and delete key is pressed
            if (!this.currentCity && this.form.coverage_areas.length > 0) {
                this.form.coverage_areas.pop();
            }
        },
        async fetchZone() {
            const id = this.$route.params.id;
            if (!id) return;
            this.loading = true;
            try {
                const { data } = await axios.get(`/api/shipping-zones/${id}`);
                this.form.name = data.name || { en: '', ar: '' };
                this.form.is_active = !!data.is_active;
                this.form.coverage_areas = data.coverage_areas || [];
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_load'));
                this.$router.push('/dashboard/shipping-zones');
            } finally {
                this.loading = false;
            }
        },
        async submitForm() {
            this.loading = true;
            
            // Add current typing if any before submitting
            if (this.currentCity.trim()) {
                this.addCity();
            }

            try {
                if (this.isEditMode) {
                    await axios.put(`/api/shipping-zones/${this.$route.params.id}`, this.form);
                } else {
                    await axios.post('/api/shipping-zones', this.form);
                }
                this.$router.push('/dashboard/shipping-zones');
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_save'));
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {
        if (this.isEditMode) {
            this.fetchZone();
        }
    }
}
</script>
