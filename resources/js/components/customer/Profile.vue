<template>
    <CustomerLayout :user="user">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $t('Profile Information') }}</h1>
            <span :class="[user?.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700', 'px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm']">
                {{ user?.status }}
            </span>
        </div>

        <form @submit.prevent="handleUpdateProfile" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-700 tracking-wide">{{ $t('Full Name') }}</label>
                    <input v-model="form.name" type="text" 
                        class="appearance-none block w-full px-6 py-3.5 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-secondary-orange/10 focus:border-secondary-orange transition-all duration-300 bg-gray-50/50 text-gray-900 font-bold text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-700 tracking-wide">{{ $t('Email address') }}</label>
                    <input v-model="form.email" type="email" 
                        class="appearance-none block w-full px-6 py-3.5 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-secondary-orange/10 focus:border-secondary-orange transition-all duration-300 bg-gray-50/50 text-gray-900 font-bold text-sm">
                </div>
            </div>

            <div class="pt-10 border-t border-gray-100">
                <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 text-secondary-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 15v2m-6 4h12a2 2-0 002-2v-6a2 2-0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $t('Security') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-700 tracking-wide">{{ $t('New Password') }}</label>
                        <input v-model="form.password" type="password" placeholder="••••••••"
                            class="appearance-none block w-full px-6 py-3.5 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-secondary-orange/10 focus:border-secondary-orange transition-all duration-300 bg-gray-50/50 text-gray-900 font-bold text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-700 tracking-wide">{{ $t('Confirm Password') }}</label>
                        <input v-model="form.password_confirmation" type="password" placeholder="••••••••"
                            class="appearance-none block w-full px-6 py-3.5 border border-gray-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-secondary-orange/10 focus:border-secondary-orange transition-all duration-300 bg-gray-50/50 text-gray-900 font-bold text-sm">
                    </div>
                </div>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" :disabled="saving"
                    class="px-12 py-4 bg-secondary-orange text-white font-black rounded-2xl hover:bg-orange-600 transition-all duration-300 transform hover:scale-105 active:scale-95 disabled:opacity-50 shadow-2xl shadow-orange-200">
                    <span v-if="saving" class="flex items-center gap-2">
                         <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ $t('Saving...') }}
                    </span>
                    <span v-else>{{ $t('Save Changes') }}</span>
                </button>
            </div>
        </form>
    </CustomerLayout>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useNotification } from '../../stores/notification';
import CustomerLayout from './CustomerLayout.vue';

const props = defineProps(['initialUser']);
const notify = useNotification();

const user = ref(props.initialUser);
const form = ref({
    name: user.value?.name,
    email: user.value?.email,
    password: '',
    password_confirmation: ''
});

const saving = ref(false);

const handleUpdateProfile = async () => {
    saving.value = true;
    try {
        const response = await axios.patch('/profile', form.value);
        notify.success('Profile updated successfully');
        form.value.password = '';
        form.value.password_confirmation = '';
    } catch (error) {
        notify.error(error.response?.data?.message || 'Update failed');
    } finally {
        saving.value = false;
    }
};
</script>
