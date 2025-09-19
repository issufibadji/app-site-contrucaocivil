<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({})
  },
  recentUsers: {
    type: Array,
    default: () => []
  },
  recentNotifications: {
    type: Array,
    default: () => []
  }
})

const statCards = computed(() => [
  {
    title: 'Usuários cadastrados',
    icon: 'fas fa-users',
    value: props.stats.total_users ?? 0,
    description: 'Total de contas ativas na plataforma.',
  },
  {
    title: 'Usuários ativos',
    icon: 'fas fa-user-check',
    value: props.stats.active_users ?? 0,
    description: 'Contas habilitadas para acesso.',
  },
  {
    title: '2FA habilitado',
    icon: 'fas fa-shield-alt',
    value: props.stats.users_with_2fa ?? 0,
    description: 'Usuários com autenticação em dois fatores.',
  },
  {
    title: 'Notificações não lidas',
    icon: 'fas fa-bell',
    value: props.stats.unread_notifications ?? 0,
    description: 'Alertas pendentes para o seu usuário.',
  },
])

const formatDate = (value) => (value ? new Date(value).toLocaleDateString() : '-')
const formatDateTime = (value) => (value ? new Date(value).toLocaleString() : '-')
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <div class="py-6 space-y-8">
      <header class="space-y-1">
        <h1 class="text-3xl font-semibold text-blue-custom-900 dark:text-white">Painel de controle</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Acompanhe rapidamente os principais indicadores da aplicação e os últimos registros.
        </p>
      </header>

      <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <article
          v-for="card in statCards"
          :key="card.title"
          class="rounded-lg border border-blue-custom-100 bg-white p-4 shadow-sm dark:border-blue-custom-800 dark:bg-gray-800"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ card.title }}</p>
              <p class="mt-2 text-2xl font-semibold text-blue-custom-900 dark:text-blue-custom-100">{{ card.value }}</p>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-custom-100 text-blue-custom-700">
              <i :class="card.icon"></i>
            </span>
          </div>
          <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">{{ card.description }}</p>
        </article>
      </section>

      <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <section class="rounded-lg border border-blue-custom-100 bg-white p-6 shadow-sm dark:border-blue-custom-800 dark:bg-gray-800">
          <h2 class="text-lg font-semibold text-blue-custom-900 dark:text-blue-custom-100">Últimos usuários cadastrados</h2>
          <p class="text-xs text-gray-500 dark:text-gray-400">Registros criados recentemente.</p>

          <ul class="mt-4 space-y-3" v-if="recentUsers.length">
            <li
              v-for="user in recentUsers"
              :key="user.id"
              class="flex items-center justify-between rounded-md bg-blue-custom-50 px-4 py-3 dark:bg-gray-700"
            >
              <div>
                <p class="font-medium text-blue-custom-900 dark:text-blue-custom-100">{{ user.name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</p>
              </div>
              <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(user.created_at) }}</span>
            </li>
          </ul>
          <p v-else class="mt-6 text-sm text-gray-500 dark:text-gray-400">Nenhum usuário cadastrado recentemente.</p>
        </section>

        <section class="rounded-lg border border-blue-custom-100 bg-white p-6 shadow-sm dark:border-blue-custom-800 dark:bg-gray-800">
          <h2 class="text-lg font-semibold text-blue-custom-900 dark:text-blue-custom-100">Notificações recentes</h2>
          <p class="text-xs text-gray-500 dark:text-gray-400">Mensagens enviadas pelo sistema.</p>

          <ul class="mt-4 space-y-3" v-if="recentNotifications.length">
            <li
              v-for="notification in recentNotifications"
              :key="notification.id"
              class="rounded-md border border-blue-custom-100 bg-blue-custom-50 p-4 dark:border-blue-custom-700 dark:bg-gray-700"
            >
              <p class="text-sm font-semibold text-blue-custom-900 dark:text-blue-custom-100">{{ notification.title || 'Notificação' }}</p>
              <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">{{ notification.message || 'Sem descrição adicional.' }}</p>
              <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>{{ formatDateTime(notification.created_at) }}</span>
                <span :class="notification.read_at ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400'">
                  {{ notification.read_at ? 'Lida' : 'Não lida' }}
                </span>
              </div>
            </li>
          </ul>
          <p v-else class="mt-6 text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação registrada.</p>
        </section>
      </div>
    </div>
  </AdminLayout>
</template>
