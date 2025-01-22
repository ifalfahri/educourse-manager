<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    courses: Array,
    students: Array
});

const form = useForm({
    user_id: '',
    course_id: '',
    payment_status: 'unpaid'
});

const submit = () => {
    form.post(route('enrollments.store'), {
        onSuccess: () => form.reset()
    });
};
</script>

<template>
    <Head title="Create Enrollment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                New Enrollment
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <form @submit.prevent="submit">
                        <div class="grid gap-6">
                            <!-- Student Selection -->
                            <div>
                                <InputLabel for="user_id" value="Student" />
                                <select
                                    id="user_id"
                                    v-model="form.user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Select a student</option>
                                    <option v-for="student in students" :key="student.id" :value="student.id">
                                        {{ student.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.user_id" class="mt-2" />
                            </div>

                            <!-- Course Selection -->
                            <div>
                                <InputLabel for="course_id" value="Course" />
                                <select
                                    id="course_id"
                                    v-model="form.course_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Select a course</option>
                                    <option v-for="course in courses" :key="course.id" :value="course.id">
                                        {{ course.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.course_id" class="mt-2" />
                            </div>

                            <!-- Payment Status -->
                            <div>
                                <InputLabel for="payment_status" value="Payment Status" />
                                <select
                                    id="payment_status"
                                    v-model="form.payment_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid">Paid</option>
                                </select>
                                <InputError :message="form.errors.payment_status" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Create Enrollment
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>