<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link, Head } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  address: Object,
  users: Array,
  mode: String
})

const form = useForm({
  cep: props.address?.cep || '',
  uf: props.address?.uf || '',
  city: props.address?.city || '',
  street: props.address?.street || '',
  complement: props.address?.complement || '',
  user_id: props.address?.user_id || ''
})

function submit() {
  if (props.mode === 'edit') {
    form.put(route('addresses.update', props.address.id))
  } else {
    form.post(route('addresses.store'))
  }
}
</script>

<template>
  <AdminLayout>
    <Head :title="props.mode === 'edit' ? 'Editar Endereço' : 'Cadastrar Endereço'" />
    <div class="max-w-3xl mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">
        {{ props.mode === 'edit' ? 'Editar Endereço' : 'Cadastrar Endereço' }}
      </h1>
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-6 gap-4">
          <div class="col-span-2">
            <InputLabel for="cep" value="CEP" />
            <input id="cep" v-model="form.cep" type="text" class="input" />
            <InputError :message="form.errors.cep" />
          </div>
          <div class="col-span-1">
            <InputLabel for="uf" value="UF" />
            <input id="uf" v-model="form.uf" type="text" class="input" />
            <InputError :message="form.errors.uf" />
          </div>
          <div class="col-span-3">
            <InputLabel for="city" value="Cidade" />
            <input id="city" v-model="form.city" type="text" class="input" />
            <InputError :message="form.errors.city" />
          </div>
          <div class="col-span-6">
            <InputLabel for="street" value="Endereço" />
            <input id="street" v-model="form.street" type="text" class="input" />
            <InputError :message="form.errors.street" />
          </div>
          <div class="col-span-3">
            <InputLabel for="complement" value="Complemento" />
            <input id="complement" v-model="form.complement" type="text" class="input" />
            <InputError :message="form.errors.complement" />
          </div>
          <div class="col-span-3">
            <InputLabel for="user_id" value="Usuário" />
            <select id="user_id" v-model="form.user_id" class="input">
              <option value="">Selecione</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }} - ({{ user.profile_type }})
              </option>
            </select>
            <InputError :message="form.errors.user_id" />
          </div>
        </div>
        <div class="flex gap-2">
          <Link :href="route('addresses.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</Link>
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
