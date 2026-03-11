import { createWebHistory, createRouter } from 'vue-router';
import Dashboard from '@/pages/dashboard/Dashboard.vue';
import Register from '@/pages/auth/Register.vue';
import Login from '@/pages/auth/Login.vue';
import { useAuthStore } from '@/store/auth';
import NotFound from '@/pages/errors/NotFound.vue';
import Messages from '@/pages/messages/Messages.vue';

const routes = [
  { path: '/', name: 'home', component: Dashboard },
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },
  { path: '/messages', name: 'messages', component: Messages },
  {
    path: '/:pathMatch(.*)*',
    name: 'not_found',
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, _from, next) => {
  const { isLoggedIn } = useAuthStore();
  const isLoginRoutes = ['login', 'register'].includes(to.name as string);

  if (isLoggedIn && isLoginRoutes) {
    next({ name: 'home' });
  } else if (!isLoggedIn && !isLoginRoutes) {
    next({ name: 'login' });
  } else {
    next();
  }
});

export { router };
