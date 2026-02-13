<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('shipping_zones') }}</h2>
            <router-link to="/dashboard/shipping-zones/create" class="bg-secondary-orange text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors">
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
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('name') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('coverage_areas') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('status') }}</th>
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="zone in zones" :key="zone.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ zone.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ getTrans(zone.name) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <span v-for="(area, index) in (zone.coverage_areas || [])" :key="index" class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-600 mr-2 mb-1">
                                {{ area }}
                            </span>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm">
                             <span :class="`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${zone.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`">
                                {{ zone.is_active ? $t('active') : $t('inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium space-x-3 rtl:space-x-reverse">
                            <router-link :to="`/dashboard/shipping-zones/${zone.id}/edit`" class="text-indigo-600 hover:text-indigo-900">{{ $t('edit') }}</router-link>
                            <button @click="deleteZone(zone.id)" class="text-red-600 hover:text-red-900">{{ $t('delete') }}</button>
                        </td>
                    </tr>
                    <tr v-if="zones.length === 0">
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
            zones: [],
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
        async fetchZones() {
            try {
                const { data } = await axios.get('/api/shipping-zones');
                this.zones = data;
            } catch (error) {
                console.error("Failed to load zones", error);
            }
        },
        async deleteZone(id) {
            if (!confirm(this.$t('confirm_delete'))) return;
            try {
                await axios.delete(`/api/shipping-zones/${id}`);
                this.zones = this.zones.filter(z => z.id !== id);
                this.success = this.$t('success_delete');
                setTimeout(() => this.success = null, 3000);
            } catch (error) {
                console.error("Failed to delete zone", error);
                alert(this.$t('failed_delete'));
            }
        }
    },
    mounted() {
        this.fetchZones();
    }
};
</script>
