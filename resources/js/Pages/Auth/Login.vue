<template>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-turno-bg-light">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold mb-2 text-turno-primary">Turno</h1>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-turno-primary">
                    Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm text-turno-primary">
                    Or
                    <Link :href="route('register')" class="font-medium hover:opacity-80 transition-opacity text-turno-primary">
                        create a new account
                    </Link>
                </p>
            </div>
            <form @submit.prevent="submit" class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email</label>
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
                                class="appearance-none rounded-lg relative block w-full pl-10 pr-4 py-3 border border-gray-300 placeholder-gray-400 rounded-t-md focus:outline-none focus:ring-2 focus:ring-turno-primary focus:border-transparent focus:z-10 sm:text-sm transition-all text-turno-primary"
                                placeholder="Email"
                            />
                        </div>
                        <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                            {{ form.errors.email }}
                        </div>
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <LockClosedIcon class="h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="appearance-none rounded-lg relative block w-full pl-10 pr-4 py-3 border border-gray-300 placeholder-gray-400 rounded-b-md focus:outline-none focus:ring-2 focus:ring-turno-primary focus:border-transparent focus:z-10 sm:text-sm transition-all text-turno-primary"
                                placeholder="Password"
                            />
                        </div>
                        <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                            {{ form.errors.password }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 border-gray-300 rounded accent-turno-primary"
                        />
                        <label for="remember" class="ml-2 block text-sm text-turno-primary">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-turno-gradient focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-turno-primary disabled:opacity-50 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        :class="form.processing ? 'opacity-70' : ''"
                    >
                        <span v-if="form.processing">Signing in...</span>
                        <span v-else>Sign in</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { EnvelopeIcon, LockClosedIcon } from '@heroicons/vue/24/outline';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'));
};
</script>

