<template>
    <div 
        class="fixed top-6 right-6 z-[9999] flex flex-col space-y-3 pointer-events-none"
        :class="{ 'left-6 right-auto': isRtl }"
    >
        <TransitionGroup name="toast">
            <div 
                v-for="notification in notifications" 
                :key="notification.id"
                class="pointer-events-auto min-w-[300px] max-w-md bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden flex transform transition-all duration-300"
                :class="typeClasses[notification.type]"
            >
                <div class="flex-1 p-4 flex items-center space-x-3 rtl:space-x-reverse">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="iconBgClasses[notification.type]">
                        <span v-if="notification.type === 'success'" class="text-xl">✅</span>
                        <span v-else-if="notification.type === 'error'" class="text-xl">❌</span>
                        <span v-else class="text-xl">ℹ️</span>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 leading-tight">
                            {{ notification.message }}
                        </p>
                    </div>

                    <!-- Close -->
                    <button 
                        @click="remove(notification.id)"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Progress Bar -->
                <div class="absolute bottom-0 left-0 h-1 bg-current opacity-20 animate-progress"></div>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { useNotification } from '../../stores/notification';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { notifications, remove } = useNotification();
const { locale } = useI18n();

const isRtl = computed(() => locale.value === 'ar');

const typeClasses = {
    success: 'border-l-4 border-l-green-500',
    error: 'border-l-4 border-l-red-500',
    info: 'border-l-4 border-l-blue-500'
};

const iconBgClasses = {
    success: 'bg-green-50 text-green-600',
    error: 'bg-red-50 text-red-600',
    info: 'bg-blue-50 text-blue-600'
};
</script>

<style scoped>
.toast-enter-from {
    opacity: 0;
    transform: translateY(-20px) scale(0.9);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(100px);
}
.rtl .toast-leave-to {
    transform: translateX(-100px);
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

.animate-progress {
    animation: progress 3s linear forwards;
}
</style>
