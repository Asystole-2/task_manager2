<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import TaskList from '@/Components/TaskList.vue';

defineProps({
    tasks: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    pendingTasksCount: {
        type: Number,
        default: 0
    },
});
</script>

<template>
    <Head title="My Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    My Tasks
                </h2>
                <div class="flex space-x-4">
                    <Link
                        :href="route('tasks.create')"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md"
                    >
                        + New Task
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Summary Card -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-medium text-gray-500 dark:text-gray-400">Pending Tasks</h3>
                        <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">
                            {{ pendingTasksCount }}
                        </p>
                    </div>
                </div>

                <!-- Task List -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <TaskList :tasks="tasks" :filters="filters" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
