<script setup>
import { computed } from 'vue'

const props = defineProps({
    status: {
        type: [Boolean, Number, String], // Can be true/false, 1/0, or specific strings if we extend later
        required: true
    },
    label: {
        type: String,
        default: null
    }
})

const isUp = computed(() => {
    // Handle boolean or 1/0
    return props.status === true || props.status === 1 || props.status === 'up';
})

const displayLabel = computed(() => {
    if (props.label) return props.label;
    
    // Default labels based on status
    if (isUp.value) return 'Healthy';
    
    // Check if status is specifically null or unknown (optional logic if needed)
    if (props.status === null) return 'Pending';
    
    return 'Down';
})

const classes = computed(() => {
    if (isUp.value) {
        return 'bg-green-100 text-green-800 border-green-200';
    }
    
    if (props.status === null) {
        return 'bg-gray-100 text-gray-800 border-gray-200';
    }
    
    return 'bg-red-100 text-red-800 border-red-200';
})
</script>

<template>
    <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border', classes]">
        <span class="w-2 h-2 mr-1.5 rounded-full" :class="isUp ? 'bg-green-400' : (status === null ? 'bg-gray-400' : 'bg-red-400')"></span>
        {{ displayLabel }}
    </span>
</template>
