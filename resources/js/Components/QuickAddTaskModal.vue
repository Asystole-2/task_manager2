<template>
    <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Quick Add Task</h3>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input v-model="form.title" type="text" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                    <input v-model="form.due_date" type="date" class="mt-1 w-full rounded border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-white">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Add</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

defineProps({ show: Boolean });
const emit = defineEmits(['close']);

const form = ref({
    title: '',
    due_date: '',
});

const submit = () => {
    router.post(route('tasks.store'), form.value, {
        onSuccess: () => emit('close'),
    });
};
</script>
