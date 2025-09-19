<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link, Head, router } from '@inertiajs/vue3'
import { watch } from 'vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  request: Object,
  services: Object,
  availabilities: Object,
  categories: Array,
  professionals: Array,
  priorities: Object,
  mode: String
})

const form = useForm({
  category_id: props.request?.category_id || '',
  service_id: props.request?.service_id || '',
  professional_id: props.request?.professional_id || '',
  schedule_availability_id: props.request?.schedule_availability_id || '',
  duration: props.request?.duration || '',
  price: props.request?.price || '',
  scheduled_at: props.request?.scheduled_at || '',
  address: props.request?.address || '',
  photo: null,
  priority: props.request?.priority || 'normal',
  description: props.request?.description || '',
  status: props.request?.status || 'pendente'
})

watch(() => form.category_id, (val) => {
  if (val) {
    router.get(props.mode === 'edit' ? route('requests.edit', props.request.id) : route('requests.create'), { category_id: val }, { preserveState: true, replace: true })
  }
})

watch(() => form.professional_id, (val) => {
  const params = { category_id: form.category_id, professional_id: val }
  router.get(props.mode === 'edit' ? route('requests.edit', props.request.id) : route('requests.create'), params, { preserveState: true, replace: true })
})

function submit() {
  if (props.mode === 'edit') {
    form.put(route('requests.update', props.request.id))
  } else {
    form.post(route('requests.store'))
  }
}
</script>

<template>
  <AdminLayout>
    <Head :title="props.mode === 'edit' ? 'Editar Agendamento' : 'Cadastrar Agendamento'" />
    <div class="max-w-3xl mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">
        {{ props.mode === 'edit' ? 'Editar Agendamento' : 'Cadastrar Agendamento' }}
      </h1>
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-3 gap-4">
          <div>
            <InputLabel for="category_id" value="Categoria" />
            <select id="category_id" v-model="form.category_id" class="input">
              <option value="">Selecione</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <InputError :message="form.errors.category_id" />
          </div>
          <div>
            <InputLabel for="service_id" value="Serviço" />
            <select id="service_id" v-model="form.service_id" class="input">
              <option value="">Selecione</option>
              <option v-for="srv in services" :key="srv.id" :value="srv.id">{{ srv.name }}</option>
            </select>
            <InputError :message="form.errors.service_id" />
          </div>
          <div>
            <InputLabel for="professional_id" value="Profissional" />
            <select id="professional_id" v-model="form.professional_id" class="input">
              <option value="">Selecione</option>
              <option v-for="p in professionals" :key="p.id" :value="p.id">{{ p.user?.name || p.uuid }}</option>
            </select>
            <InputError :message="form.errors.professional_id" />
          </div>
          <div>
            <InputLabel for="schedule_availability_id" value="Disponibilidade" />
            <select id="schedule_availability_id" v-model="form.schedule_availability_id" class="input">
              <option value="">Selecione</option>
              <option v-for="(name, id) in availabilities" :key="id" :value="id">{{ name }}</option>
            </select>
            <InputError :message="form.errors.schedule_availability_id" />
          </div>
          <div>
            <InputLabel for="duration" value="Duração (min)" />
            <input id="duration" type="number" v-model="form.duration" class="input" />
            <InputError :message="form.errors.duration" />
          </div>
          <div>
            <InputLabel for="price" value="Valor (R$)" />
            <input id="price" type="number" step="0.01" v-model="form.price" class="input" />
            <InputError :message="form.errors.price" />
          </div>
          <div>
            <InputLabel for="scheduled_at" value="Data & Hora" />
            <input id="scheduled_at" type="datetime-local" v-model="form.scheduled_at" class="input" />
            <InputError :message="form.errors.scheduled_at" />
          </div>
          <div>
            <InputLabel for="address" value="Endereço" />
            <input id="address" type="text" v-model="form.address" class="input" />
            <InputError :message="form.errors.address" />
          </div>
          <div>
            <InputLabel for="photo" value="Foto" />
            <input id="photo" type="file" @change="e => form.photo = e.target.files[0]" class="input" />
            <InputError :message="form.errors.photo" />
            <img v-if="form.photo_path" :src="'/storage/' + form.photo_path" class="mt-2 h-20" />
          </div>
          <div>
            <InputLabel for="priority" value="Prioridade" />
            <select id="priority" v-model="form.priority" class="input">
              <option v-for="(label, val) in priorities" :key="val" :value="val">{{ label }}</option>
            </select>
            <InputError :message="form.errors.priority" />
          </div>
          <div class="col-span-3">
            <InputLabel for="description" value="Descrição" />
            <textarea id="description" v-model="form.description" class="input"></textarea>
            <InputError :message="form.errors.description" />
          </div>
        </div>
        <div>
          <InputLabel for="status" value="Status" />
          <select id="status" v-model="form.status" class="input">
            <option value="pendente">Pendente</option>
            <option value="confirmado">Confirmado</option>
            <option value="cancelado">Cancelado</option>
          </select>
          <InputError :message="form.errors.status" />
        </div>
        <div class="flex gap-2">
          <Link :href="route('requests.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</Link>
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
