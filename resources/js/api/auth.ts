import type {
  LoginPayload,
  AuthResponse,
  RegisterPayload,
  AuthUser,
} from '@/interfaces/auth';
import fetchApi from '@/packages/fetchApi';

export const getProfile = async () => {
  const { data } = await fetchApi.get('/profile');

  return data.data as AuthUser;
}

export const login = async (payload: LoginPayload): Promise<AuthResponse> => {
  const { data } = await fetchApi.post('/login', payload);

  return data as AuthResponse;
};

export const register = async (payload: RegisterPayload) => {
  const { data } = await fetchApi.post('/register', payload);

  return data as AuthResponse;
};

export const logout = async () => {
  const { data } = await fetchApi.post('/logout');

  return data;
};
