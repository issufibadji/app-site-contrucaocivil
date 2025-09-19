<!-- resources/js/Pages/Perfil/Partials/RequiredAddressCard.vue -->
<template>
  <CardAccordion
    id="addresses"
    :openId="openId"
    @toggle="toggle"
  >
    <template #icon>
      <i class="fas fa-home text-blue-custom-600 text-xl"></i>
    </template>

    <template #title>Endereços</template>

    <!-- resumo quando fechado -->
    <template #summary>
      <div class="flex justify-between items-center w-full">
        <div>
          <p class="font-medium">
            {{ addresses.length ? addresses.length + ' cadastrado(s)' : 'Nenhum endereço' }}
          </p>
        </div>
        <button
          class="text-sm text-blue-custom-600 hover:underline"
          @click.stop="openNew()"
        >+ Adicionar</button>
      </div>
    </template>

    <!-- detalhes quando aberto -->
    <template #details>
      <div class="space-y-4">
        <!-- lista de endereços -->
        <div
          v-for="(addr, idx) in addresses"
          :key="addr.id"
          class="border rounded p-4 space-y-2"
        >
          <p class="font-semibold">{{ formatAddr(addr) }}</p>
          <div class="flex gap-2">
            <button
              class="text-sm text-blue-custom-600 hover:underline"
              @click="editIndex = addr.id"
            >Editar</button>
            <button
              class="text-sm text-red-600 hover:underline"
              @click="remove(addr.id)"
            >Excluir</button>
          </div>

          <!-- formulário inline para edição -->
          <AddressUserForm
            v-if="editIndex === addr.id"
            mode="edit"
            :address="addr"
            @saved="onSaved"
            @cancel="editIndex = null"
          />
        </div>

        <!-- formulário para adicionar novo -->
        <AddressUserForm
          v-if="newMode"
          mode="create"
          @saved="onSaved"
          @cancel="newMode = false"
        />
      </div>
    </template>
  </CardAccordion>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import CardAccordion from '@/Components/CardAccordion.vue'
import AddressUserForm from './AddressUserForm.vue'

const page = usePage()
// array de endereços
const addresses = ref(page.props.auth.user.addresses || [])

const openId     = ref(null)
const editIndex  = ref(null)
const newMode    = ref(false)

function toggle(id) {
  openId.value = openId.value === id ? null : id
  // quando abrir o acordeão, resetar modos
  if (openId.value !== 'addresses') {
    editIndex.value = null
    newMode.value = false
  }
}

function openNew() {
  editIndex.value = null
  newMode.value = true
  openId.value = 'addresses'
}

// simulação de remoção; você pode chamar Inertia.delete(...)
function remove(id) {
  addresses.value = addresses.value.filter(a => a.id !== id)
}

// ao salvar (criar ou editar), atualiza a lista e fecha formulários
function onSaved(updated) {
  if (updated.id) {
    // se veio com id, é edição
    const idx = addresses.value.findIndex(a => a.id === updated.id)
    addresses.value.splice(idx, 1, updated)
  } else {
    // sem id, foi criação — suposição: backend retorna o novo id
    addresses.value.push(updated)
  }
  editIndex.value = null
  newMode.value    = false
}

// helper para exibir resumo
function formatAddr(a) {
  return `${a.street}, ${a.city}${a.uf ? ' - ' + a.uf : ''}`
}
</script>
