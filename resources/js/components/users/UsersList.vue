<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ $t('users') }}</h2>
            <button
                v-if="isAdmin"
                @click="openAddUserModal"
                class="bg-secondary-orange text-white px-4 py-2 rounded-md shadow hover:bg-orange-700 transition-colors text-sm font-medium flex items-center"
            >
                <span class="mr-2 rtl:mr-0 rtl:ml-2">+</span> {{ $t('add_user') }}
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
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('role') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('joined_at') }}</th>
                        <th v-if="isAdmin" class="px-6 py-3 text-right rtl:text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in users" :key="user.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ user.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                                :class="{
                                    'bg-purple-100 text-purple-800': user.id === 1,
                                    'bg-green-100 text-green-800': user.role === 'admin' && user.id !== 1,
                                    'bg-blue-100 text-blue-800': user.role === 'customer',
                                    'bg-gray-100 text-gray-800': !['admin', 'customer'].includes(user.role)
                                }">
                                {{ user.id === 1 ? $t('super_admin') : $t(user.role || 'customer') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(user.created_at) }}</td>
                        <td v-if="isAdmin" class="px-6 py-4 whitespace-nowrap text-right rtl:text-left text-sm font-medium">
                            <button @click="openEditUserModal(user)" class="text-indigo-600 hover:text-indigo-900 mx-2">{{ $t('edit') }}</button>
                            <button v-if="user.id !== 1" @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900 mx-2">{{ $t('delete') }}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 0" class="mt-6 flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-100">
            <button @click="fetchUsers(currentPage - 1)" :disabled="currentPage === 1" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">&larr; {{ $t('previous') }}</button>
            <span class="text-sm font-medium text-gray-700">{{ $t('page') }} {{ currentPage }} {{ $t('of') }} {{ totalPages }}</span>
            <button @click="fetchUsers(currentPage + 1)" :disabled="currentPage === totalPages" class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">{{ $t('next') }} &rarr;</button>
        </div>

        <!-- Add User Modal -->
        <div v-if="showAddUserModal" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showAddUserModal = false"></div>
                <!-- Modal content -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative z-50 inline-block align-bottom bg-white rounded-lg text-left rtl:text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 rtl:sm:ml-0 rtl:sm:mr-4 sm:text-left rtl:sm:text-right w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ isEditing ? $t('edit_user') : $t('add_new_user') }}</h3>
                                <div class="mt-4 space-y-4">
                                     <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('user_name') }}</label>
                                        <input v-model="form.name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('user_email') }}</label>
                                        <input v-model="form.email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('password') }} <span v-if="isEditing" class="text-xs text-gray-400">({{ $t('leave_blank_password') }})</span></label>
                                        <input v-model="form.password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ $t('user_role') }}</label>
                                        <select v-model="form.role" :disabled="editUserId === 1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-secondary-orange focus:ring focus:ring-secondary-orange focus:ring-opacity-50 disabled:bg-gray-100 disabled:text-gray-500">
                                            <option value="admin">{{ $t('admin') }}</option>
                                            <option value="editor">{{ $t('editor') }}</option>
                                            <option value="customer">{{ $t('customer') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="saveUser" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-secondary-orange text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 rtl:sm:ml-0 rtl:sm:mr-3 sm:w-auto sm:text-sm">
                            {{ isEditing ? $t('update') : $t('save') }}
                        </button>
                        <button @click="showAddUserModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 rtl:sm:ml-0 rtl:sm:mr-3 sm:w-auto sm:text-sm">
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
            showAddUserModal: false,
            isEditing: false,
            editUserId: null,
            form: {
                name: '',
                email: '',
                password: '',
                role: 'admin'
            },
            currentUser: window.auth_user || { role: 'customer' }
        };
    },
    computed: {
        isAdmin() {
            return this.currentUser.role === 'admin';
        }
    },
    methods: {
        async fetchUsers(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get(`/api/users?exclude_role=customer&page=${page}`);
                this.users = response.data.data;
                this.currentPage = response.data.current_page;
                this.totalPages = response.data.last_page;
            } catch (err) {
                this.error = this.$t('failed_load_users');
                console.error(err);
            } finally {
                this.loading = false;
            }
        },
        openAddUserModal() {
            this.isEditing = false;
            this.editUserId = null;
            this.form = { name: '', email: '', password: '', role: 'admin' };
            this.showAddUserModal = true;
        },
        openEditUserModal(user) {
            this.isEditing = true;
            this.editUserId = user.id;
            this.form = {
                name: user.name,
                email: user.email,
                password: '', // Don't fill password
                role: user.role
            };
            this.showAddUserModal = true;
        },
        async saveUser() {
            if (!this.form.name || !this.form.email || (!this.isEditing && !this.form.password)) {
                alert(this.$t('required_fields_error'));
                return;
            }
            try {
                if (this.isEditing) {
                    await axios.put(`/api/users/${this.editUserId}`, this.form);
                    alert(this.$t('user_updated'));
                } else {
                    await axios.post('/api/users', this.form);
                    alert(this.$t('user_added'));
                }
                this.showAddUserModal = false;
                this.form = { name: '', email: '', password: '', role: 'admin' };
                this.fetchUsers(this.currentPage);
            } catch (err) {
                console.error(err);
                if (err.response && err.response.data && err.response.data.errors) {
                    const errorMessages = Object.values(err.response.data.errors).flat().join('\n');
                    alert(this.$t('validation_error') + "\n" + errorMessages);
                } else {
                    alert(err.response?.data?.message || this.$t('failed_save_user'));
                }
            }
        },
        async deleteUser(id) {
            if (!confirm(this.$t('delete_user_confirm'))) return;
            try {
                await axios.delete(`/api/users/${id}`);
                this.fetchUsers(this.currentPage);
                alert(this.$t('success_delete'));
            } catch (err) {
                alert(this.$t('failed_delete_user'));
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
        this.fetchUsers();
    },
};
</script>
