<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import DateInput from '@/Components/DateInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Multiselect from '@vueform/multiselect';
import { ref } from 'vue';

const props = defineProps({
    project: {
        type: Object,
        default: null
    },
    availableMembers: {
        type: Array,
        default: () => []
    }
});

const form = useForm({
    title: '',
    description: '',
    priority: 'medium',
    due_date: '',
    project_management_id: props.project?.id || null,
    assignee_id: null,
    member_ids: []
});

const submit = () => {
    const redirectRoute = props.project
        ? route('ProjectManagement.show', props.project.id)
        : route('dashboard');

    form.post(route('tasks.store'), {
        onSuccess: () => {
            form.reset();
            router.visit(redirectRoute);
        }
    });
};
</script>

<template>
    <Head title="Create New Task" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    {{ project ? `Create New Task for ${project.name}` : 'Create New Task' }}
                </h2>
                <Link
                    :href="project ? route('ProjectManagement.show', project.id) : route('dashboard')"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                >
                    {{ project ? 'Back to Project' : 'Back to Dashboard' }}
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="space-y-6">
                            <div>
                                <InputLabel for="title" value="Task Title *" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="description" value="Description" />
                                <TextArea
                                    id="description"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    rows="4"
                                />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="priority" value="Priority *" />
                                    <select
                                        id="priority"
                                        v-model="form.priority"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 dark:focus:border-red-600 focus:ring-red-500 dark:focus:ring-red-600 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.priority" />
                                </div>

                                <div>
                                    <InputLabel for="due_date" value="Due Date" />
                                    <DateInput
                                        id="due_date"
                                        class="mt-1 block w-full"
                                        v-model="form.due_date"
                                    />
                                    <InputError class="mt-2" :message="form.errors.due_date" />
                                </div>
                            </div>

                            <template v-if="project">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="assignee_id" value="Assignee (Optional)" />
                                        <SelectInput
                                            id="assignee_id"
                                            class="mt-1 block w-full"
                                            v-model="form.assignee_id"
                                            :options="availableMembers.reduce((obj, member) => ({ ...obj, [member.id]: member.name }), {})"
                                            nullable
                                        />
                                        <InputError class="mt-2" :message="form.errors.assignee_id" />
                                    </div>

                                    <div>
                                        <InputLabel for="member_ids" value="Additional Members (Optional)" />
                                        <Multiselect
                                            v-model="form.member_ids"
                                            mode="tags"
                                            placeholder="Select members"
                                            :close-on-select="false"
                                            :options="availableMembers.map(member => ({ value: member.id, label: member.name }))"
                                        />
                                        <InputError class="mt-2" :message="form.errors.member_ids" />
                                    </div>
                                </div>
                            </template>

                            <div class="flex items-center justify-end gap-4">
                                <Link
                                    :href="project ? route('ProjectManagement.show', project.id) : route('dashboard')"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                                >
                                    Cancel
                                </Link>
                                <PrimaryButton :disabled="form.processing">
                                    Create Task
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
