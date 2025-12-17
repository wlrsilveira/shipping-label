<template>
    <AuthenticatedLayout :auth="auth">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <h1 class="text-4xl font-bold text-turno-primary">Dashboard</h1>
                    <p class="mt-2 text-sm text-turno-primary">
                        System overview
                    </p>
                </div>
            </div>

            <div class="mt-8">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-1 lg:grid-cols-3">
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <div class="h-12 w-12 rounded-lg flex items-center justify-center bg-turno-gradient">
                                        <UserGroupIcon class="h-6 w-6 text-white" />
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium truncate text-turno-primary">
                                            Registered Users
                                        </dt>
                                        <dd class="text-3xl font-bold mt-1 text-turno-primary">
                                            {{ usersCount }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 bg-turno-bg-light">
                            <div class="text-sm">
                                <Link :href="route('users.index')" class="font-semibold transition-colors hover:opacity-80 inline-flex items-center text-turno-primary">
                                    View all users
                                    <ArrowRightIcon class="ml-2 h-4 w-4" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Pages/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { UserGroupIcon, ArrowRightIcon } from '@heroicons/vue/24/outline';

defineProps({
    auth: Object,
    usersCount: Number,
});
</script>

