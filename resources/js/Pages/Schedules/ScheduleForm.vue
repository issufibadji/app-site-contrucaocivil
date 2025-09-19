<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link, Head } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  schedule: Object,
  professionals: Object,
  mode: String
})

const form = useForm({
  schedule: props.schedule?.schedule || '',
  professional_id: props.schedule?.professional_id || '',
  day_of_week: '',
  start_time: '',
  end_time: ''
})

function submit() {
  if (props.mode === 'edit') {
    form.put(route('schedules.update', props.schedule.id))
  } else {
    form.post(route('schedules.store'))
  }
}
</script>

<template>
  <AdminLayout>
    <Head :title="props.mode === 'edit' ? 'Editar Agenda' : 'Cadastrar Agenda'" />
    <div class="max-w-3xl mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">{{ props.mode === 'edit' ? 'Editar Agenda' : 'Cadastrar Agenda' }}</h1>
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-6 gap-4">
          <div class="col-span-4">
            <InputLabel for="schedule" value="Título" />
            <input id="schedule" v-model="form.schedule" type="text" class="input" />
            <InputError :message="form.errors.schedule" />
          </div>
          <div class="col-span-2">
            <InputLabel for="professional_id" value="Profissional" />
            <select id="professional_id" v-model="form.professional_id" class="input">
              <option value="">Selecione</option>
              <option v-for="(name, id) in professionals" :key="id" :value="id">{{ name }}</option>
            </select>
            <InputError :message="form.errors.professional_id" />
          </div>
        </div>
        <div class="grid grid-cols-6 gap-4">
          <div class="col-span-2">
            <InputLabel for="day_of_week" value="Dia" />
            <select id="day_of_week" v-model="form.day_of_week" class="input">
              <option value="">Selecione</option>
              <option v-for="(d, idx) in ['Dom','Seg','Ter','Qua','Qui','Sex','Sab']" :key="idx" :value="idx">{{ d }}</option>
            </select>
            <InputError :message="form.errors.day_of_week" />
          </div>
          <div class="col-span-2">
            <InputLabel for="start_time" value="Início" />
            <input id="start_time" type="time" v-model="form.start_time" class="input" />
            <InputError :message="form.errors.start_time" />
          </div>
          <div class="col-span-2">
            <InputLabel for="end_time" value="Fim" />
            <input id="end_time" type="time" v-model="form.end_time" class="input" />
            <InputError :message="form.errors.end_time" />
          </div>
        </div>
        <div class="flex gap-2">
          <Link :href="route('schedules.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</Link>
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
