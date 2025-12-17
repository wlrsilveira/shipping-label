<template>
    <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="p-6">
            <div class="flex items-center">
                <div class="shrink-0">
                    <div :class="iconBgClass" class="h-12 w-12 rounded-lg flex items-center justify-center">
                        <component :is="icon" :class="iconColorClass" class="h-6 w-6" />
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium truncate text-gray-500">
                            {{ title }}
                        </dt>
                        <dd class="text-3xl font-bold mt-1 text-turno-primary">
                            {{ count }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div :class="footerBgClass" class="px-6 py-4 border-t border-gray-100">
            <div class="text-sm">
                <Link :href="href" :class="linkColorClass" class="font-semibold transition-colors hover:opacity-80 inline-flex items-center">
                    {{ linkText }}
                    <ArrowRightIcon class="ml-2 h-4 w-4" />
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRightIcon } from '@heroicons/vue/24/outline';

defineProps({
    title: {
        type: String,
        required: true,
    },
    count: {
        type: Number,
        required: true,
    },
    icon: {
        type: Object,
        required: true,
    },
    iconBgClass: {
        type: String,
        required: true,
    },
    iconColorClass: {
        type: String,
        required: true,
    },
    footerBgClass: {
        type: String,
        required: true,
    },
    linkColorClass: {
        type: String,
        required: true,
    },
    linkText: {
        type: String,
        required: true,
    },
    href: {
        type: String,
        required: true,
    },
});
</script>


