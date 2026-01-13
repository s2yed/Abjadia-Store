<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('products') }}</h2>
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <input 
                    v-model="searchQuery" 
                    @input="debouncedSearch"
                    type="text" 
                    :placeholder="$t('search_products')" 
                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-secondary-orange rtl:text-right"
                >
                <router-link
                    to="/dashboard/products/create"
                    class="bg-primary-dark text-white px-4 py-2 rounded hover:bg-gray-800 transition-colors"
                >
                    + {{ $t('add_product') }}
                </router-link>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-8 text-gray-500">
            {{ $t('loading_products') }}
        </div>

        <!-- Error State -->
        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>

        <!-- Products Table -->
        <div v-if="!loading && !error" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('image') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('name') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('type') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('category') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('price') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('stock') }}</th>
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="product in products" :key="product.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img 
                                v-if="product.image" 
                                :src="product.image.startsWith('http') ? product.image : (product.image.startsWith('images/') ? '/' + product.image : '/storage/' + product.image)" 
                                class="h-10 w-10 object-cover rounded" 
                            />
                            <span v-else class="text-gray-400">{{ $t('no_image') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ typeof product.name === 'object' ? product.name[$i18n.locale] || product.name.en : product.name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ product.slug }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="
                                    product.type === 'book'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-green-100 text-green-800'
                                "
                            >
                                {{ $t(product.type) }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ product.category ? (typeof product.category.name === 'object' ? product.category.name[$i18n.locale] || product.category.name.en : product.category.name) : "-" }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ product.price }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ product.stock }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium"
                        >
                            <router-link
                                :to="`/dashboard/products/${product.id}/edit`"
                                class="text-secondary-orange hover:text-orange-700 mr-3 rtl:mr-0 rtl:ml-3"
                                >{{ $t('edit') }}</router-link
                            >
                            <button
                                @click="deleteProduct(product.id)"
                                class="text-red-600 hover:text-red-900"
                            >
                                {{ $t('delete') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination (Placeholder) -->
        <div
            v-if="totalPages > 1"
            class="mt-4 flex justify-between items-center text-sm text-gray-500"
        >
            <button
                @click="fetchProducts(currentPage - 1)"
                :disabled="currentPage === 1"
                class="disabled:opacity-50"
            >
                {{ $t('previous') }}
            </button>
            <span>{{ $t('page_of', { current: currentPage, total: totalPages }) }}</span>
            <button
                @click="fetchProducts(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="disabled:opacity-50"
            >
                {{ $t('next') }}
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            products: [],
            loading: true,
            error: null,
            currentPage: 1,
            totalPages: 1,
            searchQuery: "",
            searchTimeout: null,
        };
    },
    methods: {
        async fetchProducts(page = 1) {
            this.loading = true;
            try {
                let url = `/api/products?page=${page}`;
                if (this.searchQuery) {
                    url += `&search=${encodeURIComponent(this.searchQuery)}`;
                }
                const response = await axios.get(url);
                this.products = response.data.data;
                this.currentPage = response.data.current_page;
                this.totalPages = response.data.last_page;
            } catch (err) {
                this.error = this.$t('failed_load');
                console.error(err);
            } finally {
                this.loading = false;
            }
        },
        debouncedSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
                this.fetchProducts(1);
            }, 300);
        },
        async deleteProduct(id) {
            if (!confirm(this.$t('delete_product_confirm')))
                return;

            try {
                await axios.delete(`/api/products/${id}`);
                this.products = this.products.filter((p) => p.id !== id);
                // Re-fetch if needed or just remove from list
            } catch (err) {
                alert(this.$t('failed_delete_product'));
                console.error(err);
            }
        },
    },
    mounted() {
        this.fetchProducts();
    },
};
</script>

<style scoped>
.bg-primary-dark {
    background-color: #2c3e50;
}
.text-secondary-orange {
    color: #e67e22;
}
</style>
