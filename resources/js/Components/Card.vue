<!-- resources/js/Components/Card.vue -->
<template>
  <div class="bg-white dark:bg-blue-custom-800 rounded-lg shadow p-4 flex items-start gap-4">
    <i :class="['text-2xl text-blue-custom-500 mt-1', icon]"></i>
    <div>
      <h2 class="text-lg font-semibold text-blue-custom-800 dark:text-white">{{ title }}</h2>
      <slot />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  icon: { type: String, default: 'fas fa-info-circle' },
  title: { type: String, required: true },
})
</script>
