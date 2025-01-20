<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: [Number, String],
        default: ''
    },
    placeholder: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);

const displayValue = ref('');

// Format number to Rupiah
const formatRupiah = (value) => {
    const number = parseInt(value?.toString().replace(/\D/g, '')) || 0;
    return number.toLocaleString('id-ID');
};

// Handle input changes
const handleInput = (event) => {
    let value = event.target.value;
    // Remove non-digit characters except the last dot for decimal
    value = value.replace(/[^\d]/g, '');
    
    // Emit the raw number value
    emit('update:modelValue', value ? parseInt(value) : '');
    
    // Update the displayed value with formatting
    displayValue.value = formatRupiah(value);
};

// Initialize formatted display value
watch(() => props.modelValue, (newValue) => {
    displayValue.value = formatRupiah(newValue);
}, { immediate: true });
</script>

<template>
    <div class="relative">
        <span 
            class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400"
        >
            Rp
        </span>
        <input
            type="text"
            :value="displayValue"
            @input="handleInput"
            :placeholder="placeholder"
            class="rounded-md border-gray-300 pl-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 w-full"
        />
    </div>
</template>