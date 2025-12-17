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

            <div class="mt-8 space-y-6">
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

                <div>
                    <h2 class="text-2xl font-bold text-turno-primary mb-4">Shipping Labels by Status</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <StatusCard
                            v-for="(stat, status) in shippingLabelStats"
                            :key="status"
                            :title="stat.label"
                            :count="stat.count"
                            :icon="getStatusIcon(status)"
                            :icon-bg-class="getStatusIconBgClass(status)"
                            :icon-color-class="getStatusIconColorClass(status)"
                            :footer-bg-class="getStatusFooterBgClass(status)"
                            :link-color-class="getStatusLinkColorClass(status)"
                            :link-text="`View ${stat.label.toLowerCase()} labels`"
                            :href="route('shipping-labels.index', { status: status })"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Pages/Layouts/AuthenticatedLayout.vue';
import StatusCard from '@/Components/StatusCard.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { UserGroupIcon, ArrowRightIcon, ClockIcon, CheckCircleIcon, XCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';

defineProps({
    auth: Object,
    usersCount: Number,
    shippingLabelStats: Object,
});

const getStatusIcon = (status) => {
    const iconMap = {
        'pending': ClockIcon,
        'created': CheckCircleIcon,
        'failed': XCircleIcon,
        'cancelled': XMarkIcon,
    };
    return iconMap[status] || ClockIcon;
};

const getStatusIconBgClass = (status) => {
    const classMap = {
        'pending': 'bg-yellow-100',
        'created': 'bg-green-100',
        'failed': 'bg-red-100',
        'cancelled': 'bg-gray-100',
    };
    return classMap[status] || 'bg-gray-100';
};

const getStatusIconColorClass = (status) => {
    const classMap = {
        'pending': 'text-yellow-600',
        'created': 'text-green-600',
        'failed': 'text-red-600',
        'cancelled': 'text-gray-600',
    };
    return classMap[status] || 'text-gray-600';
};

const getStatusFooterBgClass = (status) => {
    const classMap = {
        'pending': 'bg-yellow-50',
        'created': 'bg-green-50',
        'failed': 'bg-red-50',
        'cancelled': 'bg-gray-50',
    };
    return classMap[status] || 'bg-gray-50';
};

const getStatusLinkColorClass = (status) => {
    const classMap = {
        'pending': 'text-yellow-700',
        'created': 'text-green-700',
        'failed': 'text-red-700',
        'cancelled': 'text-gray-700',
    };
    return classMap[status] || 'text-gray-700';
};
</script>

