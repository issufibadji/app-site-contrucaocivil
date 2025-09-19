<script setup>
import PhoneForm from './PhoneForm.vue'
const props = defineProps({ professionals: Array, users: Array })
</script>
<template>
  <PhoneForm :professionals="professionals" :users="users" mode="create" />
</template>
