<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({ availabilities: Object })
</script>

<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto py-10 px-4">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-blue-custom-800">Disponibilidades</h1>
        <Link :href="route('schedule-availabilities.create')" class="bg-blue-custom-600 text-white px-4 py-2 rounded hover:bg-blue-custom-800">+ Disponibilidade</Link>
      </div>
      <div class="bg-white shadow rounded overflow-x-auto">
        <table class="w-full text-sm table-auto">
          <thead class="bg-blue-custom-100 text-left">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Agenda</th>
              <th class="px-4 py-2">Dia</th>
              <th class="px-4 py-2">Início</th>
              <th class="px-4 py-2">Fim</th>
              <th class="px-4 py-2 text-right">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in availabilities.data" :key="item.id" class="border-t">
              <td class="px-4 py-2">{{ item.id }}</td>
              <td class="px-4 py-2">{{ item.schedule?.schedule || '—' }}</td>
              <td class="px-4 py-2">{{ ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'][item.day_of_week] }}</td>
              <td class="px-4 py-2">{{ item.start_time }}</td>
              <td class="px-4 py-2">{{ item.end_time }}</td>
              <td class="px-4 py-2 text-right space-x-2">
                <Link :href="route('schedule-availabilities.edit', item.id)" class="text-yellow-600 hover:underline">Editar</Link>
                <Link as="button" method="delete" :href="route('schedule-availabilities.destroy', item.id)" class="text-red-600 hover:underline" preserve-scroll>Excluir</Link>
              </td>
            </tr>
            <tr v-if="availabilities.data.length === 0">
              <td colspan="6" class="px-4 py-2 text-center">Nenhuma disponibilidade cadastrada.</td>
            </tr>
          </tbody>
        </table>
        <div class="p-2">
          <Pagination :links="availabilities.links" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
