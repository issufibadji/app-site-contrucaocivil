<script setup>
import AddressForm from './AddressForm.vue'
const props = defineProps({ address: Object, users: Array })
</script>

<template>
  <AddressForm mode="edit" :address="address" :users="users" />
</template>
