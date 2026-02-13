<template>
    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-xl border border-white/20">
            <div>
                <h1 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    {{ $t('Create your account') }}
                </h1>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('Full Name') }}</label>
                        <input id="name" v-model="form.name" name="name" type="text" autocomplete="name" required 
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-secondary-orange focus:border-transparent transition-all duration-200" 
                            :placeholder="$t('Full Name')">
                    </div>
                    <div>
                        <label for="email-address" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('Email address') }}</label>
                        <input id="email-address" v-model="form.email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-secondary-orange focus:border-transparent transition-all duration-200" 
                            :placeholder="$t('Email address')">
                    </div>
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('Password') }}</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" v-model="form.password" name="password" autocomplete="new-password" required 
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-secondary-orange focus:border-transparent transition-all duration-200" 
                                :placeholder="$t('Password')">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none rtl:left-0 rtl:right-auto">
                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                             {{ $t('Password must be at least 8 characters') }}
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('Confirm Password') }}</label>
                        <input id="password_confirmation" v-model="form.password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                            class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-secondary-orange focus:border-transparent transition-all duration-200" 
                            :placeholder="$t('Confirm Password')">
                    </div>
                </div>

                <div>
                    <button type="submit" :disabled="loading" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-secondary-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-orange disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                        <span v-if="loading" class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        {{ loading ? $t('Creating account...') : $t('Register') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        {{ $t('Already have an account?') }}
                        <a href="/login" class="font-bold text-secondary-orange hover:text-orange-500 transition-colors duration-200">
                            {{ $t('Sign in') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useNotification } from '../../stores/notification';

const notify = useNotification();

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const loading = ref(false);
const showPassword = ref(false);

const handleRegister = async () => {
    loading.value = true;
    try {
        await axios.post('/register', form.value);
        notify.success('Account created successfully');
        window.location.href = '/my-account';
    } catch (error) {
        console.error('Registration error:', error);
        notify.error(error.response?.data?.message || 'Registration failed. Please try again.');
    } finally {
        loading.value = false;
    }
};
</script>
