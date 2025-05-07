<template>
    <Head title="Edit Project" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold">Edit {{ projectManagement.name }}</h2>
        </template>

        <form @submit.prevent="updateProject">
            <div>
                <label for="name">Project Name</label>
                <input v-model="form.name" id="name" type="text" required />
            </div>
            <div>
                <label for="description">Description</label>
                <textarea v-model="form.description" id="description"></textarea>
            </div>
            <div>
                <label for="start_date">Start Date</label>
                <input v-model="form.start_date" id="start_date" type="date" />
            </div>
            <div>
                <label for="end_date">End Date</label>
                <input v-model="form.end_date" id="end_date" type="date" />
            </div>
            <button type="submit">Update Project</button>
        </form>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    projectManagement: Object,
});

// Initialize form with project data
const form = useForm({
    name: props.projectManagement.name,
    description: props.projectManagement.description,
    start_date: props.projectManagement.start_date,
    end_date: props.projectManagement.end_date,
});

// Update project function
const updateProject = () => {
    form.put(route('ProjectManagement.update', props.projectManagement.id), {
        onSuccess: () => {
            // Optionally redirect or show a success message
        },
        onError: (errors) => {
            console.error('Error updating project:', errors);
        },
    });
};
</script>
