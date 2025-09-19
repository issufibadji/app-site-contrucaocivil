<template>
  <InformationPeopleForm
    v-if="professional"
    :professional="professional"
    :categories="categories"
    @saved="emit('saved')"
  />
  <p v-else class="p-4 text-center text-gray-500">
    Você ainda não configurou seu perfil de prestador.
  </p>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import InformationPeopleForm from './InformationPeopleForm.vue'

const props = defineProps({
  professional: { type: Object, default: null },
  categories:   { type: Array,  default: () => [] },
})

const emit = defineEmits(['saved'])
</script>
