<script setup>
import AppointmentForm from './AppointmentForm.vue'
const props = defineProps({ services: Object, clients: Object, availabilities: Object })
</script>

<template>
  <AppointmentForm mode="create" :services="services" :clients="clients" :availabilities="availabilities" />
</template>
