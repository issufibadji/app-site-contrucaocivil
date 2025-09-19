<template>
  <div class="flex items-center space-x-4">
    <AvatarUploader
      :src="user.avatar_url"
      :user-id="user.id"
      @updated="url => user.avatar_url = url"
    />

    <div>
      <h2 class="text-xl font-semibold">{{ user.name }}</h2>
      <p class="text-gray-600">{{ user.email }}</p>
    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import AvatarUploader from '@/Components/AvatarUploader.vue'

const page = usePage()
const user = page.props.auth.user
</script>
