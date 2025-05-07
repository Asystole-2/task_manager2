<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const features = [
    {
        title: 'Project Management',
        description: 'Organize and track all your projects in one place.',
        icon: '📊'
    },
    {
        title: 'Task Tracking',
        description: 'Never miss a deadline with our intuitive task system.',
        icon: '✅'
    },
    {
        title: 'Team Collaboration',
        description: 'Work seamlessly with your team members.',
        icon: '👥'
    }
];

const testimonials = [
    {
        quote: "This platform revolutionized our workflow!",
        author: "Alex Johnson, Marketing Director"
    },
    {
        quote: "Simple yet powerful - exactly what we needed.",
        author: "Sarah Williams, Project Manager"
    }
];
</script>

<template>
    <Head title="Welcome to Our Platform" />

    <div class="min-h-screen w-full bg-gray-50 dark:bg-gray-900">
        <!-- Hero Section -->
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    Powerful Project Management <br>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">Made Simple</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Streamline your workflow and boost productivity with our comprehensive solution.
                </p>
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <Link :href="route('register')"
                          class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-500 text-white font-medium rounded-lg shadow-lg hover:opacity-90 transition-all duration-200 transform hover:scale-105">
                        Get Started for Free
                    </Link>
                    <Link :href="route('login')"
                          class="px-8 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-white font-medium rounded-lg shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Sign In
                    </Link>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white dark:bg-gray-800 py-16">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Everything You Need to Succeed</h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Our platform provides all the tools your team needs to work efficiently
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(feature, index) in features" :key="index"
                         class="bg-gray-50 dark:bg-gray-700 p-8 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-600">
                        <div class="text-4xl mb-4">{{ feature.icon }}</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ feature.title }}</h3>
                        <p class="text-gray-600 dark:text-gray-300">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="bg-gray-50 dark:bg-gray-900 py-16">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Trusted by Teams Worldwide</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="(testimonial, index) in testimonials" :key="index"
                         class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
                        <p class="text-gray-700 dark:text-gray-300 italic text-lg mb-6">"{{ testimonial.quote }}"</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ testimonial.author }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Final CTA -->
        <div class="bg-gradient-to-r from-red-50 to-orange-100 dark:from-gray-800 dark:to-gray-900 py-20">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Ready to Transform Your Workflow?</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                    Join thousands of professionals who are already working smarter.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <Link :href="route('register')"
                          class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-500 text-white font-medium rounded-lg shadow-lg hover:opacity-90 transition">
                        Start Your Free Trial
                    </Link>
                    <Link :href="route('login')"
                          class="px-8 py-3 text-gray-700 dark:text-white font-medium rounded-lg hover:text-gray-900 dark:hover:text-gray-200 transition">
                        Learn More →
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Smooth hover transitions */
.hover-scale {
    transition: transform 0.2s ease-in-out;
}
.hover-scale:hover {
    transform: translateY(-2px);
}
</style>
