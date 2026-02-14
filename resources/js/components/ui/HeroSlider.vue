<template>
    <div class="relative group h-[400px] md:h-[500px] w-full rounded-none md:rounded-b-3xl overflow-hidden shadow-2xl bg-gray-900" 
         @mouseenter="stopAutoplay" 
         @mouseleave="startAutoplay">
        
        <!-- Slides -->
        <div class="relative w-full h-full">
            <transition-group name="fade">
                <template v-for="(banner, index) in banners" :key="banner.id || index">
                    <div v-if="currentSlide === index"
                         class="absolute inset-0 w-full h-full">
                        
                        <!-- Background Image -->
                        <img :src="banner.image_path" :alt="getTrans(banner.title)" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[10s] hover:scale-110 z-0">
                        <div class="absolute inset-0 bg-black/40 z-10"></div>
                        
                        <!-- Content Overlay -->
                        <div class="absolute inset-0 z-20 flex flex-col justify-center items-center text-center px-4 md:px-20"
                             :class="{'items-start text-left pl-10 md:pl-32': banner.text_position === 'left', 'items-end text-right pr-10 md:pr-32': banner.text_position === 'right'}">
                            
                            <h2 v-if="banner.title" class="text-3xl md:text-6xl font-black text-white mb-4 tracking-tight drop-shadow-lg animate-target delay-200">
                                {{ getTrans(banner.title) }}
                            </h2>
                            
                            <p v-if="banner.description" class="text-lg md:text-2xl text-gray-100 mb-8 max-w-2xl font-medium drop-shadow-md animate-target delay-400">
                                {{ getTrans(banner.description) }}
                            </p>
                            
                            <a v-if="banner.link" :href="banner.link" class="inline-flex items-center px-8 py-3 bg-secondary-orange text-white font-bold rounded-full hover:bg-white hover:text-primary-dark transition-all transform hover:-translate-y-1 hover:shadow-xl animate-target delay-600">
                                {{ $t('Explore Now') }}
                                <svg class="w-5 h-5 ml-2 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </template>
            </transition-group>
        </div>

        <!-- Navigation Arrows -->
        <button @click="prevSlide" class="absolute left-4 top-1/2 transform -translate-y-1/2 z-30 bg-black/30 hover:bg-secondary-orange text-white p-3 rounded-full backdrop-blur-sm transition-all duration-300 opacity-0 group-hover:opacity-100 -translate-x-full group-hover:translate-x-0 rtl:right-4 rtl:left-auto rtl:translate-x-full rtl:group-hover:translate-x-0">
            <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button @click="nextSlide" class="absolute right-4 top-1/2 transform -translate-y-1/2 z-30 bg-black/30 hover:bg-secondary-orange text-white p-3 rounded-full backdrop-blur-sm transition-all duration-300 opacity-0 group-hover:opacity-100 translate-x-full group-hover:translate-x-0 rtl:left-4 rtl:right-auto rtl:-translate-x-full rtl:group-hover:translate-x-0">
            <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <!-- Pagination Dots -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-30 flex space-x-2 rtl:space-x-reverse">
            <button v-for="(banner, index) in banners" :key="'dot-'+index" 
                    @click="goToSlide(index)"
                    class="h-2.5 rounded-full transition-all duration-300"
                    :class="currentSlide === index ? 'w-8 bg-secondary-orange' : 'w-2.5 bg-white/50 hover:bg-white'">
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();

const props = defineProps({
    banners: {
        type: Array,
        default: () => []
    }
});

const getTrans = (val) => {
    if (!val) return "";
    if (typeof val === 'object') {
        return val[locale.value] || val.en || "";
    }
    return val;
};

const currentSlide = ref(0);
let intervalId = null;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % props.banners.length;
};

const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + props.banners.length) % props.banners.length;
};

const goToSlide = (index) => {
    currentSlide.value = index;
};

const startAutoplay = () => {
    if (intervalId) clearInterval(intervalId);
    intervalId = setInterval(nextSlide, 5000);
};

const stopAutoplay = () => {
    if (intervalId) clearInterval(intervalId);
};

onMounted(() => {
    if (props.banners.length > 1) {
        startAutoplay();
    }
});

onUnmounted(() => {
    stopAutoplay();
});
</script>

<style scoped>
/* Fade Transition for Slides */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.8s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Slide Content Animations (mimic previous custom animations) */
/* When slide enters, trigger these animations */
.fade-enter-active .animate-target {
    animation: slideUp 0.8s ease-out forwards;
}

.delay-200 { animation-delay: 0.2s; }
.delay-400 { animation-delay: 0.4s; }
.delay-600 { animation-delay: 0.6s; }

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
