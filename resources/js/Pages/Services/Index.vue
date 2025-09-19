<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import { ref, computed } from 'vue'


const props = defineProps({
  services:   Object,
  categories: Array,
})

const selectedCategory = ref('')

// filtra localmente; para filtro no servidor, envie via query param
const visibleServices = computed(() => {
  if (!selectedCategory.value) {
    return props.services.data
  }
  return props.services.data.filter(s =>
    s.category_id === selectedCategory.value
  )
})
</script>

<template>
  <AdminLayout>
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Serviços</h1>
          <Link
                :href="route('services.create')"
                class="px-4 py-2 bg-blue-600 text-white rounded"
            >
          Novo Serviço
        </Link>
      </div>

      <!-- Filtro de categoria -->
      <div class="mb-6">
        <label class="block mb-1">Filtrar por Categoria:</label>
        <select
          v-model="selectedCategory"
          class="border rounded px-3 py-2"
        >
          <option value="">Todas</option>
          <option
            v-for="cat in categories"
            :key="cat.id"
            :value="cat.id"
          >
            {{ cat.name }}
          </option>
        </select>
      </div>

      <div class="grid grid-cols-3 gap-4">
        <div
          v-for="service in visibleServices"
          :key="service.uuid"
          class="p-4 bg-white rounded shadow"
        >
          <h2 class="font-bold text-lg">{{ service.name }}</h2>
          <p class="text-sm text-gray-600">
            Categoria: {{ service.category.name }}
          </p>
          <p>Duração: {{ service.duration_min }} min</p>
          <p>
            Valor: R$ {{ service.price !== null ? (+service.price).toFixed(2) : '0.00' }}
          </p>

          <!-- descrição, imagem, lista de profissionais… -->
          <div class="flex gap-2 mt-2">
            <Link
              :href="route('services.edit', service.uuid)"
              class="px-3 py-1 bg-yellow-500 text-white rounded"
            >
              Editar
            </Link>
            <Link
              as="button"
              method="delete"
              :href="route('services.destroy', service.uuid)"
              class="px-3 py-1 bg-red-600 text-white rounded"
            >
              Excluir
            </Link>
          </div>
        </div>
        <p
          v-if="visibleServices.length === 0"
          class="col-span-full text-center"
        >
          Nenhum serviço encontrado.
        </p>
      </div>

      <div class="mt-6">
        <Pagination :links="services.links" />
      </div>
    </div>
  </AdminLayout>
</template>
