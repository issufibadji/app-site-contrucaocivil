<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
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

    <div class="flex flex-col gap-4">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-semibold text-blue-custom-900">Notificações</h1>
        <Link
          href="/notifications/create"
          class="inline-flex items-center gap-2 rounded-lg bg-blue-custom-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-custom-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-custom-400 focus-visible:ring-offset-2"
        >
          <i class="fas fa-plus text-xs" aria-hidden="true"></i>
          Enviar notificação
        </Link>
      </div>

      <div class="space-y-2">
        <template v-if="notifications.length">
          <article
            v-for="note in notifications"
            :key="note.id"
            class="rounded-lg border border-blue-custom-100 bg-blue-custom-25 p-4"
          >
            <header class="flex items-start justify-between gap-2">
              <h2 class="text-base font-semibold text-blue-custom-800">
                {{ note.data?.title ?? 'Notificação' }}
              </h2>
              <time class="text-xs text-blue-custom-400" :datetime="note.created_at">
                {{ new Date(note.created_at).toLocaleString() }}
              </time>
            </header>
            <p v-if="note.data?.message" class="mt-2 whitespace-pre-line text-sm text-blue-custom-700">
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
    </div>
  </AdminLayout>
</template>
