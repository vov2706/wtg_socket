import axios from 'axios';
import { useToast } from 'vue-toastification';
import { router } from '@/routes';
import { useAuthStore } from '@/store/auth';

const fetchApi = axios.create({
  baseURL: import.meta.env.VITE_BACKEND_URL + '/api/',
  timeout: 60000,
  headers: {
    Accept: 'application/json',
  },
});

const toast = useToast();

fetchApi.interceptors.request.use(
  async config => {
    const {token} = useAuthStore();

    if (config?.headers) {
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    }

    return config;
  },
  error => {
    return Promise.reject(error);
  },
);

fetchApi.interceptors.response.use(
  (response) => {
    if (response?.data?.message) {
      toast.success(response.data.message)
    }
    return response
  },
  async (error) => {
    const errorResponse = error?.response;

    if (errorResponse?.data?.errors) {
      const errors = errorResponse?.data?.errors
      for (const el in errors) {
        for (const error of errors[el]) {
          toast.error(error)
        }
      }
    } else if (error?.response?.data?.message) {
      const err = error?.response?.data?.message

      if (error?.response.status === 403) {
        toast.error('Forbidden')
      } else {
        toast.error(err)
      }
    } else {
      toast.error(error?.message)
    }

    if (errorResponse?.status === 401) {
      await router.push('/login');
    }

    return Promise.reject(error)
  }
);

export default fetchApi;
