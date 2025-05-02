import { createRouter, createWebHistory } from 'vue-router';
import AppLayout from '@/views/AppLayout.vue';
import BoardsIndex from '@/views/boards/Index.vue';
import BoardShow from '@/views/boards/Show.vue';

const routes = [
    {
        path: '/',
        component: AppLayout,
        children: [
            {
                path: '/boards',
                name: 'boards.index',
                component: BoardsIndex
            },
            {
                path: '/boards/:id',
                name: 'boards.show',
                component: BoardShow,
                props: true
            },
            {
                path: '/',
                redirect: '/boards'
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
