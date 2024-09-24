import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index'; // 导入 router

createApp(App)
    .use(router) // 使用 router
    .mount('#app');
