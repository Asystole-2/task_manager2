<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import TaskList from '@/Components/TaskList.vue';
import CalendarWidget from '@/Components/CalendarWidget.vue';

defineProps({
    tasks: Array,
    events: Array,
    pendingTasksCount: Number,
    upcomingEventsCount: Number,
    activeProjectsCount: Number,
    projects: Array,  // Added the projects prop to get the list of projects
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <template #header>
            <!-- Success Message -->
            <div v-if="$page.props.flash.success" class="mb-6">
                <div class="px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ $page.props.flash.success }}
                </div>
            </div>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    Task Dashboard
                </h2>
                <div class="flex space-x-4">
                    <Link
                        :href="route('tasks.create')"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md"
                    >
                        + New Task
                    </Link>
                    <Link
                        :href="route('calendar.index')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md"
                    >
                        View Calendar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Pending Tasks</h3>
                        <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">
                            {{ pendingTasksCount }}
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Upcoming Events</h3>
                        <p class="mt-2 text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ upcomingEventsCount }}
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Active Projects</h3>
                        <p class="mt-2 text-3xl font-bold text-orange-600 dark:text-orange-400">
                            {{ activeProjectsCount }}
                        </p>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Task List -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Tasks</h3>
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    {{ tasks.length }} tasks
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <TaskList :tasks="tasks" />
                        </div>
                    </div>

                    <!-- Calendar Widget -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Calendar</h3>
                        </div>
                        <div class="p-6">
                            <CalendarWidget :events="events" />
                        </div>
                    </div>
                </div>

                <!-- Projects Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mt-8">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Projects</h3>
                            <Link
                                :href="route('projects.index')"
                                class="text-sm font-medium text-orange-600 hover:text-orange-500 dark:text-orange-400 dark:hover:text-orange-300"
                            >
                                View All
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div v-for="project in projects.slice(0, 3)" :key="project.id" class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ project.name }}</h4>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">{{ project.description }}</p>
                                <Link
                                    :href="route('projects.show', project.id)"
                                    class="mt-2 inline-flex text-sm font-medium text-orange-600 hover:text-orange-500 dark:text-orange-400 dark:hover:text-orange-300"
                                >
                                    View Project
                                </Link>
                            </div>
                            <div v-if="projects.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-4">
                                No projects yet
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
