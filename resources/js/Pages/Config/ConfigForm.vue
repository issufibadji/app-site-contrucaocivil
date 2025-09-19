<template>
  <form @submit.prevent="onSubmit" class="space-y-4">
    <div>
      <label class="block mb-1">Chave</label>
      <input v-model="form.key" type="text" class="input" required />
    </div>
    <div>
      <label class="block mb-1">Valor</label>
      <input v-model="form.value" type="text" class="input" />
    </div>
    <div>
      <label class="block mb-1">Descrição</label>
      <textarea v-model="form.description" rows="3" class="input"></textarea>
    </div>
    <div>
      <label class="block mb-1">Mídia</label>
      <input
        type="file"
        accept="image/*,video/*,application/pdf"
        @change="onFileChange"
        class="input"
      />
      <div v-if="isEditing && props.modelValue.media_url" class="mt-2">
        <img
          v-if="isImage"
          :src="props.modelValue.media_url"
          class="h-24 object-cover rounded"
        />
        <video
          v-else-if="isVideo"
          controls
          class="h-24 rounded"
        >
          <source :src="props.modelValue.media_url" :type="`video/${props.modelValue.extension}`" />
        </video>
        <a
          v-else
          :href="props.modelValue.media_url"
          target="_blank"
          class="text-blue-600 underline"
          >Download</a
        >
      </div>
    </div>
    <div class="flex items-center gap-2">
      <input id="required" type="checkbox" v-model="form.required" class="rounded" />
      <label for="required">Obrigatório</label>
    </div>
    <div class="flex gap-2">
      <Link :href="route('config.index')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</Link>
      <button type="submit" class="px-4 py-2 bg-blue-custom-800 text-white rounded hover:bg-blue-custom-600">
        {{ isEditing ? 'Salvar' : 'Criar' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({ key: '', value: '' })
  }
})
const emit = defineEmits(['submit'])

const form = useForm({
  key: props.modelValue.key,
  value: props.modelValue.value,
  description: props.modelValue.description || '',
  media: null,
  required: props.modelValue.required || false
})

const isEditing = computed(() => !!props.modelValue.id)

const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']
const videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'mkv', 'webm']

const isImage = computed(() => imageExtensions.includes(props.modelValue.extension))
const isVideo = computed(() => videoExtensions.includes(props.modelValue.extension))

function onFileChange(e) {
  form.media = e.target.files[0]
}

function onSubmit() {
  emit('submit', form)
}
</script>

<style scoped>
.input {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-custom-500 focus:ring-blue-custom-500;
}
</style>
