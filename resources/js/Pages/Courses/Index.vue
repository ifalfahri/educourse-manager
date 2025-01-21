<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import axios from 'axios';

const props = defineProps({
    courses: {
        type: Array,
        required: true,
    },
});

const selectedCourse = ref(null);
const showModal = ref(false);

const showCourse = (course) => {
    selectedCourse.value = course;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedCourse.value = null;
};

const deleteCourse = (id) => {
    if (confirm("Are you sure you want to delete this course?")) {
        router.delete(route("courses.destroy", id));
    }
};

const fileInput = ref(null);
const form = useForm({
    file: null
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('file', file);
        
        form.clearErrors();
        form.file = file;
        submitImport();
    }
};

const submitImport = () => {
    if (!form.file) return;

    form.post(route('courses.import'), {
        preserveScroll: true,
        preserveFiles: true,
        onSuccess: () => {
            fileInput.value.value = ''; // Clear the input
            form.reset(); // Reset the form
        },
        onError: (errors) => {
            console.error('Import errors:', errors);
        }
    });
};

const downloadExcel = async () => {
    try {
        const response = await axios.get(route('courses.export'));
        const link = document.createElement('a');
        link.href = response.data.file;
        link.setAttribute('download', 'courses.xlsx');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error('Download failed:', error);
    }
};
</script>

<template>
    <Head title="Courses" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Courses
            </h2>
            <div class="flex gap-2">
        <!-- Import Button -->
        <form @submit.prevent="submitImport" enctype="multipart/form-data" class="inline">
            <input
                type="file"
                ref="fileInput"
                class="hidden"
                @change="handleFileChange"
                accept=".xlsx,.xls"
            >
            <SecondaryButton
                type="button"
                @click="$refs.fileInput.click()"
            >
                Import Excel
            </SecondaryButton>
            <div v-if="form.errors.file" class="text-red-500 text-sm mt-1">
    {{ form.errors.file }}
</div>
        </form>

        <!-- Export Button -->
        <button
            @click="downloadExcel"
            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >
            Export Excel
        </button>
    </div>
</div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6">
                        <div class="mb-6 flex justify-between">
                            <h2 class="text-2xl font-semibold">Courses</h2>
                            <Link
                                :href="route('courses.create')"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-800 dark:active:bg-gray-300"
                            >
                                Add Course
                            </Link>
                        </div>

                        <div class="overflow-x-auto relative">
                            <div class="inline-block min-w-full align-middle">
                                <div
                                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg"
                                >
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Name
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Price
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Enrolled Students
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-800"
                                    >
                                        <tr
                                            v-for="course in courses"
                                            :key="course.id"
                                            @click="showCourse(course)"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                        >
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ course.name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                {{
                                                    new Intl.NumberFormat(
                                                        "id-ID",
                                                        {
                                                            style: "currency",
                                                            currency: "IDR",
                                                            maximumFractionDigits: 0,
                                                        }
                                                    ).format(course.price)
                                                }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                <span
                                                    :class="[
                                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                        course.status
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-red-100 text-red-800',
                                                    ]"
                                                >
                                                    {{
                                                        course.status
                                                            ? "Active"
                                                            : "Inactive"
                                                    }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ course.enrolled_students }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap space-x-2"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'courses.edit',
                                                            course.id
                                                        )
                                                    "
                                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
                                                    @click.stop
                                                >
                                                    Edit
                                                </Link>
                                                <DangerButton
                                                    @click.stop="
                                                        deleteCourse(course.id)
                                                    "
                                                >
                                                    Delete
                                                </DangerButton>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>

                        <!-- Course Details Modal -->
                        <Modal :show="showModal" @close="closeModal">
                            <div class="p-6">
                                <div
                                    class="flex items-center justify-between mb-4"
                                >
                                    <h2
                                        class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                                    >
                                        {{ selectedCourse?.name }}
                                    </h2>
                                    <SecondaryButton @click="closeModal">
                                        Close
                                    </SecondaryButton>
                                </div>

                                <div
                                    class="grid gap-6 border-t border-gray-200 pt-6 dark:border-gray-600"
                                    v-if="selectedCourse"
                                >
                                    <div>
                                        <h4
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                        >
                                            Description
                                        </h4>
                                        <p
                                            class="mt-2 text-gray-900 dark:text-gray-100"
                                        >
                                            {{ selectedCourse.description }}
                                        </p>
                                    </div>

                                    <div
                                        class="grid grid-cols-2 gap-6 sm:grid-cols-3"
                                    >
                                        <div>
                                            <h4
                                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                            >
                                                Price
                                            </h4>
                                            <p
                                                class="mt-2 text-gray-900 dark:text-gray-100"
                                            >
                                                {{
                                                    new Intl.NumberFormat(
                                                        "id-ID",
                                                        {
                                                            style: "currency",
                                                            currency: "IDR",
                                                            maximumFractionDigits: 0,
                                                        }
                                                    ).format(
                                                        selectedCourse.price
                                                    )
                                                }}
                                            </p>
                                        </div>

                                        <div>
                                            <h4
                                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                            >
                                                Status
                                            </h4>
                                            <p class="mt-2">
                                                <span
                                                    :class="[
                                                        'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                        selectedCourse.status
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-red-100 text-red-800',
                                                    ]"
                                                >
                                                    {{
                                                        selectedCourse.status
                                                            ? "Active"
                                                            : "Inactive"
                                                    }}
                                                </span>
                                            </p>
                                        </div>

                                        <div>
                                            <h4
                                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                            >
                                                Enrolled Students
                                            </h4>
                                            <p
                                                class="mt-2 text-gray-900 dark:text-gray-100"
                                            >
                                                {{
                                                    selectedCourse.enrolled_students
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
