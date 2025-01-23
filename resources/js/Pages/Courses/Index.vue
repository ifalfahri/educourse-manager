<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import axios from "axios";

const props = defineProps({
    courses: Object,
    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: '',
            price_range: '',
            sort_by: '',
            sort_direction: '',
        }),
    }
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const priceRange = ref(props.filters.price_range || '');
const sortBy = ref(props.filters.sort_by || '');
const sortDirection = ref(props.filters.sort_direction || '');

// Debounced search
const debouncedSearch = debounce((value) => {
    router.get(
        route('courses.index'),
        { 
            search: value, 
            status: status.value,
            price_range: priceRange.value,
            sort_by: sortBy.value,
            sort_direction: sortDirection.value
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}, 300);

// Watch for search input changes
watch(search, (value) => {
    debouncedSearch(value);
});

// Handle filter changes
const updateFilters = () => {
    router.get(
        route('courses.index'),
        { 
            search: search.value,
            status: status.value,
            price_range: priceRange.value,
            sort_by: sortBy.value,
            sort_direction: sortDirection.value
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

// Handle sorting
const sort = (field) => {
    sortBy.value = field;
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    updateFilters();
};

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
    file: null,
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append("file", file);

        form.clearErrors();
        form.file = file;
        submitImport();
    }
};

const submitImport = () => {
    if (!form.file) return;

    form.post(route("courses.import"), {
        preserveScroll: true,
        preserveFiles: true,
        onSuccess: () => {
            fileInput.value.value = ""; // Clear the input
            form.reset(); // Reset the form
        },
        onError: (errors) => {
            console.error("Import errors:", errors);
        },
    });
};

const downloadExcel = async () => {
    try {
        const response = await axios.get(route('courses.export'), {
            responseType: 'blob'
        });
        
        const blob = new Blob([response.data], { 
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });
        
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        const fileName = `courses_${new Date().toISOString().slice(0,10)}.xlsx`;
        
        link.href = url;
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        
        // Cleanup
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    } catch (error) {
        console.error('Download failed:', error);
    }
};

const printPdf = async () => {
    try {
        const response = await axios.get(route("courses.print"), {
            responseType: "blob"
        });

        const blob = new Blob([response.data], { type: "application/pdf" });
        const url = window.URL.createObjectURL(blob);
        
        // Open PDF in new tab instead of downloading
        window.open(url, '_blank');
        
        // Cleanup
        setTimeout(() => {
            window.URL.revokeObjectURL(url);
        }, 1000);
    } catch (error) {
        console.error("PDF generation failed:", error);
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

                    <form
                        @submit.prevent="submitImport"
                        enctype="multipart/form-data"
                        class="inline"
                    >
                        <input
                            type="file"
                            ref="fileInput"
                            class="hidden"
                            @change="handleFileChange"
                            accept=".xlsx,.xls"
                        />
                        <SecondaryButton
                            type="button"
                            @click="$refs.fileInput.click()"
                        >
                            Import
                        </SecondaryButton>
                        <div
                            v-if="form.errors.file"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.file }}
                        </div>
                    </form>

                    <Dropdown align="right" width="36">
        <template #trigger>
            <PrimaryButton>
                Export
                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </PrimaryButton>
        </template>

        <template #content>
            <DropdownLink as="button" @click="downloadExcel">
                Export as Excel
            </DropdownLink>
            <DropdownLink as="button" @click="printPdf">
                Print PDF
            </DropdownLink>
        </template>
    </Dropdown>
                </div>
            </div>
        </template>

        <div class="p-4 md:py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6">
                        
                        <div class="mb-6 flex justify-between">
                            <h2 class="text-2xl font-semibold">Courses</h2>
                            <Link
                                :href="route('courses.create')"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-800 dark:active:bg-gray-300"
                            ><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                                Add Course
                            </Link>
                        </div>

                       <!-- Search and Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search with icon -->
        <div class="md:col-span-2 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input
                type="text"
                v-model="search"
                placeholder="Search courses..."
                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
            />
        </div>

        <!-- Status Filter -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
            </div>
            <select
                v-model="status"
                @change="updateFilters"
                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
            >
                <option value="">All Status</option>
                <option value="true">Active</option>
                <option value="false">Inactive</option>
            </select>
        </div>

        <!-- Price Range Filter -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                </svg>
            </div>
            <select
                v-model="priceRange"
                @change="updateFilters"
                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
            >
                <option value="">All Prices</option>
                <option value="0-1000000">Under 1M</option>
                <option value="1000000-2000000">1M - 2M</option>
                <option value="2000000-99999999">Over 2M</option>
            </select>
        </div>
    </div>

                        <!-- Courses Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th @click="sort('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Name
                                            <span v-if="sortBy === 'name'" class="ml-1">
                                                {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th @click="sort('price')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Price
                                            <span v-if="sortBy === 'price'" class="ml-1">
                                                {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th @click="sort('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Status
                                            <span v-if="sortBy === 'status'" class="ml-1">
                                                {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th @click="sort('students_count')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                            Enrolled Students
                                            <span v-if="sortBy === 'students_count'" class="ml-1">
                                                {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <tr v-for="course in courses.data" 
                                        :key="course.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="#" @click.prevent="showCourse(course)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                {{ course.name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ new Intl.NumberFormat('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                maximumFractionDigits: 0
                                            }).format(course.price) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                course.status ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100'
                                            ]">
                                                {{ course.status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ course.students_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            <Link 
                                                :href="route('courses.edit', course.id)"
                                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
                                            >
                                                Edit
                                            </Link>
                                            <DangerButton @click="deleteCourse(course.id)">
                                                Delete
                                            </DangerButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="courses.links.length > 3" class="mt-4">
                            <div class="flex flex-wrap justify-center gap-2">
                                <template v-for="(link, i) in courses.links" :key="i">
                                    <div 
                                        v-html="link.label"
                                        @click="link.url && router.get(link.url, 
                                            { 
                                                search: search,
                                                status: status,
                                                price_range: priceRange,
                                                sort_by: sortBy,
                                                sort_direction: sortDirection
                                            }, 
                                            {
                                                preserveState: true,
                                                preserveScroll: true,
                                                replace: true
                                            }
                                        )"
                                        :class="[
                                            'px-3 py-1 rounded text-sm',
                                            link.active ? 'bg-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100',
                                            link.url ? 'cursor-pointer' : 'cursor-default opacity-50'
                                        ]"
                                    />
                                </template>
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
                                                    selectedCourse.students_count
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
        
    </AuthenticatedLayout>
</template>
