<template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Pages
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <router-link to="/dashboard/pages/create"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-secondary-orange hover:bg-orange-600">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Page
                </router-link>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <li v-for="page in pages" :key="page.id" class="hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ page.slug }}
                            </h3>
                        </div>
                        <div class="flex items-center space-x-3">
                             <router-link :to="`/dashboard/pages/${page.id}/edit`" class="text-secondary-orange">Edit</router-link>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const pages = ref([]);
const router = useRouter();

const fetchPages = async () => {
    try {
        const response = await axios.get('/api/pages');
        pages.value = response.data;
    } catch (error) {
        console.error('Error fetching pages:', error);
    }
};

const getPageTitle = (page) => {
    if (!page || !page.title) return 'Untitled';
    if (typeof page.title === 'object') {
        return page.title.ar || page.title.en || 'Untitled';
    }
    return page.title;
};

const deletePage = async (id) => {
    if (!confirm('Are you sure you want to delete this page?')) return;
    
    try {
        await axios.delete(`/api/pages/${id}`);
        await fetchPages();
    } catch (error) {
        console.error('Error deleting page:', error);
        alert('Failed to delete page');
    }
};

onMounted(() => {
    fetchPages();
});
</script>
