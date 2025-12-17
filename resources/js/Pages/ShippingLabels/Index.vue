<template>
    <AuthenticatedLayout :auth="auth">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <h1 class="text-4xl font-bold text-turno-primary">Shipping Labels</h1>
                    <div class="mt-2 flex items-center gap-3">
                        <p class="text-sm text-turno-primary">
                            <span v-if="filteredStatusLabel">Showing {{ filteredStatusLabel }} labels</span>
                            <span v-else>List of all your shipping labels</span>
                        </p>
                        <Link
                            v-if="filteredStatus"
                            :href="route('shipping-labels.index')"
                            class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            <XMarkIcon class="h-3 w-3 mr-1" />
                            Clear filter
                        </Link>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link
                        :href="route('shipping-labels.create')"
                        class="inline-flex items-center rounded-lg px-4 py-2.5 text-center text-sm font-semibold text-white shadow-md bg-turno-gradient focus-visible:outline-2 focus-visible:outline-offset-2 transition-all transform hover:-translate-y-0.5 hover:shadow-lg hover:opacity-90"
                    >
                        <PlusIcon class="mr-2 h-5 w-5" />
                        New Label
                    </Link>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="mt-4 rounded-lg bg-green-50 border border-green-200 p-4 shadow-sm">
                <div class="flex items-center">
                    <CheckCircleIcon class="h-5 w-5 text-green-500 mr-2" />
                    <p class="text-sm font-medium text-green-800">
                        {{ $page.props.flash.success }}
                    </p>
                </div>
            </div>

            <div class="mt-8 flow-root bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-turno-bg-light">
                                <tr>
                                    <th scope="col" class="py-4 pl-6 pr-3 text-left text-sm font-semibold text-turno-primary">
                                        Tracking Code
                                    </th>
                                    <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-turno-primary">
                                        Status
                                    </th>
                                    <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-turno-primary">
                                        Carrier
                                    </th>
                                    <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-turno-primary">
                                        To Address
                                    </th>
                                    <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-turno-primary">
                                        Created At
                                    </th>
                                    <th scope="col" class="relative py-4 pl-3 pr-6 text-right">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr v-for="label in localLabels" :key="label.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-turno-primary">
                                        <span v-if="label.tracking_code">{{ label.tracking_code }}</span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <span :class="getStatusClass(label.status)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                            {{ formatStatus(label.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-turno-primary">
                                        <span v-if="label.carrier">{{ label.carrier }}</span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-turno-primary">
                                        <div class="max-w-xs truncate">
                                            {{ formatAddress(label.to_address) }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-turno-primary">
                                        {{ formatDate(label.created_at) }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <button
                                                v-if="label.label_url"
                                                @click="openLabelModal(label.label_url)"
                                                class="inline-flex items-center font-medium transition-colors hover:opacity-80 text-blue-600 hover:text-blue-800"
                                                title="View Label"
                                            >
                                                <DocumentIcon class="h-4 w-4" />
                                            </button>
                                            <Link
                                                v-if="label.id"
                                                :href="route('shipping-labels.show', label.id)"
                                                class="inline-flex items-center font-medium transition-colors hover:opacity-80 text-turno-primary"
                                                title="View Details"
                                            >
                                                <EyeIcon class="h-4 w-4" />
                                            </Link>
                                            <button
                                                v-if="label.id"
                                                @click="deleteLabel(label.id)"
                                                class="inline-flex items-center text-red-600 hover:text-red-700 font-medium transition-colors"
                                                title="Delete"
                                            >
                                                <TrashIcon class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="localLabels.length === 0">
                                    <td colspan="6" class="py-8 text-center text-sm text-gray-500">
                                        No shipping labels found. Create your first one!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-if="labels.links && labels.links.length > 3" class="mt-4 flex items-center justify-between">
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link
                        v-if="labels.prev_page_url"
                        :href="labels.prev_page_url"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="labels.next_page_url"
                        :href="labels.next_page_url"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Next
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ labels.from }}</span>
                            to
                            <span class="font-medium">{{ labels.to }}</span>
                            of
                            <span class="font-medium">{{ labels.total }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <Link
                                v-for="link in labels.links"
                                :key="link.label"
                                :href="link.url || '#'"
                                :class="[
                                    link.active
                                        ? 'z-10 text-white bg-turno-gradient focus-visible:outline-2 focus-visible:outline-offset-2'
                                        : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0',
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold'
                                ]"
                                v-html="link.label"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <ShippingLabelModal
            :is-open="showLabelModal"
            :label-url="selectedLabelUrl"
            @close="closeLabelModal"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Pages/Layouts/AuthenticatedLayout.vue';
import ShippingLabelModal from '@/Components/ShippingLabelModal.vue';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { PlusIcon, EyeIcon, TrashIcon, CheckCircleIcon, DocumentIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { usePusher } from '@/composables/usePusher';
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    auth: Object,
    labels: Object,
    filteredStatus: String,
    filteredStatusLabel: String,
});

const localLabels = ref([...props.labels.data]);
const showLabelModal = ref(false);
const selectedLabelUrl = ref(null);
let channel = null;

const openLabelModal = (labelUrl) => {
    selectedLabelUrl.value = labelUrl;
    showLabelModal.value = true;
};

const closeLabelModal = () => {
    showLabelModal.value = false;
    selectedLabelUrl.value = null;
};

onMounted(() => {
    const pusher = usePusher();

    if (pusher && props.auth?.user?.id) {
        channel = pusher.subscribe(`private-user.${props.auth.user.id}`);

        channel.bind('shipping-label.processed', (data) => {
            const index = localLabels.value.findIndex(label => label.id === data.id);

            if (index !== -1) {
                localLabels.value[index] = {
                    ...localLabels.value[index],
                    status: data.status,
                    tracking_code: data.tracking_code,
                    carrier: data.carrier,
                    label_url: data.label_url,
                    updated_at: data.updated_at,
                };
            } else {
                router.reload({ only: ['labels'] });
            }
        });
    }
});

onUnmounted(() => {
    if (channel) {
        channel.unbind('shipping-label.processed');
    }
});

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
    const parts = [
        address.street1,
        address.city,
        address.state,
        address.zip,
    ].filter(Boolean);
    return parts.join(', ');
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('pt-BR', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const deleteLabel = (id) => {
    if (confirm('Are you sure you want to delete this shipping label?')) {
        router.delete(route('shipping-labels.destroy', id));
    }
};
</script>

