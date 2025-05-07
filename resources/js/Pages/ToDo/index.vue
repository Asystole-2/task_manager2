<template>
    <div>
        <h1>To-Do List</h1>

        <form @submit.prevent="addTodo">
            <input v-model="newTodo" placeholder="Add a new todo" required />
            <button type="submit">Add</button>
        </form>

        <ul>
            <li v-for="todo in todos" :key="todo.id">
                {{ todo.title }} - {{ todo.completed ? 'Completed' : 'Pending' }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            todos: [],
            newTodo: '',
        };
    },
    mounted() {
        this.fetchTodos();
    },
    methods: {
        async fetchTodos() {
            const response = await fetch('/api/todos');
            this.todos = await response.json();
        },
        async addTodo() {
            const response = await fetch('/api/todos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },
                body: JSON.stringify({ title: this.newTodo }),
            });

            if (response.ok) {
                this.newTodo = '';
                this.fetchTodos();
            }
        },
    },
};
</script>

<style scoped>
/* Add any styles you need here */
</style>
