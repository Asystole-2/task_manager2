<template>
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">My To-Do List</h3>

        <form @submit.prevent="addTodo" class="mb-4 flex gap-2">
            <input
                v-model="newTodo"
                placeholder="Add a new todo"
                required
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md"
            />
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add
            </button>
        </form>

        <ul>
            <li v-for="todo in todos" :key="todo.id" class="py-2 border-b border-gray-200">
                {{ todo.title }} - <span :class="todo.completed ? 'text-green-600' : 'text-yellow-600'">
          {{ todo.completed ? 'Completed' : 'Pending' }}
        </span>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const todos = ref([]);
const newTodo = ref('');

const fetchTodos = async () => {
    const response = await fetch('/api/todos');
    todos.value = await response.json();
};

const addTodo = async () => {
    const response = await fetch('/api/todos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ title: newTodo.value }),
    });

    if (response.ok) {
        newTodo.value = '';
        await fetchTodos();
    }
};

onMounted(fetchTodos);
</script>
