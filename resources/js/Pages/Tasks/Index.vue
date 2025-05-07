<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

defineProps({
    tasks: Array,
    filters: Object,
    pendingTasksCount: Number,
});

const deleteTask = (id) => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(route('tasks.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Success handling
            },
        });
    }
};

const priorityColors = {
    high: 'bg-red-100 text-red-800',
    medium: 'bg-yellow-100 text-yellow-800',
    low: 'bg-green-100 text-green-800',
    critical: 'bg-purple-100 text-purple-800'
};

const statusColors = {
    pending: 'bg-gray-100 text-gray-800',
    in_progress: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800'
};

const sortField = ref('due_date');
const sortDirection = ref('asc');

const sortTasks = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
    router.get(route('tasks.index'), {
        sort_by: sortField.value,
        sort_direction: sortDirection.value
    }, {
        preserveState: true,
        replace: true
    });
};
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-500">
                    Task Management
                </h2>
                <div class="flex space-x-4">
                    <Link
                        :href="route('tasks.create')"
                        class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create Task
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                    <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tasks</p>
                                <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ tasks.length }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-50 dark:bg-blue-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Tasks</p>
                                <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ pendingTasksCount }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-50 dark:bg-yellow-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed Tasks</p>
                                <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ tasks.filter(t => t.status === 'completed').length }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-50 dark:bg-green-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Table -->
                <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Task List</h3>
                            <div class="flex space-x-3">
                                <input
                                    type="text"
                                    placeholder="Search tasks..."
                                    class="px-4 py-2 text-sm border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                />
                                <select class="px-4 py-2 text-sm border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option>All Status</option>
                                    <option>Pending</option>
                                    <option>In Progress</option>
                                    <option>Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                    @click="sortTasks('title')"
                                >
                                    <div class="flex items-center">
                                        Title
                                        <svg
                                            v-if="sortField === 'title'"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 ml-1"
                                            :class="sortDirection === 'asc' ? 'rotate-0' : 'rotate-180'"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                    @click="sortTasks('status')"
                                >
                                    <div class="flex items-center">
                                        Status
                                        <svg
                                            v-if="sortField === 'status'"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 ml-1"
                                            :class="sortDirection === 'asc' ? 'rotate-0' : 'rotate-180'"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                    @click="sortTasks('priority')"
                                >
                                    <div class="flex items-center">
                                        Priority
                                        <svg
                                            v-if="sortField === 'priority'"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 ml-1"
                                            :class="sortDirection === 'asc' ? 'rotate-0' : 'rotate-180'"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                    @click="sortTasks('due_date')"
                                >
                                    <div class="flex items-center">
                                        Due Date
                                        <svg
                                            v-if="sortField === 'due_date'"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 ml-1"
                                            :class="sortDirection === 'asc' ? 'rotate-0' : 'rotate-180'"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            <tr v-for="task in tasks" :key="task.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-2 h-2 mr-3 rounded-full" :class="{
                                                'bg-red-500': task.priority === 'high' || task.priority === 'critical',
                                                'bg-yellow-500': task.priority === 'medium',
                                                'bg-green-500': task.priority === 'low'
                                            }"></div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ task.title }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">{{ task.description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full" :class="statusColors[task.status]">
                                            {{ task.status.replace('_', ' ') }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full" :class="priorityColors[task.priority]">
                                            {{ task.priority }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-300">
                                    {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date' }}
                                    <span v-if="task.due_date" class="block text-xs" :class="{
    'text-red-500': new Date(task.due_date) < new Date(),
    'text-yellow-500': new Date(task.due_date) >= new Date() && new Date(task.due_date) <= new Date(new Date().setDate(new Date().getDate() + 3)),
    'text-green-500': new Date(task.due_date) > new Date(new Date().setDate(new Date().getDate() + 3))
}">
    {{
                                            new Date(task.due_date) < new Date()
                                                ? 'Overdue'
                                                : `Due in ${Math.ceil((new Date(task.due_date) - new Date()) / (1000 * 60 * 60 * 24))} days`
                                        }}
</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <Link
                                            :href="route('tasks.edit', task.id)"
                                            class="flex items-center px-3 py-1 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </Link>
                                        <button
                                            @click="deleteTask(task.id)"
                                            class="flex items-center px-3 py-1 text-sm text-white bg-red-600 rounded-md hover:bg-red-700"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="tasks.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No tasks found. Create your first task!
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-700">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ tasks.length }}</span> results
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-sm border rounded-md dark:border-gray-600 dark:text-gray-300" disabled>
                                Previous
                            </button>
                            <button class="px-3 py-1 text-sm border rounded-md dark:border-gray-600 dark:text-gray-300">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
