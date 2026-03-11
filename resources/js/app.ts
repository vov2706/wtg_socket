import Echo from 'laravel-echo';
import { createPinia } from 'pinia';
import Pusher from 'pusher-js';
import { createApp } from 'vue';
import Toast from 'vue-toastification';
import App from '@/App.vue';
import { router } from '@/routes';
import { useAuthStore } from '@/store/auth';

const app = createApp(App);
const store = createPinia();

app.use(router);
app.use(store);
app.use(Toast);

const authStore = useAuthStore();

window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
  wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
  enabledTransports: ['ws', 'wss'],
  authEndpoint: '/api/broadcasting/auth',
  auth: {
    headers: {
      Authorization: `Bearer ${authStore.token}`,
      Accept: 'application/json',
    },
  },
});

app.mount('#app');
