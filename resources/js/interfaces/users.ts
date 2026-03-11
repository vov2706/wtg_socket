import type { PaginationMeta } from '@/interfaces/pagination';

export interface User {
  id: number;
  name: string;
  email: string;
}

export interface UserPaginator {
  data: User[];
  meta: PaginationMeta;
}
