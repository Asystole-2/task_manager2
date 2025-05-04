<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    title: '',
    description: '',
    priority: 'medium',
    due_date: '',
});

const submit = () => {
    router.post(route('tasks.store'), form.value);
};
</script>

<template>
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Create New Task</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input
                    v-model="form.title"
                    id="title"
                    type="text"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea
                    v-model="form.description"
                    id="description"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                ></textarea>
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                <select
                    v-model="form.priority"
                    id="priority"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                <input
                    v-model="form.due_date"
                    id="due_date"
                    type="datetime-local"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                />
            </div>

            <div class="flex justify-end space-x-3">
                <Link
                    :href="route('dashboard')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Cancel
                </Link>
                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-700 dark:hover:bg-red-800"
                >
                    Save Task
                </button>
            </div>
        </form>
    </div>
</template>
