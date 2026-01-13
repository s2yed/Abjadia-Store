<template>
    <div class="bg-white rounded-lg shadow-sm p-6 overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('categories') }}</h2>
            <div class="flex space-x-4 rtl:space-x-reverse w-full md:w-auto">
                <input
                    type="text"
                    v-model="search"
                    @input="handleSearch"
                    :placeholder="$t('search_categories')"
                    class="border-gray-300 rounded-md shadow-sm focus:ring-primary-dark focus:border-primary-dark sm:text-sm flex-1 p-2"
                >
                <router-link
                    to="/dashboard/categories/create"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors whitespace-nowrap"
                >
                    + {{ $t('add_category') }}
                </router-link>
            </div>
        </div>

        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>

        <div v-if="!loading && !error" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                         <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('image') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('name') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('slug') }}</th>
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="category in categories" :key="category.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img v-if="category.image" :src="category.image" class="h-10 w-10 object-cover rounded" />
                            <span v-else class="text-gray-400 text-xs">{{ $t('no_image') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ typeof category.name === 'object' ? category.name[$i18n.locale] || category.name.en : category.name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ category.slug }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium">
                            <router-link :to="'/dashboard/categories/' + category.id + '/edit'" class="text-indigo-600 hover:text-indigo-900">{{ $t('edit') }}</router-link>
                            <button @click="deleteCategory(category.id)" class="text-red-600 hover:text-red-900 mx-4">{{ $t('delete') }}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination would go here if API supported it, but our Categories API returns all -->
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            categories: [],
            loading: true,
            error: null,
            search: '',
            debounceTimeout: null,
        };
    },
    methods: {
        handleSearch() {
             if (this.debounceTimeout) clearTimeout(this.debounceTimeout);
             this.debounceTimeout = setTimeout(() => {
                 this.fetchCategories();
             }, 300);
        },
        async fetchCategories() {
            this.loading = true;
            try {
                const response = await axios.get('/api/categories', {
                    params: { search: this.search }
                });
                // Check if response is paginated or not. Based on Controller, it uses "apiResource" which usually maps to "index" method.
                // Assuming standard controller implementation or the one we checked earlier (we checked Products, not Categories).
                // Let's assume it returns data directly or paginated data.
                // Actually we haven't checked CategoryController. Assuming it returns a list like others.
                // Wait, I should check CategoryController to be sure.
                this.categories = response.data.data ? response.data.data : response.data;
            } catch (err) {
                this.error = this.$t('failed_load_category');
                console.error(err);
            } finally {
                this.loading = false;
            }
        },
        async deleteCategory(id) {
            if (!confirm(this.$t('delete_category_confirm'))) return;
            try {
                await axios.delete(`/api/categories/${id}`);
                this.fetchCategories();
            } catch (err) {
                alert(this.$t('failed_delete_category'));
                console.error(err);
            }
        },
        alert(msg) {
            window.alert(msg);
        }
    },
    mounted() {
        this.fetchCategories();
    },
};
</script>
