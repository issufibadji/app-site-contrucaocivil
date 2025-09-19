<template>
  <AdminLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Gestão de Clientes</h1>
        <Link :href="route('clients.create')"
        class="bg-blue-custom-600 text-white px-4 py-2 rounded hover:bg-blue-custom-800">
          + Novo Cliente
        </Link>
      </div>

      <div class="overflow-x-auto bg-white dark:bg-blue-custom-800 rounded shadow">
        <table class="min-w-full text-sm table-auto">
          <thead class="bg-blue-custom-100 dark:bg-blue-custom-800 text-left text-blue-custom-800 dark:text-white">
            <tr>
              <th class="px-4 py-3">ID</th>
              <th class="px-4 py-3">Usuário</th>
              <th class="px-4 py-3">Gênero</th>
              <th class="px-4 py-3">Telefone</th>
              <th class="px-4 py-3">Endereço</th>
              <th class="px-4 py-3">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="client in clients.data"
              :key="client.uuid"
              class="border-t hover:bg-blue-custom-50 dark:hover:bg-blue-custom-900"
            >
              <td class="px-4 py-2">{{ client.id }}</td>
              <td class="px-4 py-2">{{ client.user?.name || '-' }}</td>
              <td class="px-4 py-2">{{ client.gender || '—'}}</td>
              <td class="px-4 py-2">{{ client.user?.phones?.[0]?.phone || '—' }}</td>
              <td class="px-4 py-2">{{ client.user?.addresses?.[0]?.street || '—' }}</td>
              <td class="px-4 py-2 space-x-2">
                <Link :href="route('clients.edit', { client: client.uuid })" class="text-blue-600 hover:underline">
                  Editar
                </Link>
                <button
                  @click="deleteClient(client.uuid)"
                  class="text-red-600 hover:underline"
                >
                  Excluir
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="clients?.data?.length === 0" class="col-span-full text-center text-gray-500">Nenhum cliente cadastrado.</div>
        <div class="p-2">
          <Pagination :links="clients.links" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  clients:{
    type:Object,
    default: () => ({ data: [], links: [] })
  }
})
function deleteClient(id) {
  if (confirm('Deseja excluir este cliente?')) {
    router.delete(route('clients.destroy', { client: id }))
  }
}
</script>
