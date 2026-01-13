<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('customers') }}</h2>
            <button
                @click="openAddCustomerModal"
                class="bg-secondary-orange text-white px-4 py-2 rounded-md shadow hover:bg-orange-700 transition-colors text-sm font-medium flex items-center"
            >
                <span class="mr-2">+</span> {{ $t('add_new') }}
            </button>
        </div>

        <div v-if="error" class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ error }}
        </div>

        <div v-if="!loading && !error" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('id') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('name') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('email') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('status') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('joined_at') }}</th>
                        <th class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in users" :key="user.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ user.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="{
                                    'bg-green-100 text-green-800': user.status === 'active',
                                    'bg-red-100 text-red-800': user.status !== 'active'
                                }">
                                {{ user.status === 'active' ? $t('active') : $t('inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(user.created_at) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium">
                            <button @click="openEditCustomerModal(user)" class="text-indigo-600 hover:text-indigo-900 mr-4 rtl:mr-0 rtl:ml-4">{{ $t('edit') }}</button>
                            <button @click="deleteCustomer(user.id)" class="text-red-600 hover:text-red-900">{{ $t('delete') }}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="mt-4 flex justify-between items-center text-sm text-gray-500">
            <button @click="fetchCustomers(currentPage - 1)" :disabled="currentPage === 1" class="disabled:opacity-50">{{ $t('previous') }}</button>
            <span>{{ $t('page_of', { current: currentPage, total: totalPages }) }}</span>
            <button @click="fetchCustomers(currentPage + 1)" :disabled="currentPage === totalPages" class="disabled:opacity-50">{{ $t('next') }}</button>
        </div>

        <!-- Add/Edit Customer Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
                <!-- Modal content -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left rtl:text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:mx-4 sm:text-left rtl:sm:text-right w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ isEditing ? $t('edit') : $t('add_new') }}</h3>
                                <div class="mt-4 space-y-4">
                                     <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('name') }}</label>
                                        <input v-model="form.name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('email') }}</label>
                                        <input v-model="form.email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('password') }} <span v-if="isEditing" class="text-xs text-gray-400">({{ $t('leave_blank_password') }})</span></label>
                                        <input v-model="form.password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('status') }}</label>
                                        <select v-model="form.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50">
                                            <option value="active">{{ $t('active') }}</option>
                                            <option value="inactive">{{ $t('inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="saveCustomer" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-secondary-orange text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            {{ isEditing ? $t('save') : $t('add_new') }}
                        </button>
                        <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $t('cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            users: [],
            loading: true,
            error: null,
            currentPage: 1,
            totalPages: 1,
            showModal: false,
            isEditing: false,
            editUserId: null,
            form: {
                name: '',
                email: '',
                password: '',
                role: 'customer',
                status: 'active'
            }
        };
    },
    methods: {
        async fetchCustomers(page = 1) {
            this.loading = true;
            try {
                // Fetch only customers
                const response = await axios.get(`/api/users?role=customer&page=${page}`);
                this.users = response.data.data;
                this.currentPage = response.data.current_page;
                this.totalPages = response.data.last_page;
            } catch (err) {
                this.error = this.$t('failed_load');
                console.error(err);
            } finally {
                this.loading = false;
            }
        },
        openAddCustomerModal() {
            this.isEditing = false;
            this.editUserId = null;
            this.form = { name: '', email: '', password: '', role: 'customer', status: 'active' };
            this.showModal = true;
        },
        openEditCustomerModal(user) {
            this.isEditing = true;
            this.editUserId = user.id;
            this.form = {
                name: user.name,
                email: user.email,
                password: '',
                role: 'customer',
                status: user.status || 'active'
            };
            this.showModal = true;
        },
        async saveCustomer() {
            if (!this.form.name || !this.form.email || (!this.isEditing && !this.form.password)) {
                alert(this.$t('required_fields_error'));
                return;
            }
            try {
                if (this.isEditing) {
                    await axios.put(`/api/users/${this.editUserId}`, this.form);
                    alert(this.$t('customer_updated'));
                } else {
                    await axios.post('/api/users', this.form);
                    alert(this.$t('customer_added'));
                }
                this.showModal = false;
                this.form = { name: '', email: '', password: '', role: 'customer', status: 'active' };
                this.fetchCustomers(this.currentPage);
            } catch (err) {
                console.error(err);
                if (err.response && err.response.data && err.response.data.errors) {
                    const errorMessages = Object.values(err.response.data.errors).flat().join('\n');
                    alert(this.$t('validation_error') + "\n" + errorMessages);
                } else {
                    alert(err.response?.data?.message || this.$t('failed_save_customer'));
                }
            }
        },
        async deleteCustomer(id) {
            if (!confirm(this.$t('confirm_delete'))) return;
            try {
                await axios.delete(`/api/users/${id}`);
                this.fetchCustomers(this.currentPage);
            } catch (err) {
                alert(this.$t('failed_delete_customer'));
                console.error(err);
            }
        },
        formatDate(dateString) {
            if (!dateString) return '-';
            const locale = document.documentElement.lang === 'ar' ? 'ar-SA' : 'en-US';
            return new Date(dateString).toLocaleDateString(locale, {
                year: 'numeric', month: 'long', day: 'numeric'
            });
        }
    },
    mounted() {
        this.fetchCustomers();
    },
};
</script>
