<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-4">Your Boards</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="board in boards" :key="board.id" class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-xl font-semibold">{{ board.title }}</h2>
                    <Link :href="`/boards/${board.id}`" class="text-blue-500 hover:underline mt-2 block">
                        View Board
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    boards: Array
});
</script>
