<script setup lang="ts">
defineProps<{
  sender: string;
  message: string;
}>();
</script>

<template>
  <div class="flex cursor-pointer items-start gap-3">
    <div class="flex min-w-0 flex-col">
      <span class="text-sm leading-tight font-semibold break-words text-white">
        {{ sender }}
      </span>

      <span class="text-xs break-words whitespace-normal text-white/80">
        {{ message }}
      </span>

      <span class="mt-1 text-[11px] text-white/60">
        Click or close to mark as read
      </span>
    </div>
  </div>
</template>
