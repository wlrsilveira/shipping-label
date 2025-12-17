<template>
    <AuthenticatedLayout :auth="auth">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <h1 class="text-4xl font-bold text-turno-primary">Users</h1>
                    <p class="mt-2 text-sm text-turno-primary">
                        List of all registered users
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link
                        :href="route('users.create')"
                        class="inline-flex items-center rounded-lg px-4 py-2.5 text-center text-sm font-semibold text-white shadow-md bg-turno-gradient focus-visible:outline-2 focus-visible:outline-offset-2 transition-all transform hover:-translate-y-0.5 hover:shadow-lg hover:opacity-90"
                    >
                        <PlusIcon class="mr-2 h-5 w-5" />
                        New User
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
                                        Name
                                    </th>
                                    <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-turno-primary">
                                        Email
                                    </th>
                                    <th scope="col" class="relative py-4 pl-3 pr-6 text-right">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-turno-primary">
                                        {{ user.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-turno-primary">
                                        {{ user.email }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-3">
                                            <Link
                                                :href="route('users.edit', user.id)"
                                                class="inline-flex items-center font-medium transition-colors hover:opacity-80 text-turno-primary"
                                            >
                                                <PencilIcon class="h-4 w-4 mr-1" />
                                            </Link>
                                            <button
                                                @click="deleteUser(user.id)"
                                                class="inline-flex items-center text-red-600 hover:text-red-700 font-medium transition-colors"
                                            >
                                                <TrashIcon class="h-4 w-4 mr-1" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-if="users.links && users.links.length > 3" class="mt-4 flex items-center justify-between">
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link
                        v-if="users.prev_page_url"
                        :href="users.prev_page_url"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Previous
                    </Link>
                    <Link
                        v-if="users.next_page_url"
                        :href="users.next_page_url"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Next
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ users.from }}</span>
                            to
                            <span class="font-medium">{{ users.to }}</span>
                            of
                            <span class="font-medium">{{ users.total }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <Link
                                v-for="link in users.links"
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
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Pages/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { PlusIcon, PencilIcon, TrashIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';

defineProps({
    auth: Object,
    users: Object,
});

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('users.destroy', id));
    }
};
</script>

