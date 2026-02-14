<template>
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            {{ isEditMode ? $t('edit_publisher') : $t('add_publisher') }}
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
                <label class="block text-sm font-medium text-gray-700">{{ $t('image') }}</label>
                <div v-if="currentImage" class="mb-2">
                    <img :src="currentImage.startsWith('http') || currentImage.startsWith('/storage') || currentImage.startsWith('storage') ? (currentImage.startsWith('http') || currentImage.startsWith('/') ? currentImage : '/' + currentImage) : '/storage/' + currentImage" class="h-24 w-24 object-cover rounded border">
                </div>
                <input type="file" @change="handleFileChange" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $t('bio_en') }}</label>
                    <textarea v-model="form.bio.en" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange p-2 border"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 rtl:text-right" dir="auto">{{ $t('bio_ar') }}</label>
                    <textarea v-model="form.bio.ar" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-orange focus:ring-secondary-orange p-2 border rtl:text-right" dir="rtl"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <router-link to="/dashboard/publishers" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">{{ $t('cancel') }}</router-link>
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
                bio: { en: '', ar: '' },
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
        async fetchPublisher() {
            const id = this.$route.params.id;
            if (!id) return;
            this.loading = true;
            try {
                const { data } = await axios.get(`/api/publishers/${id}`);
                this.form.name = data.name || { en: '', ar: '' };
                this.form.bio = data.bio || { en: '', ar: '' };
                this.currentImage = data.image;
            } catch (err) {
                console.error(err);
                alert(this.$t('failed_load'));
                this.$router.push('/dashboard/publishers');
            } finally {
                this.loading = false;
            }
        },
        async submitForm() {
            this.loading = true;
            const formData = new FormData();
            formData.append('name[en]', this.form.name.en);
            if (this.form.name.ar) formData.append('name[ar]', this.form.name.ar);
            if (this.form.bio.en) formData.append('bio[en]', this.form.bio.en);
            if (this.form.bio.ar) formData.append('bio[ar]', this.form.bio.ar);
            if (this.form.image) formData.append('image', this.form.image);

            try {
                if (this.isEditMode) {
                    formData.append('_method', 'PUT');
                    await axios.post(`/api/publishers/${this.$route.params.id}`, formData);
                } else {
                    await axios.post('/api/publishers', formData);
                }
                this.$router.push('/dashboard/publishers');
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
            this.fetchPublisher();
        }
    }
}
</script>

<style scoped>
.bg-secondary-orange { background-color: #e67e22; }
</style>
