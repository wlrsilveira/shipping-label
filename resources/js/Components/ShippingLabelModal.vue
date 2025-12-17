<template>
    <div
        v-if="isOpen && labelUrl"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="close"
    >
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-6xl max-h-[95vh] flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Shipping Label</h3>
                    <button
                        @click="close"
                        class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-1 hover:bg-gray-100"
                    >
                        <XMarkIcon class="h-6 w-6" />
                    </button>
                </div>

                <div class="flex-1 overflow-hidden bg-gray-50 p-4">
                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-white h-full">
                        <iframe
                            :src="labelUrl"
                            class="w-full h-full min-h-[700px] border-0"
                            frameborder="0"
                        ></iframe>
                    </div>
                </div>

                <div class="flex justify-end px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <button
                        @click="close"
                        class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    labelUrl: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

const handleEscape = (event) => {
    if (event.key === 'Escape' && props.isOpen) {
        close();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleEscape);
});

watch(() => props.isOpen, (isOpen) => {
    if (isOpen) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});
</script>

