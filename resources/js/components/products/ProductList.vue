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
                                :src="product.image.startsWith('http') ? product.image : '/storage/' + product.image" 
                                class="h-10 w-10 object-cover rounded" 
                            />
                            <span v-else class="text-gray-400">{{ $t('no_image') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ getTrans(product.name) }}
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
                            {{ product.category ? getTrans(product.category.name) : "-" }}
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

        <!-- Pagination -->
        <div
            v-if="totalPages > 1"
            class="mt-6 flex justify-center items-center space-x-2 rtl:space-x-reverse bg-gray-50 p-4 rounded-lg border border-gray-100"
        >
            <button
                @click="fetchProducts(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                &larr;
            </button>
            
            <template v-for="(page, index) in visiblePages" :key="index">
                <span v-if="page === '...'" class="px-3 py-1 text-gray-500">...</span>
                <button
                    v-else
                    @click="fetchProducts(page)"
                    :class="[
                        'px-3 py-1 border rounded text-sm transition-colors',
                        currentPage === page
                            ? 'bg-secondary-orange text-white border-secondary-orange'
                            : 'bg-white border-gray-300 hover:bg-gray-50 text-gray-700'
                    ]"
                >
                    {{ page }}
                </button>
            </template>

            <button
                @click="fetchProducts(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                &rarr;
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted, computed } from "vue";
import { useI18n } from "vue-i18n";

export default {
    setup() {
        const { t, locale } = useI18n();
        const products = ref([]);
        const loading = ref(true);
        const error = ref(null);
        const currentPage = ref(1);
        const totalPages = ref(1);
        const searchQuery = ref("");
        let searchTimeout = null;

        const visiblePages = computed(() => {
            const pages = [];
            const delta = 2;
            const left = currentPage.value - delta;
            const right = currentPage.value + delta + 1;
            const l = [];

            for (let i = 1; i <= totalPages.value; i++) {
                if (i === 1 || i === totalPages.value || (i >= left && i < right)) {
                    l.push(i);
                }
            }

            for (let i of l) {
                if (pages.length > 0) {
                    if (i - pages[pages.length - 1] === 2) {
                        pages.push(pages[pages.length - 1] + 1);
                    } else if (i - pages[pages.length - 1] !== 1) {
                        pages.push('...');
                    }
                }
                pages.push(i);
            }
            return pages;
        });

        const getTrans = (val) => {
            if (!val) return "";
            if (typeof val === 'object') {
                return val[locale.value] || val.en || "";
            }
            return val;
        };

        const fetchProducts = async (page = 1) => {
            loading.value = true;
            try {
                let url = `/api/products?page=${page}`;
                if (searchQuery.value) {
                    url += `&search=${encodeURIComponent(searchQuery.value)}`;
                }
                const response = await axios.get(url);
                products.value = response.data.data;
                currentPage.value = response.data.current_page;
                totalPages.value = response.data.last_page;
            } catch (err) {
                error.value = t('failed_load');
                console.error(err);
            } finally {
                loading.value = false;
                console.log('Products loaded:', products.value);
                console.log('Pagination:', { current: currentPage.value, total: totalPages.value });
                if (products.value.length > 0) {
                     console.log('Sample product type:', products.value[0].type);
                }
            }
        };

        const debouncedSearch = () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage.value = 1;
                fetchProducts(1);
            }, 300);
        };

        const deleteProduct = async (id) => {
            if (!confirm(t('delete_product_confirm')))
                return;

            try {
                await axios.delete(`/api/products/${id}`);
                products.value = products.value.filter((p) => p.id !== id);
            } catch (err) {
                alert(t('failed_delete_product'));
                console.error(err);
            }
        };

        onMounted(() => {
            fetchProducts();
        });

        return {
            products,
            loading,
            error,
            currentPage,
            totalPages,
            searchQuery,
            debouncedSearch,
            deleteProduct,
            fetchProducts,
            getTrans,
            visiblePages
        };
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
