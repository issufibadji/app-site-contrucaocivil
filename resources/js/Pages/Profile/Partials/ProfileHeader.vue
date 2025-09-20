<template>
  <div class="flex items-center space-x-4">
    <AvatarUploader
      :src="avatarSrc"
      @updated="handleAvatarUpdated"
    />

    <div>
      <h2 class="text-xl font-semibold">{{ user.name }}</h2>
      <p class="text-gray-600">{{ user.email }}</p>
      <p v-if="currentProfile" class="text-sm text-blue-custom-600">Perfil atual: {{ currentProfile.name }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AvatarUploader from '@/Components/AvatarUploader.vue'

const page = usePage()
const user = computed(() => page.props.auth.user ?? {})
const currentProfile = computed(() => user.value.current_profile ?? null)
const avatarSrc = ref(user.value.avatar_url || '')

watch(
  () => user.value.avatar_url,
  value => {
    avatarSrc.value = value || ''
  }
)

function handleAvatarUpdated(url) {
  avatarSrc.value = url
}
</script>
