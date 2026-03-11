<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { getMessages, MessageType } from '@/api/messages';
import ReceivedMessageCard from '@/components/messages/ReceivedMessageCard.vue';
import SentMessageCard from '@/components/messages/SentMessageCard.vue';
import Pagination from '@/components/pagination/Pagination.vue';
import type { Message } from '@/interfaces/messages';
import type { PaginationMeta } from '@/interfaces/pagination';
import AppLayout from '@/layouts/AppLayout.vue';

const loading = ref(false);
const messages = ref<Message[]>([]);
const paginationData = ref<PaginationMeta>({
  current_page: 1,
  last_page: 1,
  total: 0,
  per_page: 15,
  from: 0,
  to: 0,
});

const router = useRouter();

const handlePageChange = (page: number) => {
  loadMessages(page, router.currentRoute.value.query.type as MessageType);
};

const loadMessages = (
  page: number = 1,
  messageType: MessageType = MessageType.SENT,
) => {
  loading.value = true;

  getMessages(page, messageType)
    .then((res) => {
      messages.value = res.data;
      paginationData.value = res.meta;
    })
    .finally(() => (loading.value = false));
};

onMounted(() => {
  loadMessages(1, router.currentRoute.value.query.type as MessageType);
});
</script>

<template>
  <AppLayout>
    <div>
      <div class="sm:block">
        <div class="border-b border-gray-200 dark:border-white/10">
          <nav class="-mb-px flex" aria-label="Tabs">
            <RouterLink
              :to="`/messages?type=${MessageType.SENT}`"
              @click="loadMessages(1, MessageType.SENT)"
              :class="[
                $router.currentRoute.value.query.type === MessageType.SENT
                  ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-white/20 dark:hover:text-gray-300',
                'w-1/2 border-b-2 px-1 py-4 text-center text-sm font-medium',
              ]"
            >
              Sent messages
            </RouterLink>
            <RouterLink
              :to="`/messages?type=${MessageType.RECEIVED}`"
              @click="loadMessages(1, MessageType.RECEIVED)"
              :class="[
                $router.currentRoute.value.query.type === MessageType.RECEIVED
                  ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-white/20 dark:hover:text-gray-300',
                'w-1/2 border-b-2 px-1 py-4 text-center text-sm font-medium',
              ]"
            >
              Received messages
            </RouterLink>
          </nav>
        </div>
      </div>
      <div class="mt-8 flow-root">
        <div class="mt-8 flow-root space-y-4">
          <component
            v-for="message in messages"
            :key="message.id"
            :is="
              $router.currentRoute.value.query.type === MessageType.SENT
                ? SentMessageCard
                : ReceivedMessageCard
            "
            :message="message"
          />
        </div>
      </div>
      <Pagination
        :current-page="paginationData.current_page"
        :last-page="paginationData.last_page"
        :total="paginationData.total"
        :per-page="paginationData.per_page"
        :from="paginationData.from"
        :to="paginationData.to"
        @change="handlePageChange"
      />
    </div>
  </AppLayout>
</template>
