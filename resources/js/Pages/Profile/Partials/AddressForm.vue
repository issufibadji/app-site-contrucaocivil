<script setup>
import { computed, watch } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
  address: {
    type: Object,
    default: null,
  },
})

const hasRecord = computed(() => Boolean(props.address))

const form = useForm({
  zip_code: '',
  street: '',
  number: '',
  complement: '',
  district: '',
  city: '',
  state: '',
  country: 'Brasil',
  is_international: false,
})

watch(
  () => props.address,
  value => {
    form.defaults({
      zip_code: value?.zip_code ?? '',
      street: value?.street ?? '',
      number: value?.number ?? '',
      complement: value?.complement ?? '',
      district: value?.district ?? '',
      city: value?.city ?? '',
      state: value?.state ?? '',
      country: value?.country ?? 'Brasil',
      is_international: false,
    })
    form.reset()
  },
  { immediate: true }
)

function submitForm() {
  const method = hasRecord.value ? form.put : form.post
  const routeName = hasRecord.value ? 'profile.address.update' : 'profile.address.store'

  method(route(routeName), {
    preserveScroll: true,
  })
}

function deleteRecord() {
  if (!hasRecord.value) return

  if (confirm('Deseja remover o endereço?')) {
    router.delete(route('profile.address.destroy'), {
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
      <h2 class="text-lg font-semibold text-blue-custom-800">Endereço</h2>
      <button
        v-if="hasRecord"
        type="button"
        @click="deleteRecord"
        class="text-sm text-red-600 hover:text-red-700"
      >
        Remover
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">CEP</label>
        <input v-model="form.zip_code" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.zip_code" class="text-sm text-red-600 mt-1">{{ form.errors.zip_code }}</p>
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Rua</label>
        <input v-model="form.street" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.street" class="text-sm text-red-600 mt-1">{{ form.errors.street }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Número</label>
        <input v-model="form.number" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.number" class="text-sm text-red-600 mt-1">{{ form.errors.number }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Complemento</label>
        <input v-model="form.complement" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.complement" class="text-sm text-red-600 mt-1">{{ form.errors.complement }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Bairro</label>
        <input v-model="form.district" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.district" class="text-sm text-red-600 mt-1">{{ form.errors.district }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Cidade</label>
        <input v-model="form.city" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.city" class="text-sm text-red-600 mt-1">{{ form.errors.city }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Estado</label>
        <input v-model="form.state" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.state" class="text-sm text-red-600 mt-1">{{ form.errors.state }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">País</label>
        <input v-model="form.country" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="form.errors.country" class="text-sm text-red-600 mt-1">{{ form.errors.country }}</p>
      </div>
    </div>

    <div>
      <button
        type="button"
        @click="submitForm"
        class="bg-blue-custom-600 hover:bg-blue-custom-800 text-white font-semibold px-4 py-2 rounded"
      >
        Salvar endereço
      </button>
    </div>
  </div>
</template>
