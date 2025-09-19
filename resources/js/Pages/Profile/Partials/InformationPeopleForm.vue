<!-- InformationPeopleForm.vue -->
<script setup>
import { defineProps, defineEmits } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  professional: { type: Object, required: true },
  categories:   { type: Array,  required: true },
})

const emit = defineEmits(['saved'])

const form = useForm({
  name:         props.professional.user.name || '',
  email:        props.professional.user.email || '',
  phone:        props.professional.phone          || '',
  category_id:  props.professional.category_id    || '',
  description:  props.professional.description    || '',
  photo:        null,
  document:     null,
})

function onPhotoChange(e) {
  form.photo = e.target.files[0]
}

function onDocumentChange(e) {
  form.document = e.target.files[0]
}

function submitForm() {
  form.post(route('professionals.update', props.professional.id), {
    forceFormData: true,
    onSuccess: () => emit('saved'),
  })
}

function deleteAccount() {
  if (confirm('Tem certeza que deseja excluir seu perfil?')) {
    router.delete(route('professionals.destroy', props.professional.id))
  }
}
</script>

<template>
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold text-blue-custom-800 mb-6">Editar Perfil do Prestador</h2>

    <!-- Nome e E-mail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
        <input
          type="text"
          v-model="form.name"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">E-mail</label>
        <input
          type="email"
          v-model="form.email"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
      </div>
    </div>

    <!-- Telefone -->
    <div class="mt-4">
      <label class="block text-sm font-medium text-gray-700">Telefone</label>
      <input
        type="text"
        v-model="form.phone"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
      />
    </div>

    <!-- Categoria -->
    <div class="mt-4">
      <label class="block text-sm font-medium text-gray-700">Categoria de Serviço</label>
      <select
        v-model="form.category_id"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
      >
        <option value="">Selecione a categoria</option>
        <option
          v-for="cat in categories"
          :key="cat.id"
          :value="cat.id"
        >{{ cat.name }}</option>
      </select>
    </div>

    <!-- Descrição -->
    <div class="mt-4">
      <label class="block text-sm font-medium text-gray-700">Descrição (Sobre você)</label>
      <textarea
        v-model="form.description"
        rows="4"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
      ></textarea>
    </div>

    <!-- Foto de Perfil -->
    <div class="mt-4">
      <label class="block text-sm font-medium text-gray-700">Foto de Perfil</label>
      <input
        type="file"
        accept="image/*"
        @change="onPhotoChange"
        class="mt-1 block w-full text-sm text-gray-600"
      />
    </div>

    <!-- Documento -->
    <div class="mt-4">
      <label class="block text-sm font-medium text-gray-700">Documento (PDF ou imagem)</label>
      <input
        type="file"
        accept="application/pdf,image/*"
        @change="onDocumentChange"
        class="mt-1 block w-full text-sm text-gray-600"
      />
    </div>

    <!-- Botões -->
    <div class="mt-6 flex items-center justify-between">
      <button
        @click="submitForm"
        :disabled="form.processing"
        class="bg-blue-custom-600 hover:bg-blue-custom-800 text-white font-semibold px-6 py-3 rounded"
      >
        {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
      </button>
      <button
        @click="deleteAccount"
        type="button"
        class="text-red-600 hover:text-red-800"
      >
        Excluir Perfil
      </button>
    </div>
  </div>
</template>
