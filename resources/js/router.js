import { createRouter, createWebHistory } from 'vue-router';
import KanbanBoard from './Components/KanbanBoard.vue';
import BoardList from './Components/BoardList.vue';

const routes = [
    {
        path: '/boards/:id',
        component: KanbanBoard,
        props: true
    },
    {
        path: '/boards',
        component: BoardList
    },
    {
        path: '/',
        redirect: '/boards'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
