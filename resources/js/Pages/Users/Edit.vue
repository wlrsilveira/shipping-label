<template>
    <AuthenticatedLayout :auth="auth">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <h1 class="text-4xl font-bold text-turno-primary">Edit User</h1>
                    <p class="mt-2 text-sm text-turno-primary">
                        Update user information
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">User Information</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Basic user data
                            </p>
                        </div>
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="name" class="flex items-center text-sm font-medium text-gray-700">
                                        <UserIcon class="h-4 w-4 mr-2" />
                                        Name
                                    </label>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div class="col-span-6">
                                    <label for="email" class="flex items-center text-sm font-medium text-gray-700">
                                        <EnvelopeIcon class="h-4 w-4 mr-2" />
                                        Email
                                    </label>
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password" class="flex items-center text-sm font-medium text-gray-700">
                                        <LockClosedIcon class="h-4 w-4 mr-2" />
                                        New Password (leave blank to keep current)
                                    </label>
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.password }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password_confirmation" class="flex items-center text-sm font-medium text-gray-700">
                                        <LockClosedIcon class="h-4 w-4 mr-2" />
                                        Confirm New Password
                                    </label>
                                    <input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <Link
                        :href="route('users.index')"
                        class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                    >
                        <XMarkIcon class="h-4 w-4 mr-2" />
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-md bg-turno-gradient focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-50 transition-all transform hover:-translate-y-0.5 hover:shadow-lg hover:opacity-90"
                    >
                        <CheckIcon class="h-4 w-4 mr-2" />
                        <span v-if="form.processing">Updating...</span>
                        <span v-else>Update</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Pages/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { UserIcon, EnvelopeIcon, LockClosedIcon, XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    auth: Object,
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('users.update', props.user.id));
};
</script>

