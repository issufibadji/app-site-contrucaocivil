<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  professionals:{
    type:Object,
    default: () => ({ data: [], links: [] })
  }
})
</script>

<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto py-10 px-4">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-blue-custom-800">Gestão de Profissionais</h1>
        <Link :href="route('professionals.create')" class="bg-blue-custom-600 text-white px-4 py-2 rounded hover:bg-blue-custom-800">+ Profissional</Link>
      </div>
      <div class="bg-white shadow rounded overflow-x-auto">
        <table class="w-full text-sm table-auto">
          <thead class="bg-blue-custom-100 text-left">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Nome</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-3">Telefone</th>
              <th class="px-4 py-3">Endereço</th>
              <th class="px-4 py-2">Profissões</th>
              <th class="px-4 py-2">Descrição</th>
              <th class="px-4 py-2 text-right">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="pro in professionals.data" :key="pro.id" class="border-t">
            <td class="px-4 py-2">{{ pro.user?.id }}</td>
              <td class="px-4 py-2">{{ pro.user?.name || '—' }}</td>
              <td class="px-4 py-2">{{ pro.user?.email || '—' }}</td>
               <td class="px-4 py-2">{{ pro.user?.phones?.[0]?.phone || '—' }}</td>
              <td class="px-4 py-2">{{ pro.user?.addresses?.[0]?.street || '—' }}</td>
              <td class="px-4 py-2">{{ (pro.professions || []).join(', ') }}</td>
              <td class="px-4 py-2">{{ pro.description }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <Link :href="route('professionals.edit', pro.uuid)" class="text-yellow-600 hover:underline">Editar</Link>
                <Link as="button" method="delete" :href="route('professionals.destroy', pro.uuid)" class="text-red-600 hover:underline" preserve-scroll>Excluir</Link>
              </td>
            </tr>
            <tr v-if="professionals?.data?.length === 0">
              <td colspan="8" class="px-4 py-2 text-center">Nenhum profissional cadastrado.</td>
            </tr>
          </tbody>
        </table>
        <div class="p-2">
          <Pagination :links="professionals.links" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
