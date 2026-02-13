<template>
    <div class="max-w-4xl mx-auto py-6">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $t('site_settings') }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <button @click="saveSettings" :disabled="loading"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-secondary-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange disabled:opacity-50">
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ loading ? $t('saving') : $t('save_changes') }}
                </button>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <!-- Branding Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">{{ $t('branding') }}</h3>
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('site_logo') }}</label>
                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-16 bg-gray-100 rounded overflow-hidden flex items-center justify-center border">
                                    <img v-if="logoPreview || form.logo" :src="logoPreview || form.logo" class="max-h-full max-w-full">
                                    <span v-else class="text-gray-400 text-xs">No Logo</span>
                                </div>
                                <div class="flex-1">
                                    <input type="file" @change="handleFileUpload($event, 'logo')" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('favicon') }}</label>
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 bg-gray-100 rounded overflow-hidden flex items-center justify-center border">
                                    <img v-if="faviconPreview || form.favicon" :src="faviconPreview || form.favicon" class="max-h-full max-w-full">
                                    <span v-else class="text-gray-400 text-xs">No Fav</span>
                                </div>
                                <div class="flex-1">
                                    <input type="file" @change="handleFileUpload($event, 'favicon')" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General SEO Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">{{ $t('seo_settings') }}</h3>
                    <div class="space-y-6">
                        <!-- Arabic SEO -->
                        <div class="bg-gray-50 p-4 rounded-md">
                            <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded mr-2">AR</span>
                                {{ $t('arabic_settings') }}
                            </h4>
                            <div class="grid grid-cols-1 gap-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('site_name') }} (AR)</label>
                                    <input type="text" v-model="form.site_name.ar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm" dir="rtl">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('meta_description') }} (AR)</label>
                                    <textarea v-model="form.site_description.ar" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm" dir="rtl"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- English SEO -->
                        <div class="bg-gray-50 p-4 rounded-md">
                            <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded mr-2">EN</span>
                                {{ $t('english_settings') }}
                            </h4>
                            <div class="grid grid-cols-1 gap-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('site_name') }} (EN)</label>
                                    <input type="text" v-model="form.site_name.en" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $t('meta_description') }} (EN)</label>
                                    <textarea v-model="form.site_description.en" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">{{ $t('contact_information') }}</h3>
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('email') }}</label>
                            <input type="email" v-model="form.contact_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('phone') }}</label>
                            <input type="text" v-model="form.contact_phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('whatsapp') }}</label>
                            <input type="text" v-model="form.whatsapp_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm">
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('currency') }}</label>
                            <input type="text" v-model="form.currency" placeholder="SAR" maxlength="10" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">{{ $t('currency_hint') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $t('free_shipping_threshold') }}</label>
                            <input type="number" step="0.01" v-model="form.free_shipping_threshold" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">{{ $t('free_shipping_threshold_hint') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Social Links Section -->
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">{{ $t('social_links') }}</h3>
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div v-for="social in socialPlatforms" :key="social.id">
                            <label class="block text-sm font-medium text-gray-700 flex items-center">
                                <span class="mr-2 text-gray-400">#</span>
                                {{ $t(social.id) }}
                            </label>
                            <input type="text" v-model="form[social.key]" :placeholder="`https://${social.id}.com/username`" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';

const loading = ref(false);
const logoPreview = ref(null);
const faviconPreview = ref(null);

const form = reactive({
    logo: '',
    favicon: '',
    site_name: { en: '', ar: '' },
    site_description: { en: '', ar: '' },
    site_keywords: { en: '', ar: '' },
    contact_email: '',
    contact_phone: '',
    whatsapp_number: '',
    currency: 'SAR',
    free_shipping_threshold: 0,
    social_facebook: '',
    social_twitter: '',
    social_instagram: '',
    social_linkedin: '',
    social_snapchat: '',
    social_youtube: '',
    logo_file: null,
    favicon_file: null,
});

const socialPlatforms = [
    { id: 'facebook', name: 'Facebook', key: 'social_facebook' },
    { id: 'twitter', name: 'Twitter (X)', key: 'social_twitter' },
    { id: 'instagram', name: 'Instagram', key: 'social_instagram' },
    { id: 'linkedin', name: 'LinkedIn', key: 'social_linkedin' },
    { id: 'snapchat', name: 'Snapchat', key: 'social_snapchat' },
    { id: 'youtube', name: 'YouTube', key: 'social_youtube' },
];

onMounted(async () => {
    fetchSettings();
});

const fetchSettings = async () => {
    try {
        const response = await axios.get('/api/settings');
        Object.keys(form).forEach(key => {
            if (response.data[key] !== undefined) {
                form[key] = response.data[key];
            }
        });
    } catch (error) {
        console.error('Error fetching settings:', error);
    }
};

const handleFileUpload = (event, type) => {
    const file = event.target.files[0];
    if (file) {
        if (type === 'logo') {
            form.logo_file = file;
            logoPreview.value = URL.createObjectURL(file);
        } else {
            form.favicon_file = file;
            faviconPreview.value = URL.createObjectURL(file);
        }
    }
};

const saveSettings = async () => {
    loading.value = true;
    try {
        const formData = new FormData();
        
        // Loop through the form object and append each key-value pair
        Object.keys(form).forEach(key => {
            if (key === 'site_name' || key === 'site_description' || key === 'site_keywords') {
                formData.append(`${key}[en]`, form[key].en || '');
                formData.append(`${key}[ar]`, form[key].ar || '');
            } else if (key === 'logo_file' && form.logo_file) {
                formData.append('logo', form.logo_file);
            } else if (key === 'favicon_file' && form.favicon_file) {
                formData.append('favicon', form.favicon_file);
            } else if (!key.endsWith('_file') && key !== 'logo' && key !== 'favicon') {
                formData.append(key, form[key] || '');
            }
        });

        await axios.post('/api/settings', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        alert('Settings saved successfully!');
        
        // Refresh to update saved URLs
        fetchSettings();
        logoPreview.value = null;
        faviconPreview.value = null;
    } catch (error) {
        console.error('Error saving settings:', error);
        alert(error.response?.data?.message || 'Failed to save settings.');
    } finally {
        loading.value = false;
    }
};
</script>
