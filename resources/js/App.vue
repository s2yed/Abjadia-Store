<template>
    <div class="flex h-screen bg-gray-100 font-sans" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
        <!-- Mobile Sidebar Overlay -->
        <div 
            v-if="sidebarOpen" 
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden transition-opacity duration-300"
        ></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 z-30 w-64 bg-gray-900 text-white flex flex-col transition-all duration-300 transform md:relative md:translate-x-0"
            :class="[
                currentLocale === 'ar' ? 'border-l border-gray-800' : 'border-r border-gray-800',
                sidebarOpen ? 'translate-x-0' : (currentLocale === 'ar' ? 'translate-x-[100%]' : '-translate-x-[100%]')
            ]"
        >
            <div class="p-6 flex items-center justify-between border-b border-gray-800">
                <h1 class="text-xl font-bold tracking-wide">{{ $t('admin_panel') }}</h1>
                <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto">
                <router-link
                    to="/dashboard"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📊</span> {{ $t('dashboard') }}
                </router-link>
                <router-link
                    to="/dashboard/products"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📦</span> {{ $t('products') }}
                </router-link>
                <router-link
                    to="/dashboard/authors"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">✍️</span> {{ $t('authors') }}
                </router-link>
                <router-link
                    to="/dashboard/publishers"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🏢</span> {{ $t('publishers') }}
                </router-link>
                <router-link
                    to="/dashboard/translators"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🌐</span> {{ $t('translators') }}
                </router-link>
                <router-link
                    to="/dashboard/orders"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🧾</span> {{ $t('orders') }}
                </router-link>

                <!-- Shipping -->
                <router-link
                    to="/dashboard/shipping-companies"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🚚</span> {{ $t('shipping_companies') }}
                </router-link>
                 <router-link
                    to="/dashboard/shipping-zones"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🌍</span> {{ $t('shipping_zones') }}
                </router-link>

                <!-- Coupons -->
                <router-link
                     to="/dashboard/coupons"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🎟️</span> {{ $t('coupons') }}
                </router-link>

                 <!-- Offers -->
                <router-link
                     to="/dashboard/offers"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🔥</span> {{ $t('offers') }}
                </router-link>
                <router-link
                     to="/dashboard/bank-accounts"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🏦</span> {{ $t('bank_accounts') }}
                </router-link>
                <router-link
                    v-if="isAdmin"
                    to="/dashboard/users"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">👥</span> {{ $t('users') }}
                </router-link>
                <router-link
                    v-if="isAdmin"
                    to="/dashboard/customers"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🛒</span> {{ $t('customers') }}
                </router-link>
                <router-link
                    to="/dashboard/categories"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📂</span> {{ $t('categories') }}
                </router-link>
                <router-link
                    to="/dashboard/banners"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">🖼️</span> {{ $t('banners') }}
                </router-link>
                <router-link
                    to="/dashboard/pages"
                    @click="sidebarOpen = false"
                    class="flex items-center py-2.5 px-4 rounded hover:bg-gray-800 transition-colors"
                    exact-active-class="bg-gray-800 text-secondary-orange"
                >
                    <span :class="currentLocale === 'ar' ? 'ml-3' : 'mr-3'">📄</span> {{ $t('pages') }}
                </router-link>
                <router-link
                    to="/dashboard/settings"
                    @click="sidebarOpen = false"
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
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg mr-2 rtl:mr-0 rtl:ml-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $t($route.name?.toLowerCase()) }}
                    </h2>
                </div>
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <button @click="toggleLanguage" class="text-gray-600 hover:text-gray-900 font-medium">
                        {{ currentLocale === 'en' ? 'العربية' : 'English' }}
                    </button>
                    <span class="text-gray-600 hidden sm:inline">{{ welcomeMessage }}</span>
                    <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-1 px-3 rounded shadow transition-colors">
                        {{ $t('logout') }}
                    </button>
                </div>
            </header>
            <main
                class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-6"
            >
                <router-view></router-view>
            </main>
        </div>

        <!-- Global Notifications -->
        <Toast />
    </div>
</template>

<script>
import axios from 'axios';
import Toast from './components/ui/Toast.vue';
import { useNotification } from './stores/notification';

export default {
    name: "App",
    components: {
        Toast
    },
    setup() {
        const notify = useNotification();
        return { notify };
    },
    data() {
        return {
            user: window.auth_user || null,
            currentLocale: this.$i18n.locale,
            sidebarOpen: false
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
                this.notify.error(this.$t('logout_failed'));
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
