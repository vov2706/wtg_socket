import type { User } from '@/interfaces/users';

export interface Message {
  id: number;
  text: string;
  read_at: string;
  created_at: string;
  sender?: User;
  receiver?: User;
}
