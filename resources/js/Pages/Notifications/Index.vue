<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Toast from '@/Components/Toast.vue'

const props = defineProps({
  notifications: Array,
})

const page = usePage()
</script>

<template>
  <AdminLayout>
    <Head title="Notificações" />
    <Toast v-if="page.props.flash?.success" :message="page.props.flash.success" type="success" />

    <div class="space-y-2">
      <template v-if="notifications.length">
        <article v-for="note in notifications" :key="note.id" class="border border-blue-custom-100 rounded-lg p-4 bg-blue-custom-25">
          <header class="flex items-start justify-between gap-2">
            <h2 class="text-base font-semibold text-blue-custom-800">
              {{ note.data?.title ?? 'Notificação' }}
            </h2>
            <time class="text-xs text-blue-custom-400" :datetime="note.created_at">
              {{ new Date(note.created_at).toLocaleString() }}
            </time>
          </header>
          <p v-if="note.data?.message" class="mt-2 text-sm text-blue-custom-700 whitespace-pre-line">
            {{ note.data.message }}
          </p>
          <footer v-if="note.data?.link" class="mt-3">
            <a
              :href="note.data.link"
              class="inline-flex items-center gap-2 text-sm text-blue-custom-600 hover:text-blue-custom-800"
              target="_blank"
              rel="noopener"
            >
              Acessar link
              <i class="fas fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
            </a>
          </footer>
        </article>
      </template>
      <p v-else class="text-sm text-blue-custom-500">
        Nenhuma notificação encontrada.
      </p>
    </div>
  </AdminLayout>
</template>
