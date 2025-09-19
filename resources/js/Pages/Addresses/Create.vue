<script setup>
import AddressForm from './AddressForm.vue'
const props = defineProps({ users: Array })
</script>

<template>
  <AddressForm mode="create" :users="users" />
</template>
