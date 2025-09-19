<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

const props = defineProps({
  requests: Array,
  appointments: Array
})

function actionClass(action) {
  switch (action) {
    case 'repetir':
      return 'bg-blue-custom-600 text-white px-3 py-1 rounded hover:bg-blue-custom-700'
    case 'avaliar':
      return 'bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600'
    case 'cancelar':
      return 'bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700'
    default:
      return ''
  }
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto py-10 px-4">
      <div class="bg-blue-custom-600 text-white px-4 py-2 rounded-t-md mb-4">
        <h1 class="text-xl font-bold">Histórico de Serviços</h1>
      </div>

      <div v-if="requests.length" class="mb-8">
        <h2 class="text-lg font-bold text-blue-custom-deep mb-2">Histórico de Solicitações</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="(req, idx) in requests" :key="idx" class="flex bg-white rounded shadow">
            <div class="w-2 bg-blue-custom-600 rounded-l"></div>
            <div class="p-4 flex-1">
              <h3 class="text-lg font-semibold text-blue-custom-800">{{ req.serviceName }}</h3>
              <p class="text-sm text-gray-700">{{ req.scheduledDate }}</p>
              <p class="text-sm text-gray-700 capitalize">{{ req.status }}</p>
              <div class="mt-3 flex gap-2">
                <button v-for="action in req.actions" :key="action" :class="actionClass(action)">{{ action }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="appointments.length">
        <h2 class="text-lg font-bold text-blue-custom-deep mb-2">Histórico de Serviços Prestados</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="(app, idx) in appointments" :key="idx" class="flex bg-white rounded shadow">
            <div class="w-2 bg-blue-custom-600 rounded-l"></div>
            <div class="p-4 flex-1">
              <h3 class="text-lg font-semibold text-blue-custom-800">{{ app.serviceName }}</h3>
              <p class="text-sm text-gray-700">{{ app.scheduledDate }}</p>
              <p class="text-sm text-gray-700 capitalize">{{ app.status }}</p>
              <div class="mt-3 flex gap-2">
                <button v-for="action in app.actions" :key="action" :class="actionClass(action)">{{ action }}</button>
                <Link
                  v-if="app.status === 'concluido'"
                  :href="route('appointments.review.create', app.id)"
                  class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 ml-2"
                >
                  Avaliar
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
