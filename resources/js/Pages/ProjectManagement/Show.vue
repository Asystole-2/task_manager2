<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import TaskBoard from '@/Components/TaskBoard.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    project: {
        type: Object,
        required: true,
        default: () => ({
            id: null,
            name: '',
            description: '',
            owner: {
                name: '',
                email: '',
                initials: ''
            },
            activities: [],
            tasks: [],
            members: [],
            is_active: false,
            start_date: null,
            end_date: null
        })
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

const showAddMemberModal = ref(false);
const newMemberId = ref(null);
const newMemberRole = ref('member');

const addMember = () => {
    router.post(route('ProjectManagement.add-member', props.project.id), {
        user_id: newMemberId.value,
        role: newMemberRole.value
    }, {
        onSuccess: () => {
            showAddMemberModal.value = false;
            newMemberId.value = null;
            newMemberRole.value = 'member';
        }
    });
};
</script>

<template>
    <Head :title="project?.name || 'Project Details'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    {{ project?.name || 'Loading...' }}
                </h2>
                <div v-if="project" class="flex space-x-4">
                    <Link :href="route('ProjectManagement.index')"
                          class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                    >
                        Back to Projects
                    </Link>
                    <Link :href="route('ProjectManagement.edit', { projectManagement: project.id })"
                          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md"
                    >
                        Edit Project
                    </Link>
                </div>
            </div>
        </template>

        <div v-if="project" class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Project Details Card -->
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
                                            {{ project.owner?.name || 'Unknown' }}
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

                <!-- Task Board Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Task Board</h3>
                        <Link
                            :href="route('tasks.create', { project_management_id: project.id })"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md text-sm"
                        >
                            + New Task
                        </Link>
                    </div>
                    <TaskBoard :project="project" :initial-tasks="initialTasks" />
                </div>

                <!-- Team Members Section -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Team Members</h3>
                            <button
                                @click="showAddMemberModal = true"
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
                                        {{ project.owner?.name?.split(' ').map(n => n[0]).join('') || 'O' }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ project.owner?.name || 'Owner' }} (Owner)
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ project.owner?.email || 'No email' }}
                                    </p>
                                </div>
                            </li>
                            <li v-for="member in project.members" :key="member.id" class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-600 dark:text-gray-300">
                                        {{ member.name?.split(' ').map(n => n[0]).join('') || 'M' }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ member.name }} ({{ member.pivot?.role || 'member' }})
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ member.email }}
                                    </p>
                                </div>
                                <button
                                    @click="router.delete(route('ProjectManagement.remove-member', [project.id, member.id]))"
                                    class="ml-auto text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 text-sm"
                                >
                                    Remove
                                </button>
                            </li>
                            <li v-if="!project.members || project.members.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-4">
                                No additional team members
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="py-8 text-center">
            Loading project details...
        </div>

        <!-- Add Member Modal -->
        <div v-if="showAddMemberModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Team Member</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Member</label>
                        <select v-model="newMemberId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="" disabled selected>Select a member</option>
                            <option v-for="user in $page.props.availableUsers" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.email }})
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                        <input v-model="newMemberRole" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Member">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button @click="showAddMemberModal = false" type="button" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md">
                            Cancel
                        </button>
                        <button @click="addMember" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                            Add Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
