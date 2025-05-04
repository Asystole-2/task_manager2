<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tasks: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
});

const deleteTask = (id) => {
    if (!id) return;
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(route('tasks.destroy', id));
    }
};

const updateStatus = (id, status) => {
    if (!id) return;
    router.patch(route('tasks.update', id), {
        status: status
    });
};

const toggleComplete = (task) => {
    if (!task?.id) return;
    const newStatus = task.status === 'completed' ? 'pending' : 'completed';
    updateStatus(task.id, newStatus);
};

const sortBy = ref(props.filters?.sort_by || 'due_date');
const sortDirection = ref(props.filters?.sort_direction || 'asc');

const sortTasks = (field) => {
    if (sortBy.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = field;
        sortDirection.value = 'asc';
    }
    router.get(route('tasks.index'), {
        sort_by: sortBy.value,
        sort_direction: sortDirection.value,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <div class="space-y-4">
        <!-- Sorting controls -->
        <div class="flex flex-wrap gap-2 text-sm">
            <button @click="sortTasks('title')"
                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                    :class="{ 'bg-gray-200 dark:bg-gray-600': sortBy === 'title' }">
                Sort by Title
                <span v-if="sortBy === 'title'" class="ml-1">
                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
            </button>
            <button @click="sortTasks('priority')"
                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                    :class="{ 'bg-gray-200 dark:bg-gray-600': sortBy === 'priority' }">
                Sort by Priority
                <span v-if="sortBy === 'priority'" class="ml-1">
                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
            </button>
            <button @click="sortTasks('due_date')"
                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                    :class="{ 'bg-gray-200 dark:bg-gray-600': sortBy === 'due_date' }">
                Sort by Due Date
                <span v-if="sortBy === 'due_date'" class="ml-1">
                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                </span>
            </button>
        </div>

        <div v-for="task in tasks" :key="task?.id" v-if="task"
             class="p-4 border rounded-lg shadow-sm hover:shadow-md transition-shadow"
             :class="{
                'border-l-4 border-red-500': task.priority === 'high',
                'border-l-4 border-purple-500': task.priority === 'medium',
                'border-l-4 border-orange-500': task.priority === 'low',
                'bg-gray-50 dark:bg-gray-700/50': task.status === 'completed',
                'opacity-80': task.status === 'completed'
             }">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-start gap-2">
                        <button @click="toggleComplete(task)"
                                class="p-1 rounded-full mt-0.5 flex-shrink-0"
                                :class="{
                                    'text-green-500 hover:bg-green-100 dark:hover:bg-green-900/50': task.status === 'completed',
                                    'text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600': task.status !== 'completed'
                                }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div>
                            <h4 class="font-medium" :class="{
                                'text-red-500': task.priority === 'high',
                                'text-purple-500': task.priority === 'medium',
                                'text-orange-500': task.priority === 'low',
                                'line-through': task.status === 'completed'
                            }">
                                {{ task.title }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400" :class="{ 'line-through': task.status === 'completed' }">
                                {{ task.description }}
                            </p>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                <span class="px-2 py-1 rounded-full flex items-center" :class="{
                                    'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200': task.priority === 'high',
                                    'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200': task.priority === 'medium',
                                    'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200': task.priority === 'low'
                                }">
                                    <svg v-if="task.priority === 'high'" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                                    </svg>
                                    <svg v-else-if="task.priority === 'medium'" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    {{ task.priority }}
                                </span>
                                <span v-if="task.due_date" class="text-gray-500 dark:text-gray-400 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    Due: {{ new Date(task.due_date).toLocaleDateString() }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full" :class="{
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200': task.status === 'pending',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200': task.status === 'in_progress',
                                    'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200': task.status === 'completed'
                                }">
                                    {{ task.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <select
                        @change="updateStatus(task.id, $event.target.value)"
                        class="text-xs border rounded px-2 py-1 bg-white dark:bg-gray-800"
                        :class="{
                            'border-green-200 bg-green-50 dark:bg-green-900/30': task.status === 'completed',
                            'border-blue-200 bg-blue-50 dark:bg-blue-900/30': task.status === 'in_progress',
                            'border-gray-200 bg-gray-50 dark:bg-gray-700': task.status === 'pending',
                        }"
                    >
                        <option value="pending" :selected="task.status === 'pending'">Pending</option>
                        <option value="in_progress" :selected="task.status === 'in_progress'">In Progress</option>
                        <option value="completed" :selected="task.status === 'completed'">Completed</option>
                    </select>
                    <button @click="deleteTask(task.id)"
                            class="text-red-500 hover:text-red-700 dark:hover:text-red-400 p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="tasks.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
            No tasks found. Create your first task to get started!
        </div>

        <Link :href="route('tasks.create')"
              class="block mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 dark:hover:bg-red-700 transition shadow-md flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add New Task
        </Link>
    </div>
</template>
