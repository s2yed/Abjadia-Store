<template>
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-6">{{ isEditMode ? $t('edit') + ' ' + $t('categories') : $t('add_category') }}</h2>
        
        <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('name_en') }}</label>
                    <input type="text" v-model="form.name.en" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 text-right" dir="rtl">{{ $t('name_ar') }}</label>
                    <input type="text" v-model="form.name.ar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right p-2 border" dir="rtl">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ $t('slug') }}</label>
                <input type="text" v-model="form.slug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
                <p class="text-xs text-gray-500 mt-1">{{ $t('slug_help') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">{{ $t('image') }}</label>
                <div v-if="currentImage" class="mb-2">
                    <img :src="currentImage" class="h-20 object-cover rounded" :alt="$t('current_image')">
                    <p class="text-xs text-gray-500 mt-1">{{ $t('keep_current_image_help') }}</p>
                </div>
                <input type="file" @change="handleFileChange" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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

            <div class="flex justify-end gap-3 rtl:space-x-reverse">
                <router-link to="/dashboard/categories" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">{{ $t('cancel') }}</router-link>
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
                name: { en: '', ar: '' },
                slug: '',
                description: { en: '', ar: '' },
                image: null
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
        async fetchCategory() {
            const id = this.$route.params.id;
            if (!id) return;

            this.loading = true;
            try {
                const response = await axios.get(`/api/categories/${id}`);
                const category = response.data;
                // Backend returns { en: '...', ar: '...' } for name/description now
                this.form.name = category.name || { en: '', ar: '' };
                this.form.slug = category.slug;
                this.form.description = category.description || { en: '', ar: '' };
                this.currentImage = category.image;
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_load_category'));
                this.$router.push('/dashboard/categories');
            } finally {
                this.loading = false;
            }
        },
        async submitForm() {
            this.loading = true;
            const formData = new FormData();
            
            formData.append('name[en]', this.form.name.en);
            if (this.form.name.ar) formData.append('name[ar]', this.form.name.ar);
            
            if (this.form.slug) formData.append('slug', this.form.slug);
            
            if (this.form.description.en) formData.append('description[en]', this.form.description.en);
            if (this.form.description.ar) formData.append('description[ar]', this.form.description.ar);
            
            if (this.form.image) {
                formData.append('image', this.form.image);
            }

            try {
                if (this.isEditMode) {
                    formData.append('_method', 'PUT');
                    await axios.post(`/api/categories/${this.$route.params.id}`, formData);
                } else {
                    await axios.post('/api/categories', formData);
                }
                this.$router.push('/dashboard/categories');
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_save_category'));
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {
        if (this.isEditMode) {
            this.fetchCategory();
        }
    }
}
</script>
