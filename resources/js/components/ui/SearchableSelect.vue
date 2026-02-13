<template>
    <div class="relative" ref="container">
        <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        
        <!-- Toggle Button -->
        <button 
            type="button"
            @click="toggle"
            class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left rtl:text-right cursor-default focus:outline-none focus:ring-1 focus:ring-secondary-orange focus:border-secondary-orange sm:text-sm"
        >
            <span class="block truncate">{{ selectedLabel || placeholder || 'Select option' }}</span>
            <span class="absolute inset-y-0 right-0 rtl:right-auto rtl:left-0 flex items-center pr-2 rtl:pl-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        <!-- Dropdown Menu -->
        <div 
            v-if="isOpen" 
            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
        >
            <!-- Search Input -->
            <div class="sticky top-0 z-10 bg-white p-2 border-b">
                <input 
                    ref="searchInput"
                    v-model="searchQuery"
                    type="text"
                    class="w-full border border-gray-300 rounded-md p-1 focus:ring-secondary-orange focus:border-secondary-orange"
                    placeholder="Search..."
                    @keydown.esc="close"
                />
            </div>

            <!-- Options List -->
            <ul class="pt-1">
                <li 
                    v-for="option in filteredOptions" 
                    :key="option.id"
                    @click="select(option)"
                    class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-orange-50 text-gray-900"
                >
                    <span :class="[isSelected(option) ? 'font-semibold' : 'font-normal', 'block truncate']">
                        {{ option.label }}
                    </span>
                    <span 
                        v-if="isSelected(option)" 
                        class="text-secondary-orange absolute inset-y-0 right-0 rtl:right-auto rtl:left-0 flex items-center pr-4 rtl:pl-4"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
                <li v-if="filteredOptions.length === 0" class="py-2 px-3 text-gray-500 text-center">
                    No results found
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue';

export default {
    props: {
        modelValue: {
            type: [String, Number],
            default: null
        },
        options: {
            type: Array, // Expected format: [{ id: 1, label: 'Name' }]
            default: () => []
        },
        label: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: ''
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const isOpen = ref(false);
        const searchQuery = ref('');
        const container = ref(null);
        const searchInput = ref(null);

        const toggle = () => {
            isOpen.value = !isOpen.value;
            if (isOpen.value) {
                searchQuery.value = '';
                nextTick(() => {
                    searchInput.value?.focus();
                });
            }
        };

        const close = () => {
            isOpen.value = false;
        };

        const select = (option) => {
            emit('update:modelValue', option.id);
            close();
        };

        const isSelected = (option) => {
            return props.modelValue == option.id;
        };

        const selectedLabel = computed(() => {
            const selected = props.options.find(o => o.id == props.modelValue);
            return selected ? selected.label : '';
        });

        const filteredOptions = computed(() => {
            if (!searchQuery.value) return props.options;
            const query = searchQuery.value.toLowerCase();
            return props.options.filter(option => 
                String(option.label).toLowerCase().includes(query)
            );
        });

        // Close when clicking outside
        const handleClickOutside = (event) => {
            if (container.value && !container.value.contains(event.target)) {
                close();
            }
        };

        onMounted(() => {
            document.addEventListener('click', handleClickOutside);
        });

        onUnmounted(() => {
            document.removeEventListener('click', handleClickOutside);
        });

        return {
            isOpen,
            searchQuery,
            container,
            searchInput,
            toggle,
            close,
            select,
            isSelected,
            selectedLabel,
            filteredOptions
        };
    }
}
</script>
