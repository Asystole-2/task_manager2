<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    start: '',
    end: '',
    all_day: false,
    color: '#e63946'
});

const submit = () => {
    form.post(route('calendar.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input v-model="form.title" type="text" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <p v-if="form.errors.title" class="mt-2 text-sm text-red-600">{{ form.errors.title }}</p>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="form.description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start" class="block text-sm font-medium text-gray-700">Start</label>
                <input v-model="form.start" type="datetime-local" id="start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <p v-if="form.errors.start" class="mt-2 text-sm text-red-600">{{ form.errors.start }}</p>
            </div>

            <div>
                <label for="end" class="block text-sm font-medium text-gray-700">End</label>
                <input v-model="form.end" type="datetime-local" id="end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>

        <div class="flex items-center">
            <input v-model="form.all_day" type="checkbox" id="all_day" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="all_day" class="ml-2 block text-sm text-gray-700">All day event</label>
        </div>

        <div>
            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
            <input v-model="form.color" type="color" id="color" class="mt-1 h-10 w-10 rounded-md border-gray-300 shadow-sm">
        </div>

        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            Create Event
        </button>
    </form>
</template>
