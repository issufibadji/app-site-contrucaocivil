<script setup>
import { ref, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const dropdownOpen = ref(false)
const page = usePage()
const notifications = ref([])
const totalUnread = ref(page.props.auth.user.notifications_count || 0)

async function fetchNotifications () {
  try {
    const { data } = await axios.get(route('notifications.unread'))
    notifications.value = data.data
    totalUnread.value = data.total_unread
  } catch (e) {
    console.error('Erro ao buscar notificações', e)
  }
}

onMounted(fetchNotifications)

function toggleDropdown () {
  dropdownOpen.value = !dropdownOpen.value
  if (dropdownOpen.value && notifications.value.length === 0) {
    fetchNotifications()
  }
}

function formatDate (date) {
  return new Date(date).toLocaleString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function markNotification (notification, visitLink = false) {
  if (notification.read_at) {
    if (visitLink && notification.data.url) {
      router.visit(notification.data.url, { preserveState: true, preserveScroll: true })
    }
    return
  }

  router.post(route('notifications.markAsRead'), { id: notification.id }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      notification.read_at = new Date().toISOString()
      if (totalUnread.value > 0) {
        totalUnread.value--
        page.props.auth.user.notifications_count = totalUnread.value
      }
      if (visitLink && notification.data.url) {
        router.visit(notification.data.url, { preserveState: true, preserveScroll: true })
      }
    }
  })
}

function openNotification (notification) {
  markNotification(notification, true)
}

function markAsRead (notification) {
  markNotification(notification, false)
}
</script>

<template>
  <div class="relative">
    <button
      @click="toggleDropdown"
      class="relative text-blue-custom-600 dark:text-blue-custom-300 hover:text-red-500"
      aria-label="Notificações"
      role="button"
    >
      <i class="fas fa-bell text-lg"></i>
      <span
        v-if="totalUnread > 0"
        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
        aria-live="polite"
      >
        {{ totalUnread }}
      </span>
    </button>

    <div v-if="dropdownOpen" class="fixed inset-0 z-40" @click="dropdownOpen = false"></div>

    <div
      v-if="dropdownOpen"
      class="absolute right-0 mt-2 w-64 bg-white dark:bg-blue-custom-extra border border-blue-custom-200 dark:border-blue-custom-700 rounded-md shadow-lg z-50 p-2"
    >
      <div
        v-for="notification in notifications"
        :key="notification.id"
        class="notification-card"
        :class="notification.read_at ? 'read' : 'unread'"
        @click="openNotification(notification)"
      >
        <i :class="['mr-2 mt-1', 'fas', notification.read_at ? 'fa-envelope-open' : 'fa-envelope']"></i>
        <div class="flex-1">
          <h4 :class="notification.read_at ? '' : 'font-bold'">{{ notification.data.title }}</h4>
          <p>{{ formatDate(notification.created_at) }}</p>
        </div>
        <button
          v-if="!notification.read_at"
          class="text-xs text-blue-custom-600 hover:underline ml-2"
          @click.stop="markAsRead(notification)"
        >
          Marcar como lida
        </button>
      </div>
      <div v-if="notifications.length === 0" class="px-4 py-2 text-sm text-blue-custom-700 dark:text-blue-custom-200">
        Nenhuma notificação
      </div>
    </div>
  </div>
</template>

<style scoped>
.notification-card {
  @apply flex items-start p-2 border rounded mb-2 cursor-pointer transition-colors duration-300;
}
.notification-card.unread {
  @apply bg-blue-custom-50 border-blue-custom-500;
}
.notification-card.read {
  @apply bg-white border-gray-200;
}
.notification-card h4 {
  @apply text-sm;
}
.notification-card p {
  @apply text-xs text-gray-500;
}
</style>
