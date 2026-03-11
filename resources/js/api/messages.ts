import fetchApi from '@/packages/fetchApi';

export default interface SendMessagePayload {
  receiver_id: number;
  text: string;
}

export enum MessageType {
  SENT = 'sent',
  RECEIVED = 'received',
}

export const getMessages = async (page: number, type: MessageType) => {
  const { data } = await fetchApi.get(`/messages`, {
    params: {
      page,
      type,
    },
  });

  return data;
};

export const sendMessage = async (payload: SendMessagePayload) => {
  const {data} = await fetchApi.post(`/messages`, payload);

  return data;
};
