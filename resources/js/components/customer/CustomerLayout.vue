<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white/80 backdrop-blur-md shadow-2xl rounded-[40px] overflow-hidden border border-white/20">
            <div class="md:flex min-h-[600px]">
                <!-- Sidebar -->
                <div class="md:w-[320px] bg-gray-50/50 p-8 border-r border-gray-100 rtl:border-r-0 rtl:border-l flex flex-col justify-between">
                    <div>
                        <!-- User Header -->
                        <div class="text-center mb-10">
                            <div class="relative inline-block group">
                                <span class="inline-flex h-24 w-24 items-center justify-center rounded-full bg-secondary-orange text-white text-3xl font-black shadow-2xl ring-4 ring-white transform transition-transform group-hover:scale-105 duration-500">
                                    {{ user?.name?.charAt(0).toUpperCase() }}
                                </span>
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full shadow-lg"></div>
                            </div>
                            <h2 class="mt-6 text-xl font-black text-gray-900 tracking-tight">{{ user?.name }}</h2>
                            <p class="text-xs text-gray-400 font-medium">{{ user?.email }}</p>
                        </div>
                        
                        <!-- Main Menu -->
                        <nav class="space-y-3">
                            <a v-for="item in menuItems" :key="item.path" :href="item.path" 
                                :class="[
                                    isActive(item.path) 
                                    ? 'bg-secondary-orange text-white shadow-xl shadow-orange-100' 
                                    : 'text-gray-500 hover:bg-white hover:text-secondary-orange hover:shadow-lg',
                                    'flex items-center px-6 py-3.5 text-xs font-black rounded-2xl transition-all duration-300 transform active:scale-95'
                                ]">
                                <span class="text-xl mr-4 rtl:mr-0 rtl:ml-4" v-html="item.icon"></span>
                                {{ $t(item.label) }}
                            </a>
                        </nav>
                    </div>

                    <!-- Logout -->
                    <div class="mt-12 pt-8 border-t border-gray-100">
                        <button @click="handleLogout" 
                            class="w-full flex items-center px-6 py-3.5 text-xs font-black text-red-500 hover:bg-red-50 rounded-2xl transition-all duration-300 transform active:scale-95 group">
                            <svg class="h-6 w-6 mr-4 rtl:mr-0 rtl:ml-4 text-red-400 group-hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ $t('Sign out') }}
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-1 p-8 md:p-12 bg-white/30">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { useNotification } from '../../stores/notification';

const props = defineProps(['user']);
const notify = useNotification();

const menuItems = [
    { label: 'My Profile', path: '/my-account', icon: '👤' },
    { label: 'My Orders', path: '/orders', icon: '📦' },
    { label: 'Favorites', path: '/favorites', icon: '❤️' }
];

const isActive = (path) => {
    return window.location.pathname === path || (path !== '/my-account' && window.location.pathname.startsWith(path));
};

const handleLogout = async () => {
    try {
        await axios.post('/logout');
        window.location.href = '/login';
    } catch (error) {
        notify.error('Logout failed');
    }
};
</script>

<style scoped>
/* Optional: specific styles for the layout */
</style>
