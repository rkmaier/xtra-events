<script setup>
import { onMounted, ref } from 'vue';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: 'datetime',
    },
    name: {
        type: String,
        default: 'datetime',
    },
    placeholder: {
        type: String,
        default: 'Select date & time',
    },
});

const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null);

onMounted(() => {
    if (!inputRef.value) return;

    flatpickr(inputRef.value, {
        enableTime: true,
        dateFormat: "Y-m-d\\TH:i", // internal value sent to backend
        altInput: true,
        altFormat: 'Y.m.d. H:i', // display format: 2024.05.20. 18:00
        time_24hr: true,
        defaultDate: props.modelValue || null,
        onChange(selectedDates, dateStr) {
            emit('update:modelValue', dateStr);
        },
    });
});
</script>

<template>
    <input
        :id="id"
        :name="name"
        ref="inputRef"
        :value="modelValue"
        :placeholder="placeholder"
        type="text"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    />
</template>


