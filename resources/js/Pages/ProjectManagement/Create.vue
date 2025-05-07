<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import DateInput from '@/Components/DateInput.vue';

const form = useForm({
    name: '',
    description: '',
    start_date: '',
    end_date: '',
});

const submit = () => {
    form.post(route('ProjectManagement.store'));
};

</script>

<template>
    <Head title="Create New Project" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    Create New Project
                </h2>
                <Link :href="route('ProjectManagement.index')"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                >
                    Back to Projects
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Project Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
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
                                    <InputLabel for="start_date" value="Start Date (Optional)" />
                                    <DateInput
                                        id="start_date"
                                        class="mt-1 block w-full"
                                        v-model="form.start_date"
                                    />
                                    <InputError class="mt-2" :message="form.errors.start_date" />
                                </div>

                                <div>
                                    <InputLabel for="end_date" value="End Date (Optional)" />
                                    <DateInput
                                        id="end_date"
                                        class="mt-1 block w-full"
                                        v-model="form.end_date"
                                    />
                                    <InputError class="mt-2" :message="form.errors.end_date" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link
                                    :href="route('ProjectManagement.index')"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md"
                                >
                                    Cancel
                                </Link>
                                <PrimaryButton :disabled="form.processing">
                                    Create Project
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
