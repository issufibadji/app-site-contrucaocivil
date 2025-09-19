<!-- Create.vue -->
<script setup>
import ServiceForm from './ServiceForm.vue'
const props = defineProps({
  categories: Array,
  professionals: Object,
})
</script>

<template>
  <ServiceForm
    mode="create"
    :categories="categories"
    :professionals="professionals"
  />
</template>
