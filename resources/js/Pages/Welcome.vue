<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const page = usePage();

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

const features = [
    {
        icon: 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2',
        title: 'Task Boards',
        description: 'Organize your work into customizable boards with drag-and-drop functionality.',
        color: 'purple'
    },
    {
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        title: 'Calendar View',
        description: 'Visualize your deadlines and schedule with our integrated calendar.',
        color: 'red'
    },
    {
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        title: 'Todo Lists',
        description: 'Create and manage todo lists with priorities and due dates.',
        color: 'orange'
    }
];
</script>

<template>
    <Head title="Welcome to TaskMaster" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <!-- Background Blur Circles -->
        <div class="fixed inset-0 overflow-hidden z-0">
            <div class="absolute top-0 left-1/4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:bg-purple-800 dark:opacity-10"></div>
            <div class="absolute top-0 right-1/4 w-72 h-72 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:bg-red-800 dark:opacity-10"></div>
            <div class="absolute bottom-8 left-1/2 w-72 h-72 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:bg-orange-800 dark:opacity-10"></div>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <header class="fixed top-0 left-0 right-0 z-50 bg-white/70 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <nav class="flex items-center justify-between">
                        <Link :href="route('home')" class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-purple-600 dark:from-red-400 dark:to-purple-400">
                            TaskMaster
                        </Link>
                        <div class="flex items-center space-x-4">
                            <Link
                                :href="route('guest.view')"
                                class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition"
                            >Guest View</Link>
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="px-4 py-2 rounded-lg text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-gray-700 transition"
                            >Log in</Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="px-4 py-2 rounded-lg text-white bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 shadow-md transition"
                            >Get Started</Link>
                        </div>
                    </nav>
                </div>
            </header>

            <!-- Hero Section -->
            <main class="pt-36 pb-20 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="text-center md:text-left animate-fade-in">
                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                            <span class="block">Organize Your Work</span>
                            <span class="block gradient-text">With TaskMaster</span>
                        </h1>
                        <p class="mt-6 text-xl text-gray-600 dark:text-gray-300">
                            A beautiful task management system with boards, calendars, and todo lists.
                        </p>
                        <div class="mt-10 flex justify-center md:justify-start gap-4">
                            <Link
                                :href="route('guest.view')"
                                class="px-8 py-3 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600 transition"
                            >Try Guest View</Link>
                            <Link
                                v-if="canLogin"
                                :href="route('login')"
                                class="px-8 py-3 rounded-lg text-white bg-red-600 hover:bg-red-700 transition"
                            >Sign In</Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="px-8 py-3 rounded-lg text-red-700 bg-red-100 hover:bg-red-200 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600 transition"
                            >Register</Link>
                        </div>
                    </div>
                    <div class="animate-fade-in delay-200">
                        <img src="/images/hero-image.jpg" alt="Hero Image" />

                    </div>
                </div>
            </main>

            <!-- Features -->
            <section class="py-24 bg-white dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-base font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider">
                            Features
                        </h2>
                        <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">
                            Everything you need to stay organized
                        </p>
                    </div>

                    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div
                            v-for="(feature, index) in features"
                            :key="feature.title"
                            class="animate-fade-in"
                            :class="`delay-${index * 200}`"
                        >
                            <div class="bg-white dark:bg-gray-700 rounded-xl px-6 py-8 h-full shadow-lg hover:shadow-xl transition">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md" :class="`bg-${feature.color}-500 text-white`">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                                    </svg>
                                </div>
                                <h3 class="mt-6 text-lg font-medium text-gray-900 dark:text-white text-center">
                                    {{ feature.title }}
                                </h3>
                                <p class="mt-4 text-gray-500 dark:text-gray-300 text-center">
                                    {{ feature.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center">
                    <p class="text-base text-gray-400 dark:text-gray-500">
                        &copy; {{ new Date().getFullYear() }} TaskMaster. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>
</template>

<style scoped>
.gradient-text {
    background: linear-gradient(to right, #f43f5e, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradientShift 6s ease infinite;
    background-size: 200% 200%;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease forwards;
    opacity: 0;
}

.delay-0 { animation-delay: 0s; }
.delay-200 { animation-delay: 0.2s; }
.delay-400 { animation-delay: 0.4s; }
.delay-600 { animation-delay: 0.6s; }
</style>
