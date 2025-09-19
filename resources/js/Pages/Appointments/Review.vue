<template>
  <AdminLayout>
    <div class="max-w-xl mx-auto py-8">
      <div class="bg-white border-l-4 border-blue-600 p-6 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-2">Serviço: {{ appointment.service.name }}</h2>
        <p>Prestador: {{ appointment.professional.user.name }}</p>
        <p>Data: {{ new Date(appointment.scheduled_at).toLocaleDateString('pt-BR') }}</p>
      </div>

      <form @submit.prevent="submit" class="space-y-4 bg-white p-6 rounded shadow">
        <div>
          <label class="block">Classificação</label>
          <select v-model="form.rating" class="mt-1 w-full border rounded">
            <option disabled value="">Selecione</option>
            <option v-for="n in 5" :key="n" :value="n">{{ n }} ★</option>
          </select>
        </div>

        <div>
          <label class="block">Comentário</label>
          <textarea v-model="form.comment" rows="4" class="mt-1 w-full border rounded"
            placeholder="Escreva seu feedback…"></textarea>
        </div>

        <button type="submit"
          :disabled="form.processing"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          {{ form.processing ? 'Enviando…' : 'Enviar Avaliação' }}
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({ appointment: Object })

const form = useForm({ rating: '', comment: '' })

function submit() {
  form.post(route('appointments.review.store', props.appointment.id), { preserveScroll: true })
}
</script>
