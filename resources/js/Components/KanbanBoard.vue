<template>
    <div class="kanban-board">
        <div class="board-header">
            <h1>{{ board.title }}</h1>
            <button @click="addColumn">Add Column</button>
        </div>

        <div class="columns-container">
            <draggable
                v-model="columns"
                group="columns"
                @end="onColumnDragEnd"
                item-key="id"
                handle=".column-header"
            >
                <template #item="{element}">
                    <div class="column">
                        <div class="column-header">
                            <h3>{{ element.title }}</h3>
                            <button @click="deleteColumn(element)">×</button>
                        </div>

                        <draggable
                            v-model="element.cards"
                            group="cards"
                            @end="(event) => onCardDragEnd(event, element)"
                            item-key="id"
                            class="cards-container"
                        >
                            <template #item="{element: card}">
                                <div class="card">
                                    <div class="card-content">
                                        <h4>{{ card.title }}</h4>
                                        <p v-if="card.content">{{ card.content }}</p>
                                    </div>
                                    <button @click="deleteCard(card)">×</button>
                                </div>
                            </template>
                        </draggable>

                        <button @click="addCard(element)">Add Card</button>
                    </div>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import draggable from 'vuedraggable';
import axios from 'axios';

const props = defineProps({
    boardId: {
        type: Number,
        required: true
    }
});

const board = ref({});
const columns = ref([]);

// Fetch board data
const fetchBoard = async () => {
    const response = await axios.get(`/api/boards/${props.boardId}`);
    board.value = response.data;
    columns.value = response.data.columns;
};

// Column methods
const addColumn = async () => {
    const response = await axios.post(`/api/boards/${props.boardId}/columns`, {
        title: 'New Column',
        order: columns.value.length
    });
    columns.value.push(response.data);
};

const deleteColumn = async (column) => {
    await axios.delete(`/api/columns/${column.id}`);
    columns.value = columns.value.filter(c => c.id !== column.id);
};

const onColumnDragEnd = async (event) => {
    await axios.patch(`/api/columns/${columns.value[event.newIndex].id}/move`, {
        newOrder: event.newIndex
    });
};

// Card methods
const addCard = async (column) => {
    const response = await axios.post(`/api/columns/${column.id}/cards`, {
        title: 'New Task',
        order: column.cards.length
    });
    column.cards.push(response.data);
};

const deleteCard = async (card) => {
    await axios.delete(`/api/cards/${card.id}`);
    const column = columns.value.find(c => c.cards.some(cardItem => cardItem.id === card.id));
    if (column) {
        column.cards = column.cards.filter(c => c.id !== card.id);
    }
};

const onCardDragEnd = async (event, column) => {
    await axios.patch(`/api/cards/${column.cards[event.newIndex].id}/move`, {
        newOrder: event.newIndex,
        newColumnId: column.id
    });
};

onMounted(fetchBoard);
</script>

<style scoped>
.kanban-board {
    padding: 20px;
    height: 100vh;
    overflow-x: auto;
}

.board-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.columns-container {
    display: flex;
    gap: 15px;
}

.column {
    width: 300px;
    background: #f5f5f5;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.column-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: move;
}

.cards-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 100px;
}

.card {
    background: white;
    padding: 12px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
}

.card-content {
    flex: 1;
}

button {
    background: #4a90e2;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background: #3a7bc8;
}
</style>
