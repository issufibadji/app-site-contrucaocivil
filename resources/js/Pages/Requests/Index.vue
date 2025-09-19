<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
const props = defineProps({ requests: Object })
</script>
<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto py-10 px-4">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-blue-custom-800">Minhas Solicitações</h1>
        <Link :href="route('requests.create')" class="bg-blue-custom-600 text-white px-4 py-2 rounded hover:bg-blue-custom-800">+ Solicitar</Link>
      </div>
      <div class="bg-white shadow rounded overflow-x-auto">
        <table class="w-full text-sm table-auto">
          <thead class="bg-blue-custom-100 text-left">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Serviço</th>
              <th class="px-4 py-2">Categoria</th>
              <th class="px-4 py-2">Profissional</th>
              <th class="px-4 py-2">Data & Hora</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2 text-right">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="req in requests.data" :key="req.id" class="border-t">
              <td class="px-4 py-2">{{ req.id }}</td>
              <td class="px-4 py-2">{{ req.service?.name || '—' }}</td>
              <td class="px-4 py-2">{{ req.category?.name || '—' }}</td>
                            <td class="px-4 py-2">{{ req.professional?.user?.name || '—' }}</td>
              <td class="px-4 py-2">{{ req.scheduled_at }}</td>
              <td class="px-4 py-2">{{ req.status }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <Link :href="route('requests.edit', req.id)" class="text-yellow-600 hover:underline">Editar</Link>
                <Link as="button" method="delete" :href="route('requests.destroy', req.id)" class="text-red-600 hover:underline" preserve-scroll>Excluir</Link>
              </td>
            </tr>
            <tr v-if="requests.data.length === 0">
              <td colspan="7" class="px-4 py-2 text-center">Nenhuma solicitação encontrada.</td>
            </tr>
          </tbody>
        </table>
        <div class="p-2">
          <Pagination :links="requests.links" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
