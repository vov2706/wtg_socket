<script setup lang="ts">
defineProps<{
  sender: string;
  message: string;
}>();
</script>

<template>
  <div class="flex items-start gap-3">
    <div class="flex flex-col">
      <span class="text-sm font-semibold text-white">
        {{ sender }}
      </span>

      <span class="text-xs text-white/80">
        {{ message }}
      </span>
    </div>
  </div>
</template>
