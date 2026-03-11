<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { getMessages, MessageType } from '@/api/messages';
import type { PaginationMeta } from '@/interfaces/pagination';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Message } from '@/interfaces/messages';

const messageType = ref<MessageType>(MessageType.SENT);

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

const loadMessages = (page: number = 1) => {
  loading.value = true;

  getMessages(page, messageType.value)
    .then((res) => {
      messages.value = res.data;
      paginationData.value = res.meta;
    })
    .finally(() => (loading.value = false));
};

onMounted(() => {
  loadMessages();
});
</script>

<template>
  <AppLayout> </AppLayout>
</template>

<style scoped></style>
