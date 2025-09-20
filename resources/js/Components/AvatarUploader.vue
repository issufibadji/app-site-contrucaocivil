<!-- resources/js/Components/AvatarUploader.vue -->
<template>
  <div class="relative group w-20 h-20">
    <img
      v-if="currentSrc"
      :src="currentSrc"
      alt="Avatar"
      class="w-full h-full rounded-full object-cover"
    />
    <div
      v-else
      class="w-full h-full rounded-full bg-gray-300 flex items-center justify-center text-2xl font-bold text-white"
    >
      {{ initials }}
    </div>

    <div
      class="absolute inset-0 bg-black bg-opacity-40 rounded-full flex items-center justify-center text-white text-xl
             opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
      @click="triggerFileSelect"
    >
      <i class="fas fa-pencil-alt"></i>
    </div>

    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      class="hidden"
      @change="onFileChange"
    />
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
// não precisa instalar @inertiajs/inertia: use o router do adapter Vue3
import { usePage, router } from '@inertiajs/vue3'

const props = defineProps({
  src: { type: String, default: '' },
})
const emit = defineEmits(['updated'])

// pega o nome do usuário via usePage()
const page = usePage()
const name = computed(() => page.props.auth.user?.name || '')

const currentSrc = ref(props.src)

watch(
  () => props.src,
  value => {
    currentSrc.value = value
  }
)

// monta as iniciais
const initials = computed(() =>
  name.value
    .split(' ')
    .map(w => w[0]?.toUpperCase() || '')
    .slice(0, 2)
    .join('')
)

const fileInput = ref(null)
function triggerFileSelect() {
  fileInput.value.click()
}

function onFileChange(e) {
  const file = e.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('avatar', file)

  const previousSrc = currentSrc.value
  const previewUrl = URL.createObjectURL(file)
  currentSrc.value = previewUrl

  router.post(
    route('profile.updateAvatar'),
    formData,
    {
      preserveScroll: true,
      forceFormData: true,
      onSuccess: page => {
        const newUrl = page.props.auth.user.avatar_url
        currentSrc.value = newUrl
        emit('updated', newUrl)
      },
      onError: () => {
        currentSrc.value = previousSrc
      },
      onCancel: () => {
        currentSrc.value = previousSrc
      },
      onFinish: () => {
        if (fileInput.value) {
          fileInput.value.value = ''
        }
        URL.revokeObjectURL(previewUrl)
      },
    }
  )
}
</script>
