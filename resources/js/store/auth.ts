import { defineStore } from 'pinia';
import { getProfile, login, register } from '@/api/auth';
import type { LoginPayload, RegisterPayload, User } from '@/interfaces/auth';
import { router } from '@/routes';

interface AuthState {
  user: User | null;
  token: string | null;
}

const ACCESS_TOKEN = 'access_token';

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null as User | null,
    token: localStorage.getItem(ACCESS_TOKEN),
  }),

  getters: {
    isLoggedIn:(state)=> {
      return !!state.token;
    }
  },

  actions: {
    setToken(token: string) {
      this.token = token;
      localStorage.setItem(ACCESS_TOKEN, token);
    },

    setUser(user: User | null) {
      this.user = user;
    },

    logout() {
      this.token = '';
      this.user = null;
      localStorage.removeItem(ACCESS_TOKEN);

      (async () => await router.push('/login'))();
    },

    async login(payload: LoginPayload) {
      const data = await login(payload);
      this.setToken(data.token);
      this.setUser(data.user);
      await router.push('/');
    },

    async register(payload: RegisterPayload) {
      const data = await register(payload);
      this.setToken(data.token);
      this.setUser(data.user);
      await router.push('/');
    },

    async fetchProfile() {
      const user = await getProfile();
      this.setUser(user);
      return user;
    },
    async bootstrap() {
      try {
        if (!this.token) return;
        await this.fetchProfile();
      } catch (e: any) {
        console.error(e);
        this.logout();
      }
    },
  },
});
