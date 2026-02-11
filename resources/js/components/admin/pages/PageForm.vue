<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ isEditing ? 'Edit Page' : 'Create New Page' }}</h1>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="slug">
                        Slug (URL)
                    </label>
                    <input v-model="form.slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="slug" type="text" placeholder="e.g. about-us">
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title_en">
                            Title (English)
                        </label>
                        <input v-model="form.title.en" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title_en" type="text" placeholder="Page Title">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title_ar">
                            Title (Arabic)
                        </label>
                        <input v-model="form.title.ar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-right" id="title_ar" type="text" placeholder="عنوان الصفحة">
                    </div>
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="content_en">
                            Content (English)
                        </label>
                        <textarea v-model="form.content.en" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-64" id="content_en" placeholder="Page Content (HTML supported)"></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="content_ar">
                            Content (Arabic)
                        </label>
                        <textarea v-model="form.content.ar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-64 text-right" id="content_ar" placeholder="محتوى الصفحة (يدعم HTML)"></textarea>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" v-model="form.is_active" class="form-checkbox h-5 w-5 text-indigo-600">
                        <span class="ml-2 text-gray-700">Active</span>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        {{ isEditing ? 'Update Page' : 'Create Page' }}
                    </button>
                    <router-link :to="{ name: 'Pages' }" class="inline-block align-baseline font-bold text-sm text-indigo-600 hover:text-indigo-800">
                        Cancel
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                slug: '',
                title: { en: '', ar: '' },
                content: { en: '', ar: '' },
                is_active: true
            },
            isEditing: false
        }
    },
    async mounted() {
        if (this.$route.params.id) {
            this.isEditing = true;
            await this.fetchPage(this.$route.params.id);
        }
    },
    methods: {
        async fetchPage(id) {
            try {
                const response = await axios.get(`/api/pages/${id}`);
                this.form = response.data;
                // Ensure translatable fields are objects
                if (!this.form.title) this.form.title = { en: '', ar: '' };
                if (!this.form.content) this.form.content = { en: '', ar: '' };
            } catch (error) {
                console.error('Error fetching page:', error);
            }
        },
        async submitForm() {
            try {
                if (this.isEditing) {
                    await axios.put(`/api/pages/${this.$route.params.id}`, this.form);
                } else {
                    await axios.post('/api/pages', this.form);
                }
                this.$router.push({ name: 'Pages' });
            } catch (error) {
                console.error('Error saving page:', error);
                alert('An error occurred while saving the page.');
            }
        }
    }
}
</script>
