<template>
    <AuthenticatedLayout :auth="auth">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <h1 class="text-4xl font-bold text-turno-primary">Shipping Label Details</h1>
                    <p class="mt-2 text-sm text-turno-primary">
                        View shipping label information
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link
                        :href="route('shipping-labels.index')"
                        class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                    >
                        <ArrowLeftIcon class="h-4 w-4 mr-2" />
                        Back to List
                    </Link>
                </div>
            </div>

            <div v-if="label" class="mt-8 space-y-6">
                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Status & Tracking</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                <span :class="getStatusClass(label.status)" class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium">
                                    {{ formatStatus(label.status) }}
                                </span>
                            </div>
                        </div>
                        <div v-if="label.tracking_code">
                            <label class="text-sm font-medium text-gray-500">Tracking Code</label>
                            <p class="mt-1 text-sm text-turno-primary font-medium">{{ label.tracking_code }}</p>
                        </div>
                        <div v-if="label.carrier">
                            <label class="text-sm font-medium text-gray-500">Carrier</label>
                            <p class="mt-1 text-sm text-turno-primary">{{ label.carrier }}</p>
                        </div>
                        <div v-if="label.service">
                            <label class="text-sm font-medium text-gray-500">Service</label>
                            <p class="mt-1 text-sm text-turno-primary">{{ label.service }}</p>
                        </div>
                        <div v-if="label.rate">
                            <label class="text-sm font-medium text-gray-500">Rate</label>
                            <p class="mt-1 text-sm text-turno-primary">${{ label.rate.toFixed(2) }}</p>
                        </div>
                        <div v-if="label.label_url">
                            <button
                                @click="showLabel = true"
                                class="mt-1 inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-white bg-turno-gradient hover:opacity-90 transition-opacity shadow-md"
                            >
                                <EyeIcon class="h-5 w-5 mr-2" />
                                View Label
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">From Address</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-turno-primary">{{ formatAddress(label.from_address) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">To Address</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-turno-primary">{{ formatAddress(label.to_address) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Package Information</h3>
                    <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Weight</label>
                            <p class="mt-1 text-sm text-turno-primary">{{ label.package.weight }} {{ label.package.weight_unit }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Length</label>
                            <p class="mt-1 text-sm text-turno-primary">{{ label.package.length }} {{ label.package.dimension_unit }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Width</label>
                            <p class="mt-1 text-sm text-turno-primary">{{ label.package.width }} {{ label.package.dimension_unit }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Height</label>
                            <p class="mt-1 text-sm text-turno-primary">{{ label.package.height }} {{ label.package.dimension_unit }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button
                        v-if="label.id"
                        @click="deleteLabel(label.id)"
                        class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-red-700 transition-colors"
                    >
                        <TrashIcon class="h-4 w-4 mr-2" />
                        Delete Label
                    </button>
                </div>
            </div>
        </div>

        <ShippingLabelModal
            :is-open="showLabel"
            :label-url="label?.label_url"
            @close="showLabel = false"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Pages/Layouts/AuthenticatedLayout.vue';
import ShippingLabelModal from '@/Components/ShippingLabelModal.vue';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ArrowLeftIcon, TrashIcon, EyeIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';

defineProps({
    auth: Object,
    label: Object,
});

const showLabel = ref(false);

const formatStatus = (status) => {
    const statusMap = {
        'pending': 'Pending',
        'created': 'Created',
        'failed': 'Failed',
        'cancelled': 'Cancelled',
    };
    return statusMap[status] || status;
};

const getStatusClass = (status) => {
    const classMap = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'created': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800',
        'cancelled': 'bg-gray-100 text-gray-800',
    };
    return classMap[status] || 'bg-gray-100 text-gray-800';
};

const formatAddress = (address) => {
    if (!address) return '-';
    const parts = [];
    if (address.name) parts.push(address.name);
    if (address.company) parts.push(address.company);
    if (address.street1) parts.push(address.street1);
    if (address.street2) parts.push(address.street2);
    const cityStateZip = [
        address.city,
        address.state,
        address.zip,
    ].filter(Boolean).join(', ');
    if (cityStateZip) parts.push(cityStateZip);
    return parts.join('\n');
};

const deleteLabel = (id) => {
    if (confirm('Are you sure you want to delete this shipping label?')) {
        router.delete(route('shipping-labels.destroy', id));
    }
};
</script>

