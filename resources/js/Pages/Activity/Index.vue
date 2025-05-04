<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    activities: Array,
});
</script>

<template>
    <Head title="Activity Feed" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                Recent Activity
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li v-for="activity in activities" :key="activity.id" class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                    <span class="text-red-600 dark:text-red-300">
                                        {{ activity.initials }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ activity.description }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ activity.time_ago }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
