<template>
    <div class="bg-white rounded-lg shadow-sm p-6 overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('banners') }}</h2>
            <div class="flex space-x-4 rtl:space-x-reverse w-full md:w-auto">
                <input
                    type="text"
                    v-model="search"
                    @input="handleSearch"
                    :placeholder="$t('search_banners')"
                    class="border-gray-300 rounded-md shadow-sm focus:ring-primary-dark focus:border-primary-dark sm:text-sm flex-1 p-2"
                >
                <router-link
                    to="/dashboard/banners/create"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors whitespace-nowrap"
                >
                    + {{ $t('add_banner') }}
                </router-link>
            </div>
        </div>

        <div v-if="loading" class="text-center py-8 text-gray-500">
            {{ $t('loading_banners') }}
        </div>

        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>

        <div v-if="!loading && !error" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ $t('image') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ $t('name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ $t('position') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ $t('order') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ $t('status') }}
                        </th>
                        <th
                            class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ $t('actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="banner in banners" :key="banner.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img
                                :src="banner.image_path.startsWith('/') || banner.image_path.startsWith('http') ? banner.image_path : '/' + banner.image_path"
                                class="h-12 w-20 object-cover rounded"
                                alt="Banner"
                            />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ typeof banner.title === 'object' ? banner.title[$i18n.locale] || banner.title.en : (banner.title || "-") }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"
                            >
                                {{ banner.position }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ banner.order }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="
                                    banner.is_active
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'
                                "
                            >
                                {{ banner.is_active ? $t('active') : $t('inactive') }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium"
                        >
                            <router-link
                                :to="'/dashboard/banners/' + banner.id + '/edit'"
                                class="text-indigo-600 hover:text-indigo-900"
                            >
                                {{ $t('edit') }}
                            </router-link>
                            <button
                                @click="deleteBanner(banner.id)"
                                class="text-red-600 hover:text-red-900 mx-4"
                            >
                                {{ $t('delete') }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            banners: [],
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
                 this.fetchBanners();
             }, 300);
        },
        async fetchBanners() {
            this.loading = true;
            try {
                const response = await axios.get("/api/banners", {
                    params: { search: this.search }
                });
                this.banners = response.data;
            } catch (err) {
                this.error = this.$t('failed_load_banner');
                console.error(err);
            } finally {
                this.loading = false;
            }
        },
        async deleteBanner(id) {
            if (!confirm(this.$t('delete_banner_confirm'))) return;
            try {
                await axios.delete(`/api/banners/${id}`);
                this.banners = this.banners.filter((b) => b.id !== id);
            } catch (err) {
                alert(this.$t('failed_delete_banner'));
            }
        },
    },
    mounted() {
        this.fetchBanners();
    },
};
</script>

<style scoped>
.bg-primary-dark {
    background-color: #2c3e50;
}
</style>
