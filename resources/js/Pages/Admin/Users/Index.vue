<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch } from 'vue'
import Pagination from '@/Components/Pagination.vue'
import Toast from '@/Components/Toast.vue'

const props = defineProps({
  users: Object,
  filters: Object,
  flash: Object
})

const search = ref(props.filters.search || '')
const active = ref(props.filters.active ?? '')
const verified = ref(props.filters.verified ?? '')

const loadingStatus = ref(null)
const loading2FA = ref(null)

watch([search, active, verified], () => {
  router.get(route('users.index'), {
    search: search.value,
    active: active.value,
    verified: verified.value,
  }, {
    preserveState: true,
    replace: true
  })
})

function toggleStatus(user) {
  loadingStatus.value = user.id
  router.post(route('users.toggle-status', user.id), {}, {
    preserveScroll: true,
    onFinish: () => loadingStatus.value = null
  })
}

function toggle2FA(user) {
  loading2FA.value = user.id
  router.post(route('users.toggle-2fa', user.id), {}, {
    preserveScroll: true,
    onFinish: () => loading2FA.value = null
  })
}
</script>

<template>
  <AdminLayout>
    <Head title="Usuários" />
    <Toast v-if="$page.props.flash?.success" :message="$page.props.flash.success" type="success" />
    <Toast v-if="$page.props.flash?.error" :message="$page.props.flash.error" type="error" />

    <div class="max-w-7xl mx-auto py-10 px-4">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-blue-custom-800 dark:text-blue-custom-200">Lista de Usuários</h1>
        <Link href="/users/create" class="bg-blue-custom-600 dark:bg-blue-custom-700 text-white px-4 py-2 rounded hover:bg-blue-custom-800 dark:hover:bg-blue-custom-600">
          + Usuário
        </Link>
      </div>

      <div class="flex gap-4 mb-6">
        <input
          type="text"
          placeholder="Buscar nome ou email ..."
          v-model="search"
          class="border border-gray-300 dark:border-gray-600 rounded px-3 py-2 w-1/3 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
        />

        <select v-model="active" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-2 rounded">
          <option value="">Status</option>
          <option value="1">Ativo</option>
          <option value="0">Inativo</option>
        </select>

        <select v-model="verified" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-2 py-2 rounded">
          <option value="">E-mail Verificado</option>
          <option value="1">Sim</option>
          <option value="0">Não</option>
        </select>
      </div>

      <table class="w-full table-auto text-sm bg-white dark:bg-gray-700 shadow rounded">
        <thead class="bg-blue-custom-100 dark:bg-gray-800 text-left text-blue-custom-800 dark:text-blue-custom-200">
          <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Nome</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">2FA</th>
            <th class="px-4 py-2">Verificado</th>
            <th class="px-4 py-2">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.data" :key="user.id" class="border-t border-gray-200 dark:border-gray-600">
            <td class="px-4 py-2">{{ user.id }}</td>
            <td class="px-4 py-2">{{ user.name }}</td>
            <td class="px-4 py-2">{{ user.email }}</td>

            <!-- Status -->
            <td class="px-4 py-2">
              <span
                :class="user.active ? 'text-blue-700 dark:text-blue-300 font-semibold' : 'text-red-600 dark:text-red-400 font-semibold'"
              >
                {{ user.active ? 'Ativo' : 'Inativo' }}
              </span>
            </td>

            <!-- 2FA -->
            <td class="px-4 py-2">
              <span
                :class="user.active_2fa ? 'text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-500 dark:text-gray-400 font-semibold'"
              >
                {{ user.active_2fa ? 'Ativo' : 'Inativo' }}
              </span>
            </td>

            <!-- E-mail verificado -->
            <td class="px-4 py-2">
              <span
                :class="user.email_verified_at ? 'text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-600 dark:text-gray-400'"
              >
                {{ user.email_verified_at ? 'Sim' : 'Não' }}
              </span>
            </td>

            <!-- Ações -->
            <td class="px-4 py-2 space-x-2 flex flex-wrap">
              <Link :href="route('users.edit', user.id)" class="text-yellow-600 dark:text-yellow-400 hover:underline flex items-center">
                <i class="mdi mdi-pencil mr-1"></i> Editar
              </Link>

              <button
                @click="toggleStatus(user)"
                class="text-red-600 dark:text-red-400 hover:underline flex items-center"
                :disabled="loadingStatus === user.id"
              >
                <i class="mdi" :class="user.active ? 'mdi-close-circle-outline' : 'mdi-check-circle-outline'"></i>
                <span class="ml-1">
                  {{ loadingStatus === user.id ? 'Aguarde...' : (user.active ? 'Desativar' : 'Ativar') }}
                </span>
              </button>

              <button
                @click="toggle2FA(user)"
                class="text-blue-custom-600 dark:text-blue-custom-300 hover:underline flex items-center"
                :disabled="loading2FA === user.id"
              >
                <i class="mdi" :class="user.active_2fa ? 'mdi-lock-off-outline' : 'mdi-lock-open-outline'"></i>
                <span class="ml-1">
                  {{ loading2FA === user.id ? 'Processando...' : (user.active_2fa ? 'Desativar 2FA' : 'Ativar 2FA') }}
                </span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="mt-4">
        <Pagination :links="users.links" />
      </div>
    </div>
  </AdminLayout>
</template>

<style>
.mdi {
  font-size: 16px;
}
</style>
