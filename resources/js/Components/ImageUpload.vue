<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: [File, String, null],
        default: null,
    },
    currentUrl: {
        type: String,
        default: '',
    },
    placeholderUrl: {
        type: String,
        default:
            'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=600&fit=crop',
    },
    id: {
        type: String,
        default: 'image',
    },
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);
const isDragging = ref(false);

const previewUrl = ref('');

const computeInitialPreview = () => {
    if (props.modelValue instanceof File) {
        return URL.createObjectURL(props.modelValue);
    }
    if (props.currentUrl) {
        // If it already looks like a full URL, use as-is; otherwise assume storage path
        if (props.currentUrl.startsWith('http')) {
            return props.currentUrl;
        }
        return `/storage/${props.currentUrl}`;
    }
    if (typeof props.modelValue === 'string' && props.modelValue.length) {
        return props.modelValue;
    }
    return props.placeholderUrl;
};

previewUrl.value = computeInitialPreview();

watch(
    () => [props.currentUrl, props.modelValue],
    () => {
        previewUrl.value = computeInitialPreview();
    }
);

const openFileDialog = () => {
    fileInput.value?.click();
};

const handleFiles = (files) => {
    if (!files || !files.length) return;
    const file = files[0];
    emit('update:modelValue', file);
    previewUrl.value = URL.createObjectURL(file);
};

const onFileChange = (event) => {
    handleFiles(event.target.files);
};

const onDragOver = (event) => {
    event.preventDefault();
    isDragging.value = true;
};

const onDragLeave = (event) => {
    event.preventDefault();
    isDragging.value = false;
};

const onDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;
    handleFiles(event.dataTransfer.files);
};
</script>

<template>
    <div class="space-y-3">
        <div
            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md cursor-pointer transition-colors"
            :class="[
                isDragging
                    ? 'border-indigo-400 bg-indigo-50'
                    : 'border-gray-300 hover:border-indigo-400 hover:bg-gray-50',
            ]"
            @click="openFileDialog"
            @dragover="onDragOver"
            @dragleave="onDragLeave"
            @drop="onDrop"
        >
            <div class="space-y-1 text-center">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 48 48"
                    aria-hidden="true"
                >
                    <path
                        d="M28 8H20L13 16V34C13 35.1046 13.8954 36 15 36H33C34.1046 36 35 35.1046 35 34V16L28 8Z"
                        stroke-width="2"
                        stroke-linejoin="round"
                    />
                    <path
                        d="M20 22L23 25L28 20"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                <div class="flex text-sm text-gray-600 justify-center">
                    <span class="relative rounded-md font-medium text-indigo-600">
                        Click to upload
                    </span>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
            </div>
        </div>

        <input
            :id="id"
            ref="fileInput"
            type="file"
            class="hidden"
            accept="image/*"
            @change="onFileChange"
        />

        <div class="mt-2">
            <p class="text-xs text-gray-500 mb-2">Preview</p>
            <div class="h-32 w-32 rounded-md overflow-hidden bg-gray-100 border border-gray-200">
                <img
                    :src="previewUrl"
                    alt="Event image preview"
                    class="h-full w-full object-cover"
                />
            </div>
        </div>
    </div>
</template>


