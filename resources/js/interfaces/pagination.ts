export interface PaginationMeta {
  current_page: number;
  last_page: number;
  total: number;
  per_page: number;
  from: number;
  to: number;
}

export interface Paginator<T> {
  data: T[];
  meta: PaginationMeta;
}
