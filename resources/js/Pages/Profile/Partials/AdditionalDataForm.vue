<script setup>
import { computed, watch } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
  additionalData: {
    type: Object,
    default: null,
  },
})

const hasRecord = computed(() => Boolean(props.additionalData))

const form = useForm({
  cpf: '',
  rg: '',
  birth_date: '',
  phone: '',
  secondary_phone: '',
})

watch(
  () => props.additionalData,
  value => {
    form.defaults({
      cpf: value?.cpf ?? '',
      rg: value?.rg ?? '',
      birth_date: value?.birth_date ?? '',
      phone: value?.phone ?? '',
      secondary_phone: value?.secondary_phone ?? '',
    })
    form.reset()
  },
  { immediate: true }
)

function submitForm() {
  const method = hasRecord.value ? form.put : form.post
  const routeName = hasRecord.value ? 'profile.additional-data.update' : 'profile.additional-data.store'

  method(route(routeName), {
    preserveScroll: true,
  })
}

function deleteRecord() {
  if (!hasRecord.value) return

  if (confirm('Deseja remover os dados adicionais?')) {
    router.delete(route('profile.additional-data.destroy'), {
      preserveScroll: true,
      onFinish: () => {
        form.reset()
        form.clearErrors()
      },
    })
  }
}
</script>

<template>
  <div class="mt-6 border-t pt-6 space-y-6">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold text-blue-custom-800">Dados adicionais</h2>
      <button
        v-if="hasRecord"
        type="button"
        @click="deleteRecord"
        class="text-sm text-red-600 hover:text-red-700"
      >
        Remover
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">CPF</label>
        <input
          type="text"
          v-model="form.cpf"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
        <p v-if="form.errors.cpf" class="text-sm text-red-600 mt-1">{{ form.errors.cpf }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">RG</label>
        <input
          type="text"
          v-model="form.rg"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
        <p v-if="form.errors.rg" class="text-sm text-red-600 mt-1">{{ form.errors.rg }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Data de nascimento</label>
        <input
          type="date"
          v-model="form.birth_date"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
        <p v-if="form.errors.birth_date" class="text-sm text-red-600 mt-1">{{ form.errors.birth_date }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Telefone principal</label>
        <input
          type="text"
          v-model="form.phone"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
        <p v-if="form.errors.phone" class="text-sm text-red-600 mt-1">{{ form.errors.phone }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Telefone secundário</label>
        <input
          type="text"
          v-model="form.secondary_phone"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
        <p v-if="form.errors.secondary_phone" class="text-sm text-red-600 mt-1">{{ form.errors.secondary_phone }}</p>
      </div>
    </div>

    <div>
      <button
        type="button"
        @click="submitForm"
        class="bg-blue-custom-600 hover:bg-blue-custom-800 text-white font-semibold px-4 py-2 rounded"
      >
        Salvar dados
      </button>
    </div>
  </div>
</template>
