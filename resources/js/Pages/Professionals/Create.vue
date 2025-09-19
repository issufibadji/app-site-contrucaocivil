<script setup>
import ProfessionalForm from './ProfessionalForm.vue'
const props = defineProps({ users: Object, phones: Array, professions: Array })
</script>

<template>
  <ProfessionalForm mode="create" :users="users" :phones="phones" :professions="professions" />
</template>
