<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ requests: Array })
const requests = ref(props.requests)

function formatDate(date) {
  const d = new Date(date)
  return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
}

function dayOfWeek(date) {
  return new Date(date).toLocaleDateString('pt-BR', { weekday: 'long' })
}

function updateStatus(id, status) {
  router.patch(route('requests.updateStatus', id), { status }, {
    preserveScroll: true,
    onSuccess: () => {
      requests.value = requests.value.filter(r => r.id !== id)
    }
  })
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto py-10 px-4">
      <div class="bg-blue-custom-600 text-white px-4 py-2 rounded-t-md mb-4">
        <h1 class="text-xl font-bold">Solicitações Recebidas</h1>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="req in requests" :key="req.id" class="flex bg-white rounded shadow">
          <div class="w-2 bg-blue-custom-600 rounded-l"></div>
          <div class="p-4 flex-1">
            <div class="flex items-center mb-2">
              <img v-if="req.photo_path" :src="'/storage/' + req.photo_path" class="h-10 w-10 rounded-full mr-3" />
              <h2 class="text-lg font-semibold text-blue-custom-800">{{ req.client?.user?.name }}</h2>
            </div>
            <p class="text-sm text-gray-700">{{ req.service?.name || req.description }}</p>
            <p class="text-sm text-gray-700">{{ dayOfWeek(req.scheduled_at) }} - {{ formatDate(req.scheduled_at) }}</p>
            <p v-if="req.address" class="text-sm text-gray-700">{{ req.address }}</p>
            <div class="mt-3 flex gap-2">
              <button @click="updateStatus(req.id, 'confirmado')" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Aceitar</button>
              <button @click="updateStatus(req.id, 'cancelado')" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Recusar</button>
            </div>
          </div>
        </div>
        <p v-if="requests.length === 0" class="col-span-2 text-center text-gray-500">Nenhuma solicitação pendente.</p>
      </div>
    </div>
  </AdminLayout>
</template>
