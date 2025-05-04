<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    tasks: Array,
});

const deleteTask = (id) => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(route('tasks.destroy', id));
    }
};
</script>

<template>
    <div class="space-y-4">
        <div v-for="task in tasks" :key="task.id" class="p-4 border rounded-lg shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-medium" :class="{
                        'text-red-500': task.priority === 'high',
                        'text-purple-500': task.priority === 'medium',
                        'text-orange-500': task.priority === 'low'
                    }">
                        {{ task.title }}
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ task.description }}</p>
                    <div class="mt-2 flex items-center space-x-2 text-xs">
                        <span class="px-2 py-1 rounded-full" :class="{
                            'bg-red-100 text-red-800': task.priority === 'high',
                            'bg-purple-100 text-purple-800': task.priority === 'medium',
                            'bg-orange-100 text-orange-800': task.priority === 'low'
                        }">
                            {{ task.priority }}
                        </span>
                        <span v-if="task.due_date" class="text-gray-500">
                            Due: {{ new Date(task.due_date).toLocaleDateString() }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button @click="deleteTask(task.id)" class="text-red-500 hover:text-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <Link :href="route('tasks.create')" class="block mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
            Add New Task
        </Link>
    </div>
</template>
