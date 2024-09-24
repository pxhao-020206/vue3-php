import { createRouter, createWebHistory } from 'vue-router';
import UserList from '../components/UserList.vue';
import HelloWorld from '@/components/HelloWorld.vue';

const routes = [
    {
        path: '/',
        name: 'UserList',
        component: UserList
    },
    {
        path:'/hello',
        name:'HelloWorld',
        component: HelloWorld
    }
    // 可以在此处添加其他页面的路由
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
