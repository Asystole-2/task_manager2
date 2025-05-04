<script setup>
import { ref } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { router } from '@inertiajs/vue3';

dayjs.extend(relativeTime);

const props = defineProps({
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

function formatDate(date) {
    return dayjs(date).format('MMM D, YYYY');
}

function daysLeft(date) {
    const now = dayjs();
    const due = dayjs(date);
    const diff = due.diff(now, 'day');
    return diff >= 0 ? `${diff} day(s) left` : `Overdue by ${Math.abs(diff)} day(s)`;
}

function isDueSoon(date) {
    return dayjs(date).diff(dayjs(), 'day') <= 3 && dayjs(date).isAfter(dayjs());
}
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
        <div
            v-for="task in tasks"
            :key="task.id"
            class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md hover:shadow-xl transition group relative"
        >
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                {{ task.name }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                {{ task.description }}
            </p>
            <p class="mt-4 text-sm text-gray-500">
                Due: {{ formatDate(task.due_date) }}
            </p>
            <p class="text-sm font-medium mt-1" :class="{
                'text-green-600': daysLeft(task.due_date).includes('Due in'),
                'text-orange-500': daysLeft(task.due_date).includes('3'),
                'text-red-600': daysLeft(task.due_date).includes('Overdue'),
            }">
                {{ daysLeft(task.due_date) }}
            </p>

            <span
                v-if="isDueSoon(task.due_date)"
                class="absolute top-2 right-2 text-xs bg-yellow-300 text-black px-2 py-1 rounded-full shadow-sm"
            >
                Due Soon
            </span>
        </div>
    </div>
</template>
