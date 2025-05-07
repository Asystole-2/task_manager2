<template>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">{{ board.title }}</h1>
                <div class="flex space-x-3">
                    <button
                        @click="updateBoard"
                        class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50"
                    >
                        Edit
                    </button>
                    <button
                        @click="deleteBoard"
                        class="px-3 py-1 border border-gray-300 rounded-md text-sm text-red-600 hover:bg-red-50"
                    >
                        Delete
                    </button>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-1">Last updated {{ formatDate(board.updated_at) }}</p>
        </div>

        <div v-if="loading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        </div>

        <div v-else>
            <KanbanBoard :board="board" @refresh="fetchBoard" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import KanbanBoard from '@/Components/KanbanBoard.vue';
// import KanbanBoard from '@/components/KanbanBoard.vue';


const route = useRoute();
const router = useRouter();
const board = ref({});
const loading = ref(true);

const fetchBoard = async () => {
    try {
        const response = await axios.get(`/api/boards/${route.params.id}`);
        board.value = response.data;
    } catch (error) {
        console.error('Error fetching board:', error);
        router.push('/boards');
    } finally {
        loading.value = false;
    }
};

onMounted(fetchBoard);

const updateBoard = async () => {
    const newTitle = prompt('Enter new board title:', board.value.title);
    if (newTitle && newTitle !== board.value.title) {
        try {
            await axios.put(`/api/boards/${board.value.id}`, {
                title: newTitle
            });
            board.value.title = newTitle;
        } catch (error) {
            console.error('Error updating board:', error);
        }
    }
};

const deleteBoard = async () => {
    if (confirm('Are you sure you want to delete this board?')) {
        try {
            await axios.delete(`/api/boards/${board.value.id}`);
            router.push('/boards');
        } catch (error) {
            console.error('Error deleting board:', error);
        }
    }
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>
