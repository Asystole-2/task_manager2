<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    projects: Array,
});
</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    My Projects
                </h2>
                <Link
                    :href="route('projects.create')"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md"
                >
                    + New Project
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="project in projects" :key="project.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ project.name }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ project.description }}
                            </p>
                        </div>
                        <div class="p-6">
                            <Link
                                :href="route('projects.show', project.id)"
                                class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300"
                            >
                                View Project →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
