<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link, Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({ phone: Object, mode: String, professionals: Array, users: Array })

const form = useForm({
  ddi: props.phone?.ddi || '',
  ddd: props.phone?.ddd || '',
  phone: props.phone?.phone || '',
  user_id: props.phone?.user_id || ''
})

const selectedUser = computed(() => props.users.find(u => u.id === form.user_id))

function submit() {
  if (props.mode === 'edit') {
    form.put(route('phones.update', props.phone.id))
  } else {
    form.post(route('phones.store'))
  }
}
</script>

<template>
  <AdminLayout>
    <Head :title="props.mode === 'edit' ? 'Editar Telefone' : 'Cadastrar Telefone'" />
    <div class="max-w-3xl mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">{{ props.mode === 'edit' ? 'Editar Telefone' : 'Cadastrar Telefone' }}</h1>
      <form @submit.prevent="submit" class="space-y-4">
        <div class="grid grid-cols-6 gap-4">
          <div class="col-span-1">
            <InputLabel for="ddi" value="DDI" />
            <input id="ddi" v-model="form.ddi" type="text" class="input" />
            <InputError :message="form.errors.ddi" />
          </div>
          <div class="col-span-1">
            <InputLabel for="ddd" value="DDD" />
            <input id="ddd" v-model="form.ddd" type="text" class="input" />
            <InputError :message="form.errors.ddd" />
          </div>
          <div class="col-span-2">
            <InputLabel for="phone" value="Telefone" />
            <input id="phone" v-model="form.phone" type="text" class="input" />
            <InputError :message="form.errors.phone" />
          </div>
          <div class="col-span-3">
            <InputLabel for="user_id" value="Usuário" />
            <select id="user_id" v-model="form.user_id" class="input">
              <option value="">Selecione</option>
              <option
                v-for="user in users"
                :key="user.id"
                :value="user.id"
              >
                {{ user.name }} - ({{ user.profile_type }})
              </option>
            </select>
            <InputError :message="form.errors.user_id" />
            <div v-if="selectedUser" class="text-sm text-gray-600 mt-1">
              <p>Telefone: {{ selectedUser.phone || '—' }}</p>
              <p>Endereço: {{ selectedUser.address || '—' }}</p>
            </div>
          </div>
        </div>
        <div class="flex gap-2">
          <Link :href="route('phones.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</Link>
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
