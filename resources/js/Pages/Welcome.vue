<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
    navigation,
    socialLinks,
    features,
    testimonials,
    faqs 
} from '@/constants';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    }
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 dark:bg-black">
        <div class="relative min-h-screen">
            <!-- Header -->
            <header class="fixed w-full bg-white/80 backdrop-blur-md shadow-sm dark:bg-gray-800/80 z-50">
                <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        <div class="flex items-center">
                            <a class="text-xl font-bold text-gray-900 hover:text-indigo-600 dark:text-white transition-colors" href="#hero">EduCourse Manager</a>
                            <div class="hidden text-sm font-medium md:flex ml-10 space-x-8">
                                <a v-for="item in navigation" 
                                :key="item.name"
                                :href="item.href"
                                class="text-gray-700 hover:text-gray-900 dark:text-gray-300 transition-colors">
                                    {{ item.name }}
                                </a>
                            </div>
                        </div>
                        <div v-if="canLogin" class="flex items-center gap-4">
                            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition-colors">
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link :href="route('login')"
                                    class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300">
                                    Log in
                                </Link>
                                <Link v-if="canRegister" :href="route('register')"
                                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition-colors">
                                    Register
                                </Link>
                            </template>
                        </div>
                    </div>
                </nav>
            </header>

            <main>
                <!-- Hero Section -->
                <section id="hero" class="relative isolate">
                    <div class="md:h-screen flex justify-center items-center mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                        <div class="mx-auto max-w-2xl text-center">
                            <div class="flex justify-center mb-6 md:mb-10 transition-all hover:text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-12">
                                    <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                    <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                    <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                                </svg>
                            </div>
                            <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                                Manage Your <span class="text-indigo-600">Courses</span> with Ease
                            </h1>
                            <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                                A comprehensive solution for educational institutions to manage courses, 
                                students, and enrollments efficiently.
                            </p>
                            <div class="mt-10 flex items-center justify-center gap-x-6">
                                <Link v-if="canRegister" :href="route('register')"
                                    class="rounded-md bg-indigo-600 px-6 py-3 text-lg font-semibold text-white hover:bg-indigo-500 transition-colors">
                                    Get Started
                                </Link>
                                <Link href="https://github.com/ifalfahri/educourse-manager" class="text-lg font-semibold hover:animate-bounce leading-6 text-gray-900 dark:text-white">
                                    Learn more <span aria-hidden="true">→</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Features Section -->
            <section id="features" class="bg-white py-24 dark:bg-gray-800 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl lg:text-center">
                        <h2 class="text-base font-semibold leading-7 text-indigo-600">Features</h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                            Everything you need to manage your courses
                        </p>
                    </div>
                    <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                        <div class="grid grid-cols-2 gap-x-4 gap-y-4 lg:grid-cols-3">
                            <div v-for="feature in features" :key="feature.name" 
                                class="group relative flex flex-col rounded-2xl border border-gray-200 dark:border-gray-700 p-6 md:p-8 hover:border-indigo-600 dark:hover:border-indigo-500 transition-all duration-300 hover:shadow-lg">
                                <component :is="feature.icon" 
                                    class="h-8 w-8 text-indigo-600 mb-4 group-hover:scale-110 transition-transform" />
                                <dt class="text-md md:text-lg font-semibold leading-7 text-gray-900 dark:text-white">
                                    {{ feature.name }}
                                </dt>
                                <dd class="hidden md:block mt-4  flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-300">
                                    <p>{{ feature.description }}</p>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section id="about" class="py-24 bg-gray-50 dark:bg-gray-900">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">About EduCourse Manager</h2>
                            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                                EduCourse Manager is built to simplify course management for educational institutions. 
                                Our platform helps you focus on what matters most - delivering quality education. Anyway that data is fake.
                            </p>
                            <div class="mt-8 grid grid-cols-2 gap-4">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                                    <div class="text-2xl font-bold text-indigo-600">1000+</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Active Users</div>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                                    <div class="text-2xl font-bold text-indigo-600">5000+</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Courses Managed</div>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644" 
                                alt="Team meeting" 
                                class="rounded-lg shadow-xl">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="py-24 bg-white dark:bg-gray-800">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">What Our Users Say</h2>
                    </div>
                    <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div v-for="testimonial in testimonials" :key="testimonial.author"
                            class="rounded-2xl bg-gray-50 dark:bg-gray-700 p-8 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex gap-2 text-yellow-400 mb-4">
                                <svg v-for="i in 5" :key="i" class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ testimonial.content }}</p>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ testimonial.author }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ testimonial.role }}, {{ testimonial.company }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="py-24 bg-gray-50 dark:bg-gray-900">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Frequently Asked Questions</h2>
                    </div>
                    <div class="mt-16 max-w-3xl mx-auto">
                        <div v-for="(faq, index) in faqs" :key="index" 
                            class="mb-4 rounded-lg bg-white dark:bg-gray-800 shadow">
                            <details class="group p-6">
                                <summary class="flex cursor-pointer items-center justify-between">
                                    <h2 class="font-medium text-gray-900 dark:text-white">
                                        {{ faq.question }}
                                    </h2>
                                    <span class="ml-6 flex-shrink-0 text-gray-400 group-open:rotate-180 transition-transform">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </summary>
                                <p class="mt-4 text-gray-600 dark:text-gray-300">
                                    {{ faq.answer }}
                                </p>
                            </details>
                        </div>
                    </div>
                </div>
            </section>
        </main>

            <!-- Footer -->
            <footer class="bg-indigo-600 dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <div class="flex justify-between gap-6">
                <!-- Company Info -->
                <div>
                    <h3 class="text-lg font-semibold text-white">EduCourse Manager</h3>
                    <p class="mt-4 text-sm text-white">
                        Simplifying course management for educational institutions worldwide. 
                        Powered by Laravel Inertia Vue.
                    </p>
                </div>
                <!-- Navigation -->
                <div>
                    <h3 class="text-lg font-semibold text-white">Navigation</h3>
                    <div class="mt-4 flex flex-col space-y-2">
                        <a v-for="item in navigation" 
                           :key="item.name"
                           :href="item.href"
                           class="text-sm text-white hover:text-gray-300 transition-colors duration-200 dark:text-gray-400 dark:hover:text-white">
                            {{ item.name }}
                        </a>
                    </div>
                </div>
                <!-- Social Links -->
                <div>
                    <h3 class="text-lg font-semibold text-white">Connect</h3>
                    <div class="mt-4 flex flex-col space-y-2">
                        <a v-for="social in socialLinks" 
                           :key="social.name"
                           :href="social.href"
                           class="text-sm text-white hover:text-gray-300 transition-colors duration-200 dark:text-gray-400 dark:hover:text-white"
                           target="_blank"
                           rel="noopener noreferrer">
                            {{ social.name }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 border-t-2 border-gray-200 dark:border-gray-700 pt-8">
                <p class="text-center text-sm text-white dark:text-gray-400">
                    Made with ❤️ by <a href="https://ifal.me" class="hover:text-gray-400 transition-colors dark:hover:text-white">Ifal Fahri A</a>
                </p>
                <p class="text-center text-xs text-white mt-2">
                    © {{ new Date().getFullYear() }} EduCourse Manager. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
        </div>
    </div>
</template>