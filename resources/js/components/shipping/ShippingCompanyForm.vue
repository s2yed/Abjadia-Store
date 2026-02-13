<template>
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            {{ isEditMode ? $t('edit_shipping_company') : $t('add_shipping_company') }}
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
                <label class="block text-sm font-medium text-gray-700">{{ $t('logo') }}</label>
                <div v-if="currentImage" class="mb-2">
                    <img :src="currentImage" class="h-24 w-24 object-cover rounded border">
                </div>
                <input type="file" @change="handleFileChange" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex items-center">
                <input id="is_active" type="checkbox" v-model="form.is_active" class="h-4 w-4 text-secondary-orange focus:ring-secondary-orange border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    {{ $t('active') }}
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <router-link to="/dashboard/shipping-companies" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">{{ $t('cancel') }}</router-link>
                <button type="submit" :disabled="loading" class="px-4 py-2 bg-secondary-orange text-white rounded-md hover:bg-orange-700 disabled:opacity-50">
                    {{ loading ? $t('saving') : (isEditMode ? $t('update') : $t('save')) }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                name: { en: '', ar: '' },
                image: null,
                is_active: true
            },
            currentImage: null,
            loading: false
        }
    },
    computed: {
        isEditMode() {
            return !!this.$route.params.id;
        }
    },
    methods: {
        handleFileChange(e) {
            this.form.image = e.target.files[0];
        },
        async fetchCompany() {
            const id = this.$route.params.id;
            if (!id) return;
            this.loading = true;
            try {
                const { data } = await axios.get(`/api/shipping-companies/${id}`);
                this.form.name = data.name || { en: '', ar: '' };
                this.form.is_active = !!data.is_active;
                this.currentImage = data.logo;
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_load'));
                this.$router.push('/dashboard/shipping-companies');
            } finally {
                this.loading = false;
            }
        },
        async submitForm() {
            this.loading = true;
            const formData = new FormData();
            formData.append('name[en]', this.form.name.en);
            if (this.form.name.ar) formData.append('name[ar]', this.form.name.ar);
            formData.append('is_active', this.form.is_active ? '1' : '0');
            if (this.form.image) formData.append('logo', this.form.image);

            try {
                if (this.isEditMode) {
                    formData.append('_method', 'PUT');
                    await axios.post(`/api/shipping-companies/${this.$route.params.id}`, formData);
                } else {
                    await axios.post('/api/shipping-companies', formData);
                }
                this.$router.push('/dashboard/shipping-companies');
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
            this.fetchCompany();
        }
    }
}
</script>
