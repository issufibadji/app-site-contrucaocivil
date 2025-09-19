<script setup>
import ScheduleAvailabilityForm from './ScheduleAvailabilityForm.vue'
const props = defineProps({ schedules: Object })
</script>

<template>
  <ScheduleAvailabilityForm mode="create" :schedules="schedules" />
</template>
