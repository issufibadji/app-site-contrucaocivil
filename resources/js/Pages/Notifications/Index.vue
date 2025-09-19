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

    <ul class="space-y-2">
      <li v-for="note in notifications" :key="note.id" class="border p-2 rounded">
        <div class="font-semibold">{{ note.data.title }}</div>
        <div class="text-sm text-gray-600">{{ new Date(note.created_at).toLocaleString() }}</div>
      </li>
    </ul>
  </AdminLayout>
</template>
