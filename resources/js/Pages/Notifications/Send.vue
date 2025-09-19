<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Toast from '@/Components/Toast.vue'

const props = defineProps({
  users: Array
})

const form = useForm({
  title: '',
  message: '',
  link: '',
  icon: '',
  users: [],
  notification_type: 'internal'
})

function submit() {
  form.post(route('notifications.send'), {
    onSuccess: () => form.reset(),
  })
}

const page = usePage()
</script>

<template>
  <AdminLayout>
    <Head title="Enviar Notificação" />
    <Toast v-if="$page.props.flash?.success" :message="$page.props.flash.success" type="success" />

    <form @submit.prevent="submit" class="max-w-xl space-y-4">
      <div>
        <label class="block text-sm font-medium">Título</label>
        <input v-model="form.title" type="text" class="mt-1 w-full border rounded" />
        <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium">Mensagem</label>
        <textarea v-model="form.message" class="mt-1 w-full border rounded" />
        <div v-if="form.errors.message" class="text-red-500 text-sm">{{ form.errors.message }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium">Link</label>
        <input v-model="form.link" type="text" class="mt-1 w-full border rounded" />
        <div v-if="form.errors.link" class="text-red-500 text-sm">{{ form.errors.link }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium">Ícone (opcional)</label>
        <input v-model="form.icon" type="text" class="mt-1 w-full border rounded" />
        <div v-if="form.errors.icon" class="text-red-500 text-sm">{{ form.errors.icon }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium">Usuários</label>
        <select v-model="form.users" multiple class="mt-1 w-full border rounded">
          <option v-for="user in props.users" :key="user.id" :value="user.id">{{ user.name }}</option>
        </select>
        <div v-if="form.errors.users" class="text-red-500 text-sm">{{ form.errors.users }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium">Tipo</label>
        <select v-model="form.notification_type" class="mt-1 w-full border rounded">
          <option value="internal">Interna</option>
          <option value="webpush">Web Push</option>
          <option value="both">Ambas</option>
        </select>
        <div v-if="form.errors.notification_type" class="text-red-500 text-sm">{{ form.errors.notification_type }}</div>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enviar</button>
    </form>
  </AdminLayout>
</template>
