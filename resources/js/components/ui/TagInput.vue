<template>
    <div class="tag-input-container w-full">
        <div class="flex flex-wrap gap-2 p-2 border border-gray-300 rounded-md focus-within:ring-2 focus-within:ring-secondary-orange focus-within:border-secondary-orange bg-white min-h-[42px]">
            <div v-for="(tag, index) in tags" :key="index" class="flex items-center bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-gray-200">
                {{ tag }}
                <button @click="removeTag(index)" type="button" class="ml-1.5 text-gray-400 hover:text-red-500 focus:outline-none">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <input 
                type="text" 
                v-model="newTag" 
                @keydown.enter.prevent="addTag" 
                @keydown.comma.prevent="addTag"
                @keydown.backspace="handleBackspace"
                :placeholder="tags.length > 0 ? '' : placeholder" 
                class="flex-1 min-w-[120px] outline-none border-none p-0 focus:ring-0 text-sm bg-transparent h-6"
            />
        </div>
        <p v-if="hint" class="mt-1 text-xs text-gray-500">{{ hint }}</p>
    </div>
</template>

<script setup>
import { ref, watch, toRefs } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Add tag...'
    },
    hint: {
        type: String,
        default: 'Press Enter or Comma to add tags'
    }
});

const emit = defineEmits(['update:modelValue']);

const { modelValue } = toRefs(props);
const tags = ref([]);
const newTag = ref('');

// Initialize tags from prop
const initTags = () => {
    if (modelValue.value) {
        tags.value = modelValue.value.split(',').map(t => t.trim()).filter(t => t);
    } else {
        tags.value = [];
    }
};

initTags();

// Watch for external changes
watch(modelValue, (newVal) => {
    // Only update if the string representation is different to avoid cursor jumping issues or loops
    const currentString = tags.value.join(',');
    if (newVal !== currentString) {
        // initTags(); // Re-initializing might be aggressive if user is typing, but modelValue is usually string.
        // Better: only if vastly different. For now, simple re-init is safer for external updates.
        tags.value = newVal ? newVal.split(',').map(t => t.trim()).filter(t => t) : [];
    }
});

const updateModel = () => {
    emit('update:modelValue', tags.value.join(','));
};

const addTag = () => {
    const tag = newTag.value.trim();
    if (tag && !tags.value.includes(tag)) {
        tags.value.push(tag);
        newTag.value = '';
        updateModel();
    } else if (tags.value.includes(tag)) {
        newTag.value = ''; // Duplicate
    }
};

const removeTag = (index) => {
    tags.value.splice(index, 1);
    updateModel();
};

const handleBackspace = (e) => {
    if (newTag.value === '' && tags.value.length > 0) {
        tags.value.pop();
        updateModel();
    }
};
</script>
