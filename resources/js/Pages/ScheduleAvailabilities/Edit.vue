<script setup>
import ScheduleAvailabilityForm from './ScheduleAvailabilityForm.vue'
const props = defineProps({ availability: Object, schedules: Object })
</script>

<template>
  <ScheduleAvailabilityForm mode="edit" :availability="availability" :schedules="schedules" />
</template>
