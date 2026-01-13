<template>
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-6">{{ isEditMode ? $t('edit') + ' ' + $t('banners') : $t('add_banner') }}</h2>
        
        <form @submit.prevent="submitForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ $t('position') }}</label>
                <select v-model="form.position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                    <option value="hero_image">{{ $t('hero_side_image') }}</option>
                    <option value="separator_1">{{ $t('separator_1') }}</option>
                    <option value="separator_2">{{ $t('separator_2') }}</option>
                    <option value="separator_3">{{ $t('separator_3') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ $t('image') }}</label>
                <div v-if="currentImage" class="mb-2">
                    <img :src="currentImage" class="h-20 object-cover rounded" :alt="$t('banners')">
                    <p class="text-xs text-gray-500 mt-1">{{ $t('keep_current_image_help') }}</p>
                </div>
                <input type="file" @change="handleFileChange" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('name_en') }}</label>
                    <input type="text" v-model="form.title.en" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 text-right" dir="rtl">{{ $t('name_ar') }}</label>
                    <input type="text" v-model="form.title.ar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right p-2 border" dir="rtl">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ $t('link_url') }}</label>
                <input type="url" v-model="form.link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ $t('order') }}</label>
                <input type="number" v-model="form.order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('desc_en') }}</label>
                    <textarea v-model="form.description.en" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 text-right" dir="rtl">{{ $t('desc_ar') }}</label>
                    <textarea v-model="form.description.ar" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right p-2 border" dir="rtl"></textarea>
                </div>
            </div>

            <div class="flex items-center rtl:space-x-reverse">
                <input type="checkbox" v-model="form.is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label class="ml-2 rtl:mr-2 block text-sm text-gray-900">{{ $t('active') }}</label>
            </div>

            <div class="flex justify-end gap-3 rtl:space-x-reverse">
                <router-link to="/dashboard/banners" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">{{ $t('cancel') }}</router-link>
                <button type="submit" :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
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
                position: 'separator_1',
                title: { en: '', ar: '' },
                link: '',
                order: 0,
                is_active: true,
                image: null,
                description: { en: '', ar: '' }
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
        async fetchBanner() {
            const id = this.$route.params.id;
            if (!id) return;

            this.loading = true;
            try {
                const response = await axios.get(`/api/banners/${id}`);
                const banner = response.data;
                this.form.position = banner.position;
                this.form.title = banner.title || { en: '', ar: '' };
                this.form.link = banner.link;
                this.form.order = banner.order;
                this.form.is_active = !!banner.is_active;
                this.form.description = banner.description || { en: '', ar: '' };
                this.currentImage = banner.image_path;
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_load_banner'));
                this.$router.push('/dashboard/banners');
            } finally {
                this.loading = false;
            }
        },
        async submitForm() {
            if (!this.isEditMode && !this.form.image) {
                alert(this.$t('select_image'));
                return;
            }

            this.loading = true;
            const formData = new FormData();
            formData.append('position', this.form.position);
            
            if (this.form.title.en) formData.append('title[en]', this.form.title.en);
            if (this.form.title.ar) formData.append('title[ar]', this.form.title.ar);
            
            if (this.form.description.en) formData.append('description[en]', this.form.description.en);
            if (this.form.description.ar) formData.append('description[ar]', this.form.description.ar);

            formData.append('link', this.form.link || '');
            formData.append('order', this.form.order);
            formData.append('is_active', this.form.is_active ? 1 : 0);
            
            if (this.form.image) {
                formData.append('image', this.form.image);
            }

            try {
                if (this.isEditMode) {
                    // Method spoofing for Laravel PUT with files
                    formData.append('_method', 'PUT'); 
                    await axios.post(`/api/banners/${this.$route.params.id}`, formData);
                } else {
                    await axios.post('/api/banners', formData);
                }
                this.$router.push('/dashboard/banners');
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_save_banner'));
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {
        if (this.isEditMode) {
            this.fetchBanner();
        }
    }
}
</script>
