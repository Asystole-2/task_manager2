<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const page = usePage()

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

// Redirect if already logged in
onMounted(() => {
    if (page.props.auth.user) {
        router.visit('/dashboard');
    }
});
</script>

<template>
    <Head title="Welcome to Task Manager" />
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="relative overflow-hidden">
            <!-- Header -->
            <header class="relative pt-6 px-4 sm:px-6 lg:px-8">
                <nav class="relative flex items-center justify-between sm:h-10">
                    <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                        <div class="flex items-center justify-between w-full md:w-auto">
                            <Link :href="route('home')">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-500">TaskMaster</span>
                            </Link>
                        </div>
                    </div>
                    <div class="hidden md:block md:ml-10 md:pr-4">
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="font-medium text-purple-600 hover:text-purple-500 dark:text-purple-400 dark:hover:text-purple-300 transition duration-150 ease-in-out"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="ml-8 font-medium text-orange-600 hover:text-orange-500 dark:text-orange-400 dark:hover:text-orange-300 transition duration-150 ease-in-out"
                        >
                            Register
                        </Link>
                    </div>
                </nav>
            </header>

            <!-- Main content -->
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                        <span class="block">Organize Your Work</span>
                        <span class="block text-red-600 dark:text-red-500">With TaskMaster</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 dark:text-gray-400 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        A beautiful task management system with boards, calendars, and todo lists.
                        Built with Laravel and Vue.js for maximum productivity.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 md:py-4 md:text-lg md:px-10"
                            >
                                Get Started
                            </Link>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 md:py-4 md:text-lg md:px-10 dark:text-red-300 dark:bg-red-900 dark:hover:bg-red-800"
                            >
                                Create Account
                            </Link>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Feature section -->
            <div class="py-12 bg-white dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center">
                        <h2 class="text-base text-red-600 dark:text-red-400 font-semibold tracking-wide uppercase">Features</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                            Everything you need to stay organized
                        </p>
                    </div>

                    <div class="mt-10">
                        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Task Boards -->
                            <div class="pt-6">
                                <div class="flow-root bg-gray-50 dark:bg-gray-700 rounded-lg px-6 pb-8">
                                    <div class="-mt-6">
                                        <div>
                                            <span class="inline-flex items-center justify-center p-3 bg-purple-500 rounded-md shadow-lg">
                                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                                </svg>
                                            </span>
                                        </div>
                                        <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight">Task Boards</h3>
                                        <p class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                            Organize your work into customizable boards with drag-and-drop functionality.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Calendar -->
                            <div class="pt-6">
                                <div class="flow-root bg-gray-50 dark:bg-gray-700 rounded-lg px-6 pb-8">
                                    <div class="-mt-6">
                                        <div>
                                            <span class="inline-flex items-center justify-center p-3 bg-red-500 rounded-md shadow-lg">
                                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight">Calendar View</h3>
                                        <p class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                            Visualize your deadlines and schedule with our integrated calendar.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Todo Lists -->
                            <div class="pt-6">
                                <div class="flow-root bg-gray-50 dark:bg-gray-700 rounded-lg px-6 pb-8">
                                    <div class="-mt-6">
                                        <div>
                                            <span class="inline-flex items-center justify-center p-3 bg-orange-500 rounded-md shadow-lg">
                                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            </span>
                                        </div>
                                        <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight">Todo Lists</h3>
                                        <p class="mt-5 text-base text-gray-500 dark:text-gray-400">
                                            Create and manage todo lists with priorities and due dates.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-50 dark:bg-gray-800">
                <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
                    <p class="mt-8 text-center text-base text-gray-400 dark:text-gray-500">
                        &copy; 2023 TaskMaster. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped>
/* Custom transitions */
.enter-active-class,
.leave-active-class {
    transition: opacity 0.2s;
}
.enter-class,
.leave-to-class {
    opacity: 0;
}
</style>
