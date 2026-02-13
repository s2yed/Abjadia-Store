<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('authors') }}</h2>
            <router-link to="/dashboard/authors/create" class="bg-secondary-orange text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors">
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
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="author in authors" :key="author.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ author.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ getTrans(author.name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium space-x-3 rtl:space-x-reverse">
                            <router-link :to="`/dashboard/authors/${author.id}/edit`" class="text-indigo-600 hover:text-indigo-900">{{ $t('edit') }}</router-link>
                            <button @click="deleteAuthor(author.id)" class="text-red-600 hover:text-red-900">{{ $t('delete') }}</button>
                        </td>
                    </tr>
                    <tr v-if="authors.length === 0">
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">{{ $t('no_data') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { useI18n } from 'vue-i18n';

export default {
    data() {
        return {
            authors: [],
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
        async fetchAuthors() {
            try {
                const { data } = await axios.get('/api/authors');
                this.authors = data;
            } catch (error) {
                console.error("Failed to load authors", error);
            }
        },
        async deleteAuthor(id) {
            if (!confirm(this.$t('confirm_delete'))) return;
            try {
                await axios.delete(`/api/authors/${id}`);
                this.authors = this.authors.filter(a => a.id !== id);
                this.success = this.$t('success_delete');
                setTimeout(() => this.success = null, 3000);
            } catch (error) {
                console.error("Failed to delete author", error);
                alert(this.$t('failed_delete'));
            }
        }
    },
    mounted() {
        this.fetchAuthors();
    }
};
</script>
