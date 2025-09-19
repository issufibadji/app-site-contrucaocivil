<script setup>
import PhoneForm from './PhoneForm.vue'
const props = defineProps({ phone: Object, professionals: Array, users: Array })
</script>
<template>
  <PhoneForm :phone="phone" :professionals="professionals" :users="users" mode="edit" />
</template>
