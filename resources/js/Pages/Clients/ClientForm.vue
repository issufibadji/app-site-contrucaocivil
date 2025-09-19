<template>
  <form @submit.prevent="onSubmit" class="space-y-4">
    <div>
      <label class="block mb-1">Usuário</label>
      <select v-model="form.user_id" required class="input">
        <option value="">— selecione —</option>
        <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
      </select>
      <div v-if="selectedUser" class="text-sm text-gray-600 mt-1">
        <p>Telefone: {{ selectedUser.phone || '—' }}</p>
        <p>Endereço: {{ selectedUser.address || '—' }}</p>
      </div>
    </div>

    <div>
      <label class="block mb-1">Gênero</label>
      <input v-model="form.gender" type="text" class="input" />
    </div>
   <div class="flex gap-2">
    <Link :href="route('clients.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</Link>
    <button type="submit" class="px-4 py-2 bg-blue-custom-800 text-white rounded hover:bg-blue-custom-600">
      {{ isEditing ? 'Salvar' : 'Criar' }}
    </button>
    </div>
  </form>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
const props = defineProps({
  users: Array,
  modelValue: {
    type: Object,
    default: () => ({ user_id: '', gender: '' }),
  },
})
const emit = defineEmits(['submit'])

const form = ref({
  user_id: props.modelValue.user_id,
  gender:  props.modelValue.gender,
})

const isEditing = computed(() => !!props.modelValue.uuid)
const selectedUser = computed(() => props.users.find(u => u.id === form.value.user_id))

function onSubmit() {
  emit('submit', form.value)
}
</script>
