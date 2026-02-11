<template>
    <div class="flex h-screen bg-gray-100 font-sans" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-gray-900 text-white flex flex-col transition-all duration-300"
            :class="currentLocale === 'ar' ? 'border-l border-gray-800' : 'border-r border-gray-800'"
        >
            <div class="p-6 text-center border-b border-gray-800">
                <h1 class="text-xl font-bold tracking-wide">{{ $t('admin_panel') }}</h1>
            </div>
            <nav class="flex-1 mt-6 px-4 space-y-2">
                <router-link
                    to="/dashboard"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📊</span> {{ $t('dashboard') }}
                </router-link>
                <router-link
                    to="/dashboard/products"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📦</span> {{ $t('products') }}
                </router-link>
                <router-link
                    to="/dashboard/orders"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🧾</span> {{ $t('orders') }}
                </router-link>
                <router-link
                    v-if="isAdmin"
                    to="/dashboard/users"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">👥</span> {{ $t('users') }}
                </router-link>
                <router-link
                    v-if="isAdmin"
                    to="/dashboard/customers"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🛒</span> {{ $t('customers') }}
                </router-link>
                <router-link
                    to="/dashboard/categories"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📂</span> {{ $t('categories') }}
                </router-link>
                <router-link
                    to="/dashboard/banners"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🖼️</span> {{ $t('banners') }}
                </router-link>
                <router-link
                    to="/dashboard/pages"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📄</span> {{ $t('pages') }}
                </router-link>
                <router-link
                    to="/dashboard/settings"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">⚙️</span> {{ $t('site_settings') }}
                </router-link>
            </nav>
            <div class="p-4 border-t border-gray-800">
                <a
                    href="/"
                    class="block text-center py-2 text-sm text-gray-400 hover:text-white"
                    >{{ $t('back_to_store') }}</a
                >
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="bg-white shadow-sm z-10 p-4 flex justify-between items-center"
            >
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $t($route.name?.toLowerCase()) }}
                </h2>
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <button @click="toggleLanguage" class="text-gray-600 hover:text-gray-900 font-medium">
                        {{ currentLocale === 'en' ? 'العربية' : 'English' }}
                    </button>
                    <span class="text-gray-600">{{ welcomeMessage }}</span>
                    <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-1 px-3 rounded shadow transition-colors">
                        {{ $t('logout') }}
                    </button>
                </div>
            </header>
            <main
                class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6"
            >
                <router-view></router-view>
            </main>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "App",
    data() {
        return {
            user: window.auth_user || null,
            currentLocale: this.$i18n.locale
        };
    },
    computed: {
        isAdmin() {
            return this.user && this.user.role === 'admin';
        },
        welcomeMessage() {
            const userName = this.user?.name || 'Admin';
            return this.currentLocale === 'ar' 
                ? `مرحباً، ${userName}` 
                : `Welcome, ${userName}`;
        }
    },
    methods: {
        toggleLanguage() {
            const newLocale = this.currentLocale === 'en' ? 'ar' : 'en';
            this.$i18n.locale = newLocale;
            this.currentLocale = newLocale;
            document.documentElement.lang = newLocale;
            document.documentElement.dir = newLocale === 'ar' ? 'rtl' : 'ltr';
            
            // Persist preference via backend
            axios.get(`/lang/${newLocale}`);
        },
        async logout() {
            try {
                await axios.post('/logout');
                window.location.href = '/login';
            } catch (error) {
                console.error("Logout failed:", error);
                alert(this.$t('logout_failed'));
            }
        }
    }
};
</script>

<style>
.text-secondary-orange {
    color: #e67e22;
}
</style>
