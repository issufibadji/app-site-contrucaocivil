<template>
  <AdminLayout>
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">configuração de Sistema</h1>
        <Link :href="route('config.create')" class="bg-blue-custom-600 text-white px-4 py-2 rounded hover:bg-blue-custom-800">+ Nova Configuração</Link>
      </div>
      <div class="overflow-x-auto bg-white dark:bg-blue-custom-800 rounded shadow">
        <table class="min-w-full text-sm table-auto">
          <thead class="bg-blue-custom-100 dark:bg-blue-custom-800 text-left text-blue-custom-800 dark:text-white">
            <tr>
              <th class="px-4 py-3">Chave</th>
              <th class="px-4 py-3">Valor</th>
              <th class="px-4 py-3">Descrição</th>
              <th class="px-4 py-3">Mídia</th>
              <th class="px-4 py-3">Obrigatório</th>
              <th class="px-4 py-3">Ações</th>
            </tr>
          </thead>
                    <tbody>
            <tr v-for="config in configs" :key="config.id" class="border-t hover:bg-blue-custom-50 dark:hover:bg-blue-custom-900">
              <td class="px-4 py-2">{{ config.key }}</td>
              <td class="px-4 py-2">{{ config.value }}</td>
              <td class="px-4 py-2">{{ config.description }}</td>
              <td class="px-4 py-2">
                <template v-if="config.media_url">
                  <img
                    v-if="isImage(config.extension)"
                    :src="config.media_url"
                    class="h-10 w-10 object-cover"
                  />
                  <video
                    v-else-if="isVideo(config.extension)"
                    controls
                    class="h-10 w-10 object-cover"
                  >
                    <source :src="config.media_url" :type="`video/${config.extension}`" />
                  </video>
                  <a
                    v-else
                    :href="config.media_url"
                    target="_blank"
                    class="text-blue-600 underline"
                    >Download</a
                  >
                </template>
              </td>
              <td class="px-4 py-2">{{ config.required ? 'Sim' : 'Não' }}</td>
              <td class="px-4 py-2 space-x-2">
                <Link :href="route('config.edit', config.id)" class="text-blue-600 hover:underline">Editar</Link>
                <button
                  v-if="!config.required"
                  @click="deleteConfig(config.id)"
                  class="text-red-600 hover:underline"
                >
                  Excluir
                </button>
              </td>
            </tr>
            <tr v-if="configs.length === 0">
              <td colspan="6" class="px-4 py-2 text-center">Nenhuma configuração encontrada.</td>
            </tr>
          </tbody>

        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  configs: {
    type: Array,
    default: () => []
  }
})

function isImage(extension) {
  return ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)
}

function isVideo(extension) {
  return ['mp4', 'mov', 'avi', 'wmv', 'mkv', 'webm'].includes(extension)
}

function deleteConfig(id) {
  if (confirm('Deseja excluir esta configura\u00e7\u00e3o?')) {
    router.delete(route('config.destroy', id))
  }
}
</script>
