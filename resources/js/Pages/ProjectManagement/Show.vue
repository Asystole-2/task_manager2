<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    project: Object,
    availableTasks: Array
});

const showTaskModal = ref(false);
const selectedTasks = ref([]);

const form = useForm({
    task_ids: []
});

const addTasks = () => {
    console.log('Project:', props.project); // Check if project is defined
    form.task_ids = selectedTasks.value;

    form.post(route('ProjectManagement.tasks.add', props.project.id), {
        preserveScroll: true,
        onSuccess: () => {
            showTaskModal.value = false;
            selectedTasks.value = [];
        },
        onError: (errors) => {
            console.error('Error adding tasks:', errors);
        }
    });
};
</script>

<template>
    <Head :title="project.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    {{ project.name }}
                </h2>
                <div class="flex space-x-4">
                    <Link :href="route('ProjectManagement.index')"
                          class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                    >
                        Back to Projects
                    </Link>
                    <Link :href="route('ProjectManagement.edit', project.id)"
                          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                    >
                        Edit Project
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Project Details
                                </h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">
                                    {{ project.description || 'No description provided' }}
                                </p>
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Owner</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ project.owner.name }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Start Date</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ project.start_date ? new Date(project.start_date).toLocaleDateString() : 'Not set' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">End Date</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ project.end_date ? new Date(project.end_date).toLocaleDateString() : 'Not set' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium"
                                :class="{
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': project.is_active,
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': !project.is_active
                                }"
                            >
                                {{ project.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li v-for="activity in project.activities" :key="activity.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                            <span class="text-red-600 dark:text-red-300">
                                                {{ activity.user.initials }}
                                            </span>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ activity.user.name }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ activity.time_ago }}
                                                </p>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                                {{ activity.description }}
                                            </p>
                                            <div v-if="activity.properties.changes" class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                <div v-for="(value, key) in activity.properties.changes" :key="key">
                                                    <span class="font-medium">{{ key }}:</span>
                                                    {{ value }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li v-if="project.activities.length === 0" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    No activity yet
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tasks Section -->
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Tasks</h3>
                                <div class="flex space-x-2">
                                    <button @click="showTaskModal = true"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md text-sm"
                                    >
                                        Add Existing Tasks
                                    </button>
                                    <Link
                                        :href="route('tasks.create', { project_id: project.id })"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md text-sm"
                                    >
                                        + New Task
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div v-if="project.tasks.length > 0" class="space-y-4">
                                <div v-for="task in project.tasks" :key="task.id" class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ task.title }}</h4>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ task.description }}</p>
                                        </div>
                                        <Link :href="route('tasks.edit', task.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                            Edit
                                        </Link>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Due: {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date' }}
                                        </span>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': task.status === 'completed',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': task.status === 'in_progress',
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': task.status === 'pending'
                                            }"
                                        >
                                            {{ task.status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center text-gray-500 dark:text-gray-400 py-4">
                                No tasks for this project yet
                            </div>
                        </div>
                    </div>

                    <!-- Team Members Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Team Members</h3>
                                <button
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md text-sm"
                                >
                                    + Add Member
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-4">
                                <li class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-600 dark:text-gray-300">
                                            {{ project.owner.name.split(' ').map(n => n[0]).join('') }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ project.owner.name }} (Owner)
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ project.owner.email }}
                                        </p>
                                    </div>
                                </li>
                                <li v-for="member in project.members" :key="member.id" class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-600 dark:text-gray-300">
                                            {{ member.name.split(' ').map(n => n[0]).join('') }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ member.name }} ({{ member.pivot.role }})
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ member.email }}
                                        </p>
                                    </div>
                                </li>
                                <li v-if="project.members.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-4">
                                    No additional team members
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Selection Modal -->
        <div v-if="showTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] flex flex-col">
                <h3 class="text-lg font-semibold mb-4">Add Tasks to Project</h3>

                <div class="flex-1 overflow-y-auto mb-4">
                    <div v-if="availableTasks.length > 0" class="space-y-2">
                        <div v-for="task in availableTasks" :key="task.id"
                             class="flex items-center p-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <input type="checkbox" :value="task.id" v-model="selectedTasks"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:border-gray-600">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">{{ task.title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ task.description }}</p>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date' }}
                            </span>
                        </div>
                    </div>
                    <div v-else class="text-center text-gray-500 dark:text-gray-400 py-8">
                        No available tasks to add
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button @click="showTaskModal = false"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button @click="addTasks"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            :disabled="form.processing || selectedTasks.length === 0">
                        Add Selected Tasks
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
