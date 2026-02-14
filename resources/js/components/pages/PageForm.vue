<template>
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ isEdit ? 'Edit Page' : 'Create New Page' }}
            </h2>
        </div>

        <form @submit.prevent="savePage" class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Arabic Title -->
                <div class="bg-gray-50 p-4 rounded-md">
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded mr-2">AR</span>
                        Arabic Content
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                            <input type="text" v-model="form.title.ar" required dir="rtl"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Content (AR)</label>
                            <textarea v-model="form.content.ar" rows="8" required dir="rtl"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Description (AR)</label>
                            <textarea v-model="form.meta_description.ar" rows="2" dir="rtl"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange"></textarea>
                        </div>
                    </div>
                </div>

                <!-- English Title -->
                <div class="bg-gray-50 p-4 rounded-md">
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded mr-2">EN</span>
                        English Content
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                            <input type="text" v-model="form.title.en" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Content (EN)</label>
                            <textarea v-model="form.content.en" rows="8" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Description (EN)</label>
                            <textarea v-model="form.meta_description.en" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Page Image -->
                <div class="bg-gray-50 p-4 rounded-md">
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                        Feature Image
                    </h4>
                    <div class="space-y-4">
                        <div v-if="previewImage" class="mb-2">
                             <img :src="previewImage" class="h-48 w-full object-cover rounded-lg border shadow-sm" />
                        </div>
                        <input
                            type="file"
                            @change="handleImageUpload"
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100"
                        />
                    </div>
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" v-model="form.slug" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange">
                    <p class="mt-1 text-sm text-gray-500">URL-friendly version (e.g., about-us)</p>
                </div>

                <!-- Is Active -->
                <div class="flex items-center">
                    <input type="checkbox" v-model="form.is_active" id="is_active"
                        class="h-4 w-4 text-secondary-orange focus:ring-secondary-orange border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (visible on website)
                    </label>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                <router-link to="/dashboard/pages"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </router-link>
                <button type="submit" :disabled="loading"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary-orange hover:bg-orange-600 disabled:opacity-50">
                    {{ loading ? 'Saving...' : 'Save Page' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const loading = ref(false);
const isEdit = ref(false);
const previewImage = ref(null);

const form = reactive({
    title: { ar: '', en: '' },
    slug: '',
    content: { ar: '', en: '' },
    meta_description: { ar: '', en: '' },
    is_active: true,
    image: null
});

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const fetchPage = async (id) => {
    try {
        const response = await axios.get(`/api/pages/${id}`);
        const data = response.data;
        
        // Helper to safely get translated values
        const getTrans = (val) => {
            if (!val) return { ar: '', en: '' };
            if (typeof val === 'object') return { ar: val.ar || '', en: val.en || '' };
            return { ar: val, en: '' }; // Fallback for string
        };

        form.title = getTrans(data.title);
        form.content = getTrans(data.content);
        form.meta_description = getTrans(data.meta_description);
        form.slug = data.slug || '';
        form.is_active = !!data.is_active;

        if (data.image_path) {
            previewImage.value = data.image_path.startsWith('http') || data.image_path.startsWith('/storage') || data.image_path.startsWith('storage') ? (data.image_path.startsWith('http') || data.image_path.startsWith('/') ? data.image_path : '/' + data.image_path) : `/storage/${data.image_path}`;
        }
    } catch (error) {
        console.error('Error fetching page:', error);
    }
};

const savePage = async () => {
    loading.value = true;
    try {
        const formData = new FormData();
        
        // Handle translations
        formData.append('title[ar]', form.title.ar);
        formData.append('title[en]', form.title.en);
        formData.append('content[ar]', form.content.ar);
        formData.append('content[en]', form.content.en);
        formData.append('meta_description[ar]', form.meta_description.ar);
        formData.append('meta_description[en]', form.meta_description.en);
        
        // Regular fields
        formData.append('slug', form.slug);
        formData.append('is_active', form.is_active ? 1 : 0);
        
        if (form.image instanceof File) {
            formData.append('image', form.image);
        }

        if (isEdit.value) {
            formData.append('_method', 'PUT');
            await axios.post(`/api/pages/${route.params.id}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            await axios.post('/api/pages', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        }
        router.push('/dashboard/pages');
    } catch (error) {
        console.error('Error saving page:', error);
        if (error.response && error.response.data && error.response.data.errors) {
            const errors = error.response.data.errors;
            let message = 'Validation errors:\n';
            Object.keys(errors).forEach(key => {
                message += `${key}: ${errors[key].join(', ')}\n`;
            });
            alert(message);
        } else {
            alert('Failed to save page');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    if (route.params.id) {
        isEdit.value = true;
        fetchPage(route.params.id);
    }
});
</script>
