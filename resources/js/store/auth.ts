import { defineStore } from 'pinia';
import { getProfile, login, logout, register } from '@/api/auth';
import type {
  AuthUser,
  LoginPayload,
  RegisterPayload,
} from '@/interfaces/auth';
import { router } from '@/routes';

interface AuthState {
  user: AuthUser | null;
  token: string | null;
}

const ACCESS_TOKEN = 'access_token';

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null as AuthUser | null,
    token: localStorage.getItem(ACCESS_TOKEN),
  }),

  getters: {
    isLoggedIn: (state) => {
      return !!state.token;
    },
  },

  actions: {
    setToken(token: string) {
      this.token = token;
      localStorage.setItem(ACCESS_TOKEN, token);
    },

    setUser(user: AuthUser | null) {
      this.user = user;
    },

    async logout() {
      await logout()

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
