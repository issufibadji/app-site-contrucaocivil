<!-- Edit.vue -->
<script setup>
import ServiceForm from './ServiceForm.vue'
const props = defineProps({
  service: Object,
  categories: Array,
  professionals: Object,
})
</script>

<template>
  <ServiceForm
    mode="edit"
    :service="service"
    :categories="categories"
    :professionals="professionals"
  />
</template>
