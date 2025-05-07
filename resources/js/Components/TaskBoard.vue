<script setup>
import { ref, onMounted, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3'; // Fixed: Added Link import
import { usePage } from '@inertiajs/vue3';
import Draggable from 'vuedraggable'; // Fixed: Capitalized to match usage in template

const props = defineProps({
    project: {
        type: Object,
        required: true
    },
    initialTasks: {
        type: Object,
        default: () => ({
            standby: [],
            ongoing: [],
            done: []
        })
    }
});

const tasks = ref({
    standby: props.initialTasks.standby,
    ongoing: props.initialTasks.ongoing,
    done: props.initialTasks.done
});

const statusClasses = {
    standby: 'bg-gray-100 dark:bg-gray-700',
    ongoing: 'bg-blue-100 dark:bg-blue-900',
    done: 'bg-green-100 dark:bg-green-900'
};

const statusTitles = {
    standby: 'Standby',
    ongoing: 'Ongoing',
    done: 'Done'
};

const updateTaskStatus = async (taskId, newStatus) => {
    try {
        await router.put(route('tasks.update-status'), {
            task_id: taskId,
            status: newStatus
        });
    } catch (error) {
        console.error('Error updating task status:', error);
    }
};

const onDragEnd = (event) => {
    if (event.added) {
        const task = event.added.element;
        const newStatus = event.to.dataset.status;
        updateTaskStatus(task.id, newStatus);
    }
};

const formatTimeRemaining = (task) => {
    if (!task.time_remaining) return 'No due date';

    const { days, hours, minutes } = task.time_remaining;
    return `${days}d ${hours}h ${minutes}m left`;
};
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div v-for="status in ['standby', 'ongoing', 'done']" :key="status" class="flex-1">
            <div class="p-4 rounded-lg" :class="statusClasses[status]">
                <h3 class="text-lg font-semibold mb-4">{{ statusTitles[status] }}</h3>

                <Draggable
                    :list="tasks[status]"
                    group="tasks"
                    item-key="id"
                    class="space-y-3 min-h-20"
                    :data-status="status"
                    @end="onDragEnd"
                >
                    <template #item="{ element: task }">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow cursor-move">
                            <div class="flex justify-between items-start">
                                <h4 class="font-medium">{{ task.title }}</h4>
                                <span class="text-xs px-2 py-1 rounded-full"
                                      :class="{
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': task.priority === 'high',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': task.priority === 'medium',
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': task.priority === 'low'
                                    }"
                                >
                                    {{ task.priority }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2">
                                {{ task.description }}
                            </p>

                            <div class="mt-3 flex items-center justify-between text-xs">
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ formatTimeRemaining(task) }}
                                </span>
                                <Link
                                    :href="route('tasks.edit', task.id)"
                                    class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
                                >
                                    Edit
                                </Link>
                            </div>

                            <div v-if="task.assignee" class="mt-2 flex items-center">
                                <div class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs">
                                    {{ task.assignee.name.split(' ').map(n => n[0]).join('') }}
                                </div>
                                <span class="ml-2 text-xs text-gray-600 dark:text-gray-300">
                                    {{ task.assignee.name }}
                                </span>
                            </div>
                        </div>
                    </template>
                </Draggable>
            </div>
        </div>
    </div>
</template>
