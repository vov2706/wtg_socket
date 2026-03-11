import { useToast } from 'vue-toastification';
import { markAsRead } from '@/api/messages';
import MessageToast from '@/components/toasts/MessageToast.vue';
import type { Message } from '@/interfaces/messages';

export const showMessageToast = (message: Message)=> {
  useToast().info(
    {
      component: MessageToast,
      props: {
        sender: message.sender?.name,
        message: message.text,
      },
    },
    {
      timeout: false,
      onClose: () => {
        markAsRead(message.id).then((r) => console.log(r));
      },
    },
  );
}
