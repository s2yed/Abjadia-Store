<template>
    <div class="relative cursor-pointer" @click="open">
        <slot></slot>
        <span 
            v-if="count > 0"
            class="absolute -top-1 -right-1 bg-secondary-orange text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center animate-pulse"
        >
            {{ count }}
        </span>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCart } from '../../stores/cart';

const props = defineProps({
    initialCount: {
        type: Number,
        default: 0
    }
});

const { state, refresh, open } = useCart();

const count = computed(() => state.count);

onMounted(async () => {
    // If we have an initial count from Blade, we can use it, 
    // but refresh() will fetch the most accurate data from session.
    if (props.initialCount > 0 && state.count === 0) {
        state.count = props.initialCount;
    }
    await refresh();
});

import { computed } from 'vue';
</script>
