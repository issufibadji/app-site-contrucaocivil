<!-- resources/js/Pages/Perfil/Partials/RequiredDataCard.vue -->
<template>
  <CardAccordion
    id="data"
    :openId="openId"
    @toggle="toggle"
  >
    <template #icon>
      <i class="fas fa-user text-blue-custom-600 text-xl"></i>
    </template>

    <template #title>Dados da Conta</template>

    <!-- resumo quando fechado -->
    <template #summary>
      <div class="flex justify-between items-center">
        <div>
          <p class="font-medium">{{ user.name }}</p>
          <p class="text-sm text-gray-600">{{ user.email }}</p>
        </div>
        <button
          class="text-sm text-blue-custom-600 hover:underline"
          @click.stop="toggle('data')"
        >Editar</button>
      </div>
    </template>

    <!-- formulário quando aberto -->
    <template #details>
      <ProfileInformationForm :user="user" @saved="toggle(null)" />
    </template>
  </CardAccordion>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import CardAccordion from '@/Components/CardAccordion.vue'
import ProfileInformationForm from './ProfileInformationForm.vue'

const page = usePage()
const user = page.props.auth.user

// controla qual card está aberto
const openId = ref(null)
function toggle(id) {
  openId.value = openId.value === id ? null : id
}
</script>
