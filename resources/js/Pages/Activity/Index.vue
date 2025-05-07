<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    activities: Array,
    projects: Array,
    tasks: Array,
});
</script>

<template>
    <Head title="Activity Feed" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    Recent Activity
                </h2>
                <Link
                    :href="route('dashboard')"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                >
                    Back to Dashboard
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Activity Feed -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activities</h3>
                    </div>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li v-for="activity in activities" :key="activity.id" class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                    <span class="text-red-600 dark:text-red-300">
                                        {{ activity.initials }}
                                    </span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ activity.user?.name ?? 'System' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ activity.time_ago }}
                                        </p>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        {{ activity.description }}
                                    </p>
                                    <div v-if="activity.task" class="mt-2">
                                        <Link
                                            :href="route('tasks.show', activity.task.id)"
                                            class="text-sm text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            View Task
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Projects Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Projects</h3>
                            <Link
                                :href="route('ProjectManagement.index')"
                                class="text-sm font-medium text-orange-600 hover:text-orange-500 dark:text-orange-400 dark:hover:text-orange-300"
                            >
                                View All
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div v-if="projects.length > 0" class="space-y-4">
                            <div
                                v-for="project in projects"
                                :key="`project-${project.id}`"
                                class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0"
                            >
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ project.name }}</h4>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">{{ project.description }}</p>
                                <Link
                                    :href="route('ProjectManagement.show', project.id)"
                                    class="mt-2 inline-flex text-sm font-medium text-orange-600 hover:text-orange-500 dark:text-orange-400 dark:hover:text-orange-300"
                                >
                                    View Project
                                </Link>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500 dark:text-gray-400 py-4">
                            No projects yet
                        </div>
                    </div>
                </div>

                <!-- Tasks Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Tasks</h3>
                            <Link
                                :href="route('tasks.index')"
                                class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300"
                            >
                                View All
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div v-if="tasks.length > 0" class="space-y-4">
                            <div
                                v-for="task in tasks"
                                :key="`task-${task.id}`"
                                class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0"
                            >
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ task.title }}</h4>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ task.description }}</p>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status: {{ task.status }}</span>
                                    <Link
                                        :href="route('tasks.show', task.id)"
                                        class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300"
                                    >
                                        View Task
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500 dark:text-gray-400 py-4">
                            No tasks yet
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
