<template>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-turno-bg-light">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold mb-2 text-turno-primary">Turno</h1>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-turno-primary">
                    Create your account
                </h2>
                <p class="mt-2 text-center text-sm text-turno-primary">
                    Or
                    <Link :href="route('login')" class="font-medium hover:opacity-80 transition-opacity text-turno-primary">
                        sign in
                    </Link>
                </p>
            </div>
            <form @submit.prevent="submit" class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-1 text-turno-primary">Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <UserIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                autocomplete="name"
                                required
                                class="appearance-none relative block w-full pl-10 pr-4 py-3 border border-gray-300 placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all text-turno-primary"
                                placeholder="Full name"
                            />
                        </div>
                        <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1 text-turno-primary">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                autocomplete="email"
                                required
                                class="appearance-none relative block w-full pl-10 pr-4 py-3 border border-gray-300 placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all text-turno-primary"
                                placeholder="Email"
                            />
                        </div>
                        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium mb-1 text-turno-primary">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <LockClosedIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                autocomplete="new-password"
                                required
                                class="appearance-none relative block w-full pl-10 pr-4 py-3 border border-gray-300 placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all text-turno-primary"
                                placeholder="Password"
                            />
                        </div>
                        <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                            {{ form.errors.password }}
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-1 text-turno-primary">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <LockClosedIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                required
                                class="appearance-none relative block w-full pl-10 pr-4 py-3 border border-gray-300 placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all text-turno-primary"
                                placeholder="Confirm password"
                            />
                        </div>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-turno-gradient focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-turno-primary disabled:opacity-50 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 hover:opacity-90"
                        :class="form.processing ? 'opacity-70' : ''"
                    >
                        <span v-if="form.processing">Creating account...</span>
                        <span v-else>Create account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { UserIcon, EnvelopeIcon, LockClosedIcon } from '@heroicons/vue/24/outline';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'));
};
</script>

