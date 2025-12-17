<template>
    <AuthenticatedLayout :auth="auth">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center mb-8">
                <div class="sm:flex-auto">
                    <h1 class="text-4xl font-bold text-turno-primary">New Shipping Label</h1>
                    <p class="mt-2 text-sm text-turno-primary">
                        Fill in the data to create a new shipping label
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">From Address</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Origin address information
                            </p>
                        </div>
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="from_street1" class="flex items-center text-sm font-medium text-gray-700">
                                        Street Address 1 *
                                    </label>
                                    <input
                                        id="from_street1"
                                        v-model="form.from_address.street1"
                                        type="text"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['from_address.street1']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['from_address.street1'] }}
                                    </div>
                                </div>

                                <div class="col-span-6">
                                    <label for="from_street2" class="flex items-center text-sm font-medium text-gray-700">
                                        Street Address 2
                                    </label>
                                    <input
                                        id="from_street2"
                                        v-model="form.from_address.street2"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="from_city" class="flex items-center text-sm font-medium text-gray-700">
                                        City *
                                    </label>
                                    <input
                                        id="from_city"
                                        v-model="form.from_address.city"
                                        type="text"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['from_address.city']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['from_address.city'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="from_state" class="flex items-center text-sm font-medium text-gray-700">
                                        State *
                                    </label>
                                    <select
                                        id="from_state"
                                        v-model="form.from_address.state"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    >
                                        <option value="">Select State</option>
                                        <option v-for="state in usStates" :key="state.value" :value="state.value">
                                            {{ state.label }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors['from_address.state']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['from_address.state'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="from_zip" class="flex items-center text-sm font-medium text-gray-700">
                                        ZIP Code *
                                    </label>
                                    <input
                                        id="from_zip"
                                        v-model="form.from_address.zip"
                                        type="text"
                                        required
                                        pattern="^\d{5}(-\d{4})?$"
                                        placeholder="12345 or 12345-6789"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['from_address.zip']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['from_address.zip'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="from_name" class="flex items-center text-sm font-medium text-gray-700">
                                        Name
                                    </label>
                                    <input
                                        id="from_name"
                                        v-model="form.from_address.name"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="from_phone" class="flex items-center text-sm font-medium text-gray-700">
                                        Phone
                                    </label>
                                    <input
                                        id="from_phone"
                                        v-model="form.from_address.phone"
                                        type="tel"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="from_company" class="flex items-center text-sm font-medium text-gray-700">
                                        Company
                                    </label>
                                    <input
                                        id="from_company"
                                        v-model="form.from_address.company"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">To Address</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Destination address information
                            </p>
                        </div>
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="to_street1" class="flex items-center text-sm font-medium text-gray-700">
                                        Street Address 1 *
                                    </label>
                                    <input
                                        id="to_street1"
                                        v-model="form.to_address.street1"
                                        type="text"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['to_address.street1']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['to_address.street1'] }}
                                    </div>
                                </div>

                                <div class="col-span-6">
                                    <label for="to_street2" class="flex items-center text-sm font-medium text-gray-700">
                                        Street Address 2
                                    </label>
                                    <input
                                        id="to_street2"
                                        v-model="form.to_address.street2"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="to_city" class="flex items-center text-sm font-medium text-gray-700">
                                        City *
                                    </label>
                                    <input
                                        id="to_city"
                                        v-model="form.to_address.city"
                                        type="text"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['to_address.city']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['to_address.city'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="to_state" class="flex items-center text-sm font-medium text-gray-700">
                                        State *
                                    </label>
                                    <select
                                        id="to_state"
                                        v-model="form.to_address.state"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    >
                                        <option value="">Select State</option>
                                        <option v-for="state in usStates" :key="state.value" :value="state.value">
                                            {{ state.label }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors['to_address.state']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['to_address.state'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="to_zip" class="flex items-center text-sm font-medium text-gray-700">
                                        ZIP Code *
                                    </label>
                                    <input
                                        id="to_zip"
                                        v-model="form.to_address.zip"
                                        type="text"
                                        required
                                        pattern="^\d{5}(-\d{4})?$"
                                        placeholder="12345 or 12345-6789"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['to_address.zip']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['to_address.zip'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="to_name" class="flex items-center text-sm font-medium text-gray-700">
                                        Name
                                    </label>
                                    <input
                                        id="to_name"
                                        v-model="form.to_address.name"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="to_phone" class="flex items-center text-sm font-medium text-gray-700">
                                        Phone
                                    </label>
                                    <input
                                        id="to_phone"
                                        v-model="form.to_address.phone"
                                        type="tel"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="to_company" class="flex items-center text-sm font-medium text-gray-700">
                                        Company
                                    </label>
                                    <input
                                        id="to_company"
                                        v-model="form.to_address.company"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-lg rounded-xl border border-gray-100 px-6 py-6 sm:p-8">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Package Information</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Package dimensions and weight
                            </p>
                        </div>
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="package_weight" class="flex items-center text-sm font-medium text-gray-700">
                                        Weight *
                                    </label>
                                    <input
                                        id="package_weight"
                                        v-model.number="form.package.weight"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        max="150"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['package.weight']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['package.weight'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="package_weight_unit" class="flex items-center text-sm font-medium text-gray-700">
                                        Weight Unit
                                    </label>
                                    <select
                                        id="package_weight_unit"
                                        v-model="form.package.weight_unit"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    >
                                        <option value="lb">Pound (lb)</option>
                                        <option value="oz">Ounce (oz)</option>
                                        <option value="kg">Kilogram (kg)</option>
                                        <option value="g">Gram (g)</option>
                                    </select>
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <label for="package_length" class="flex items-center text-sm font-medium text-gray-700">
                                        Length *
                                    </label>
                                    <input
                                        id="package_length"
                                        v-model.number="form.package.length"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        max="108"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['package.length']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['package.length'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <label for="package_width" class="flex items-center text-sm font-medium text-gray-700">
                                        Width *
                                    </label>
                                    <input
                                        id="package_width"
                                        v-model.number="form.package.width"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        max="108"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['package.width']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['package.width'] }}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <label for="package_height" class="flex items-center text-sm font-medium text-gray-700">
                                        Height *
                                    </label>
                                    <input
                                        id="package_height"
                                        v-model.number="form.package.height"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        max="108"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    />
                                    <div v-if="form.errors['package.height']" class="text-red-500 text-sm mt-1">
                                        {{ form.errors['package.height'] }}
                                    </div>
                                </div>

                                <div class="col-span-6">
                                    <label for="package_dimension_unit" class="flex items-center text-sm font-medium text-gray-700">
                                        Dimension Unit
                                    </label>
                                    <select
                                        id="package_dimension_unit"
                                        v-model="form.package.dimension_unit"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-turno-primary focus:border-transparent sm:text-sm transition-all px-4 py-3 text-turno-primary"
                                    >
                                        <option value="in">Inch (in)</option>
                                        <option value="cm">Centimeter (cm)</option>
                                        <option value="mm">Millimeter (mm)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <Link
                        :href="route('shipping-labels.index')"
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
                        <span v-if="form.processing">Creating...</span>
                        <span v-else>Create Label</span>
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
import { XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    auth: Object,
});

const usStates = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get(route('us-states.index'));
        usStates.value = response.data;
    } catch (error) {
        console.error('Error loading US states:', error);
    }
});

const form = useForm({
    from_address: {
        street1: '',
        street2: '',
        city: '',
        state: '',
        zip: '',
        country: 'US',
        name: '',
        phone: '',
        company: '',
    },
    to_address: {
        street1: '',
        street2: '',
        city: '',
        state: '',
        zip: '',
        country: 'US',
        name: '',
        phone: '',
        company: '',
    },
    package: {
        weight: '',
        length: '',
        width: '',
        height: '',
        weight_unit: 'lb',
        dimension_unit: 'in',
    },
});

const submit = () => {
    form.post(route('shipping-labels.store'));
};
</script>

