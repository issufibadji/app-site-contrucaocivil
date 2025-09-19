<script setup>
import ProfessionalForm from './ProfessionalForm.vue'
const props = defineProps({ professional: Object, users: Object, phones: Array, professions: Array })
</script>

<template>
  <ProfessionalForm mode="edit" :professional="professional" :users="users" :phones="phones" :professions="professions" />
</template>
