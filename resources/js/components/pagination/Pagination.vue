<script setup lang="ts">
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';
import { computed } from 'vue';

type PaginationItem = number | '...';

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  lastPage: {
    type: Number,
    required: true,
  },
  total: {
    type: Number,
    required: true,
  },
  perPage: {
    type: Number,
    required: true,
  },
  from: {
    type: Number,
    default: 0,
  },
  to: {
    type: Number,
    default: 0,
  },
});

const emit = defineEmits<{
  (e: 'change', page: number): void;
}>();

const hasPrevious = computed(() => props.currentPage > 1);
const hasNext = computed(() => props.currentPage < props.lastPage);

const goToPage = (page: number) => {
  if (page < 1 || page > props.lastPage || page === props.currentPage) {
    return;
  }

  emit('change', page);
};

const paginationItems = computed<PaginationItem[]>(() => {
  const current = props.currentPage;
  const last = props.lastPage;

  if (last <= 7) {
    return Array.from({ length: last }, (_, i) => i + 1);
  }

  const items: PaginationItem[] = [];

  items.push(1);

  if (current <= 4) {
    items.push(2, 3, 4, 5, '...', last);
    return items;
  }

  if (current >= last - 3) {
    items.push('...', last - 4, last - 3, last - 2, last - 1, last);
    return items;
  }

  items.push('...', current - 1, current, current + 1, '...', last);

  return items;
});
</script>

<template>
  <div
    class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 dark:border-white/10 dark:bg-transparent"
  >
    <div class="flex flex-1 justify-between sm:hidden">
      <button
        type="button"
        :disabled="!hasPrevious"
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 dark:border-white/10 dark:bg-white/5 dark:text-gray-200"
        :class="[
          hasPrevious
            ? 'hover:bg-gray-50 dark:hover:bg-white/10'
            : 'cursor-not-allowed opacity-50',
        ]"
        @click="goToPage(currentPage - 1)"
      >
        Previous
      </button>

      <button
        type="button"
        :disabled="!hasNext"
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 dark:border-white/10 dark:bg-white/5 dark:text-gray-200"
        :class="[
          hasNext
            ? 'hover:bg-gray-50 dark:hover:bg-white/10'
            : 'cursor-not-allowed opacity-50',
        ]"
        @click="goToPage(currentPage + 1)"
      >
        Next
      </button>
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700 dark:text-gray-300">
          Showing
          <span class="font-medium">{{ from }}</span>
          to
          <span class="font-medium">{{ to }}</span>
          of
          <span class="font-medium">{{ total }}</span>
          results
        </p>
      </div>

      <div>
        <nav
          class="isolate inline-flex -space-x-px rounded-md shadow-xs dark:shadow-none"
          aria-label="Pagination"
        >
          <button
            type="button"
            :disabled="!hasPrevious"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 inset-ring inset-ring-gray-300 dark:inset-ring-gray-700"
            :class="[
              hasPrevious
                ? 'hover:bg-gray-50 focus:z-20 focus:outline-offset-0 dark:hover:bg-white/5'
                : 'cursor-not-allowed opacity-50',
            ]"
            @click="goToPage(currentPage - 1)"
          >
            <span class="sr-only">Previous</span>
            <ChevronLeftIcon class="size-5" aria-hidden="true" />
          </button>

          <template
            v-for="(item, index) in paginationItems"
            :key="`${item}-${index}`"
          >
            <span
              v-if="item === '...'"
              class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 inset-ring inset-ring-gray-300 focus:outline-offset-0 dark:text-gray-400 dark:inset-ring-gray-700"
            >
              ...
            </span>

            <button
              v-else
              type="button"
              :aria-current="item === currentPage ? 'page' : undefined"
              class="relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 focus:outline-offset-0"
              :class="[
                item === currentPage
                  ? 'z-10 bg-indigo-600 text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:focus-visible:outline-indigo-500'
                  : 'text-gray-900 inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:text-gray-200 dark:inset-ring-gray-700 dark:hover:bg-white/5',
              ]"
              @click="goToPage(item)"
            >
              {{ item }}
            </button>
          </template>

          <button
            type="button"
            :disabled="!hasNext"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 inset-ring inset-ring-gray-300 dark:inset-ring-gray-700"
            :class="[
              hasNext
                ? 'hover:bg-gray-50 focus:z-20 focus:outline-offset-0 dark:hover:bg-white/5'
                : 'cursor-not-allowed opacity-50',
            ]"
            @click="goToPage(currentPage + 1)"
          >
            <span class="sr-only">Next</span>
            <ChevronRightIcon class="size-5" aria-hidden="true" />
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>
