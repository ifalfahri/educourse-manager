<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    enrollments: Array
});

const form = useForm({});

const updateStatus = (id, status) => {
    form.put(route('enrollments.update-status', id), {
        payment_status: status === 'paid' ? 'unpaid' : 'paid'
    });
};
</script>

<template>
    <Head title="Enrollments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Enrollments
                </h2>
            </div>
        </template>

        <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6">
                    <div class="mb-6 flex justify-between">
                            <h2 class="text-2xl font-semibold">Enrollments</h2>
                            <Link
                                :href="route('enrollments.create')"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-800 dark:active:bg-gray-300"
                            ><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                                New Enrollment
                            </Link>
                        </div>
                    <div class="overflow-x-auto relative">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Student
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Course
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Enrollment Date
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Payment Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800">
                                        <tr v-for="enrollment in enrollments" 
                                            :key="enrollment.id"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ enrollment.user.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ enrollment.course.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ new Date(enrollment.created_at).toLocaleDateString() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button 
                                                    @click="updateStatus(enrollment.id, enrollment.payment_status)"
                                                    :class="[
                                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                        enrollment.payment_status === 'paid' 
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100'
                                                            : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100'
                                                    ]"
                                                >
                                                    {{ enrollment.payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>