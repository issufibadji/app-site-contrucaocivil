<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link, Head } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  professional: Object,
  mode: String,
  users: Object,
  phones: Array,
  professions: Array
})

const form = useForm({
  user_id: props.professional?.user_id || '',
  professions: props.professional?.professions || [],
  description: props.professional?.description || '',
  phones: props.phones || []
})

function submit() {
  if (props.mode === 'edit') {
    form.put(route('professionals.update', props.professional.uuid))
  } else {
    form.post(route('professionals.store'))
  }
}
</script>

<template>
  <AdminLayout>
    <Head :title="props.mode === 'edit' ? 'Editar Profissional' : 'Cadastrar Profissional'" />
    <div class="max-w-3xl mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">
        {{ props.mode === 'edit' ? 'Editar Profissional' : 'Cadastrar Profissional' }}
      </h1>
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Usuário -->
        <div>
          <InputLabel for="user_id" value="Usuário" />
          <select id="user_id" v-model="form.user_id" class="input">
            <option value="">Selecione</option>
            <option v-for="(name, id) in users" :key="id" :value="id">{{ name }}</option>
          </select>
          <InputError :message="form.errors.user_id" />
        </div>

        <!-- Profissão -->
        <div>
          <InputLabel for="professions" value="Profissões" />
          <select id="professions" v-model="form.professions" multiple class="input">
            <option v-for="profession in professions" :key="profession" :value="profession">{{ profession }}</option>
          </select>
          <InputError :message="form.errors.professions" />
        </div>

        <!-- Descrição / Função -->
        <div>
          <InputLabel for="description" value="Descrição / Função" />
          <textarea id="description" v-model="form.description" rows="4" class="input"></textarea>
          <InputError :message="form.errors.description" />
        </div>

        <!-- Ações -->
        <div class="flex gap-2">
          <Link :href="route('professionals.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</Link>
          <button type="submit" class="px-4 py-2 bg-blue-custom-800 text-white rounded hover:bg-blue-custom-600" :disabled="form.processing">
            {{ props.mode === 'edit' ? 'Atualizar' : 'Salvar' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<style scoped>
.input {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-custom-500 focus:ring-blue-custom-500;
}
</style>
