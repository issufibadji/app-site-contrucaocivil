<!-- resources/js/Pages/Perfil/Partials/AddressForm.vue -->
<template>
  <form @submit.prevent="submit" class="space-y-6">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-2">
        <InputLabel for="cep" value="CEP" />
        <TextInput id="cep" v-model="form.cep" class="input w-full" />
        <InputError :message="form.errors.cep" />
      </div>

      <div class="col-span-1">
        <InputLabel for="uf" value="UF" />
        <TextInput id="uf" v-model="form.uf" class="input w-full" />
        <InputError :message="form.errors.uf" />
      </div>

      <div class="col-span-3">
        <InputLabel for="city" value="Cidade" />
        <TextInput id="city" v-model="form.city" class="input w-full" />
        <InputError :message="form.errors.city" />
      </div>

      <div class="col-span-6">
        <InputLabel for="street" value="Rua" />
        <TextInput id="street" v-model="form.street" class="input w-full" />
        <InputError :message="form.errors.street" />
      </div>

      <div class="col-span-4">
        <InputLabel for="complement" value="Complemento" />
        <TextInput id="complement" v-model="form.complement" class="input w-full" />
        <InputError :message="form.errors.complement" />
      </div>

      <div class="col-span-2 flex items-end space-x-2">
        <PrimaryButton class="px-6" :disabled="form.processing">
          {{ props.mode === 'edit' ? 'Salvar' : 'Adicionar' }}
        </PrimaryButton>
        <SecondaryButton type="button" @click="$emit('cancel')">
          Cancelar
        </SecondaryButton>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { defineProps, defineEmits } from 'vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
  mode: { type: String, default: 'create' },
  address: { type: Object, default: () => ({}) }
})

// Emite evento para fechar/atualizar o card pai
const emit = defineEmits(['save', 'cancel'])

const form = useForm({
  cep: props.address.cep || '',
  uf: props.address.uf || '',
  city: props.address.city || '',
  street: props.address.street || '',
  complement: props.address.complement || '',
})

function submit() {
  if (props.mode === 'edit' && props.address.id) {
    form.put(route('addresses.update', props.address.id), {
      onSuccess: () => emit('save', form.data)
    })
  } else {
    form.post(route('addresses.store'), {
      onSuccess: () => emit('save', form.data)
    })
  }
}
</script>
