<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('shipping_companies') }}</h2>
            <router-link to="/dashboard/shipping-companies/create" class="bg-secondary-orange text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors">
                + {{ $t('add_new') }}
            </router-link>
        </div>

        <!-- Success Message -->
        <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ success }}
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('id') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('logo') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('name') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('status') }}</th>
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="company in companies" :key="company.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ company.id }}</td>
                         <td class="px-6 py-4 whitespace-nowrap">
                            <img v-if="company.logo" :src="company.logo" class="h-10 w-10 rounded-full object-cover border" alt="">
                            <span v-else class="text-gray-400">-</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ getTrans(company.name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                             <span :class="`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${company.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`">
                                {{ company.is_active ? $t('active') : $t('inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium space-x-3 rtl:space-x-reverse">
                            <router-link :to="`/dashboard/shipping-companies/${company.id}/edit`" class="text-indigo-600 hover:text-indigo-900">{{ $t('edit') }}</router-link>
                            <button @click="deleteCompany(company.id)" class="text-red-600 hover:text-red-900">{{ $t('delete') }}</button>
                        </td>
                    </tr>
                    <tr v-if="companies.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">{{ $t('no_data') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            companies: [],
            success: null
        };
    },
    methods: {
        getTrans(val) {
            if (!val) return "";
            if (typeof val === 'object') {
                return val[this.$i18n.locale] || val.en || "";
            }
            return val;
        },
        async fetchCompanies() {
            try {
                const { data } = await axios.get('/api/shipping-companies');
                this.companies = data;
            } catch (error) {
                console.error("Failed to load companies", error);
            }
        },
        async deleteCompany(id) {
            if (!confirm(this.$t('confirm_delete'))) return;
            try {
                await axios.delete(`/api/shipping-companies/${id}`);
                this.companies = this.companies.filter(c => c.id !== id);
                this.success = this.$t('success_delete');
                setTimeout(() => this.success = null, 3000);
            } catch (error) {
                console.error("Failed to delete company", error);
                alert(this.$t('failed_delete'));
            }
        }
    },
    mounted() {
        this.fetchCompanies();
    }
};
</script>
