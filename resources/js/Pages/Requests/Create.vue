<script setup>
import RequestForm from './RequestForm.vue'
const props = defineProps({ services:Object, availabilities:Object, categories:Array, professionals:Array, priorities:Object })
</script>
<template>
  <RequestForm mode="create" :services="services" :availabilities="availabilities" :categories="categories" :professionals="professionals" :priorities="priorities" />
</template>
