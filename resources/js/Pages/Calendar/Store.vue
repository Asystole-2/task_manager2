<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
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
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Create Calendar Event" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    Create New Event
                </h2>
                <Link
                    :href="route('calendar.index')"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                >
                    Back to Calendar
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Event Title
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300"
                                required
                            />
                            <p v-if="form.errors.title" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Start Date & Time
                                </label>
                                <input
                                    id="start"
                                    v-model="form.start"
                                    type="datetime-local"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300"
                                    required
                                />
                                <p v-if="form.errors.start" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.start }}
                                </p>
                            </div>

                            <div>
                                <label for="end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    End Date & Time (Optional)
                                </label>
                                <input
                                    id="end"
                                    v-model="form.end"
                                    type="datetime-local"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-300"
                                />
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="all_day"
                                v-model="form.all_day"
                                type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700"
                            />
                            <label for="all_day" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                All Day Event
                            </label>
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Event Color
                            </label>
                            <div class="flex items-center mt-1">
                                <input
                                    id="color"
                                    v-model="form.color"
                                    type="color"
                                    class="h-10 w-10 rounded border-gray-300 shadow-sm"
                                />
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ form.color }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link
                                :href="route('calendar.index')"
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md disabled:opacity-50"
                            >
                                Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
