<template>
    <div class="min-h-screen bg-turno-bg-gray">
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <h1 class="text-2xl font-bold text-turno-primary">Turno</h1>
                        </div>
                        <div class="hidden sm:ml-8 sm:flex sm:space-x-4">
                            <Link
                                :href="route('dashboard')"
                                :class="[
                                    $page.url === '/dashboard'
                                        ? 'bg-turno-bg-light text-turno-primary border-turno-primary border-b-2'
                                        : 'text-turno-primary border-transparent border-b-2',
                                    'inline-flex items-center px-4 py-2 text-sm font-medium transition-colors rounded-t-lg'
                                ]"
                            >
                                <HomeIcon class="h-5 w-5 mr-2" />
                                Dashboard
                            </Link>
                            <Link
                                :href="route('users.index')"
                                :class="[
                                    $page.url.startsWith('/users')
                                        ? 'bg-turno-bg-light text-turno-primary border-turno-primary border-b-2'
                                        : 'text-turno-primary border-transparent border-b-2',
                                    'inline-flex items-center px-4 py-2 text-sm font-medium transition-colors rounded-t-lg'
                                ]"
                            >
                                <UserGroupIcon class="h-5 w-5 mr-2" />
                                Users
                            </Link>
                            <Link
                                :href="route('shipping-labels.index')"
                                :class="[
                                    $page.url.startsWith('/shipping-labels')
                                        ? 'bg-turno-bg-light text-turno-primary border-turno-primary border-b-2'
                                        : 'text-turno-primary border-transparent border-b-2',
                                    'inline-flex items-center px-4 py-2 text-sm font-medium transition-colors rounded-t-lg'
                                ]"
                            >
                                <TruckIcon class="h-5 w-5 mr-2" />
                                Shipping Labels
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-white text-sm font-semibold bg-turno-gradient">
                                {{ auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                            <span class="text-sm font-medium text-turno-primary">{{ auth.user.name }}</span>
                        </div>
                        <Link
                            :href="route('logout')"
                            method="post"
                            class="hidden sm:inline-flex items-center text-sm font-medium transition-colors hover:opacity-80 text-turno-primary"
                        >
                            <ArrowRightOnRectangleIcon class="h-5 w-5 mr-2" />
                        </Link>
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-turno-primary hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-turno-primary"
                        >
                            <Bars3Icon v-if="!mobileMenuOpen" class="h-6 w-6" />
                            <XMarkIcon v-else class="h-6 w-6" />
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="mobileMenuOpen" class="sm:hidden border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link
                        :href="route('dashboard')"
                        @click="mobileMenuOpen = false"
                        :class="[
                            $page.url === '/dashboard'
                                ? 'bg-turno-bg-light text-turno-primary'
                                : 'text-turno-primary hover:bg-gray-50',
                            'block px-3 py-2 rounded-md text-base font-medium'
                        ]"
                    >
                        <div class="flex items-center">
                            <HomeIcon class="h-5 w-5 mr-2" />
                            Dashboard
                        </div>
                    </Link>
                    <Link
                        :href="route('users.index')"
                        @click="mobileMenuOpen = false"
                        :class="[
                            $page.url.startsWith('/users')
                                ? 'bg-turno-bg-light text-turno-primary'
                                : 'text-turno-primary hover:bg-gray-50',
                            'block px-3 py-2 rounded-md text-base font-medium'
                        ]"
                    >
                        <div class="flex items-center">
                            <UserGroupIcon class="h-5 w-5 mr-2" />
                            Users
                        </div>
                    </Link>
                    <Link
                        :href="route('shipping-labels.index')"
                        @click="mobileMenuOpen = false"
                        :class="[
                            $page.url.startsWith('/shipping-labels')
                                ? 'bg-turno-bg-light text-turno-primary'
                                : 'text-turno-primary hover:bg-gray-50',
                            'block px-3 py-2 rounded-md text-base font-medium'
                        ]"
                    >
                        <div class="flex items-center">
                            <TruckIcon class="h-5 w-5 mr-2" />
                            Shipping Labels
                        </div>
                    </Link>
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <div class="flex items-center px-3 mb-3">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-semibold bg-turno-gradient">
                                {{ auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-turno-primary">{{ auth.user.name }}</div>
                            </div>
                        </div>
                        <Link
                            :href="route('logout')"
                            method="post"
                            @click="mobileMenuOpen = false"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium text-turno-primary hover:bg-gray-50"
                        >
                            <ArrowRightOnRectangleIcon class="h-5 w-5 mr-2" />
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    HomeIcon,
    UserGroupIcon,
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    XMarkIcon,
    TruckIcon
} from '@heroicons/vue/24/outline';

defineProps({
    auth: Object,
});

const mobileMenuOpen = ref(false);
</script>

