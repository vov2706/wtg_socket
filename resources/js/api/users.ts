import type { UserPaginator } from '@/interfaces/users';
import fetchApi from '@/packages/fetchApi';

export const getUsers = async (page: number = 1, search?: string) => {
  const { data } = await fetchApi.get(`/users`, {
    params: {
      page,
      search,
    },
  });

  return data as UserPaginator;
}
