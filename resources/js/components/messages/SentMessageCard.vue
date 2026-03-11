<script setup lang="ts">
import type { Message } from '@/interfaces/messages';
import moment from 'moment';

defineProps<{
  message: Message;
}>();
</script>

<template>
  <div
    class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md dark:border-white/10 dark:bg-gray-800/60"
  >
    <div class="flex items-start justify-between gap-4">
      <div class="flex items-start gap-3">
        <div
          class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-500 text-sm font-semibold text-white"
        >
          {{ message.receiver?.name.charAt(0) }}
        </div>

        <div>
          <div class="text-sm font-semibold text-gray-900 dark:text-white">
            To: {{ message.receiver?.name }}
          </div>

          <div class="text-xs text-gray-500">
            {{ message.receiver?.email }}
          </div>

          <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
            {{ message.text }}
          </p>

          <span class="mt-1 block text-xs text-gray-400">
            {{ moment(message.created_at).format('DD/MM/YYYY HH:mm') }}
          </span>
        </div>
      </div>

      <span
        v-if="!message.read_at"
        class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-300"
      >
        Not read
      </span>
    </div>
  </div>
</template>
