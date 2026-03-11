import { createPinia } from 'pinia';
import { createApp } from 'vue';
import Toast from 'vue-toastification';
import App from '@/App.vue';
import { router } from '@/routes';

const app = createApp(App);
const store = createPinia();

app.use(router);
app.use(store);
app.use(Toast);

app.mount('#app');
