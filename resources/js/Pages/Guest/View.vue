<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: GuestLayout,
});
</script>

<template>
    <Head title="Guest View" />

    <div class="space-y-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Guest View</h1>
        <p class="text-gray-600 dark:text-gray-300">
            This is a limited view for guests. To access all features, please
            <Link :href="route('login')" class="text-blue-500 hover:text-blue-700">
                log in
            </Link> or
            <Link :href="route('register')" class="text-blue-500 hover:text-blue-700">
                register
            </Link>.
        </p>

        <!-- Add any guest-accessible content here -->
        <div class="mt-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Sample Content</h2>
            <p class="text-gray-600 dark:text-gray-300">
                This could be a preview of your application's features for guests.
            </p>
        </div>
    </div>
</template>
