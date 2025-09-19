<script setup>
import RequestForm from './RequestForm.vue'
const props = defineProps({ request:Object, services:Object, availabilities:Object, categories:Array, professionals:Array, priorities:Object })
</script>
<template>
  <RequestForm mode="edit" :request="request" :services="services" :availabilities="availabilities" :categories="categories" :professionals="professionals" :priorities="priorities" />
</template>
