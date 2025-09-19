<template>
  <AdminLayout>
    <main class="p-6 space-y-8">
      <div>
        <h1 class="text-2xl font-bold mb-2 text-blue-custom-800">Meu Perfil</h1>
        <p class="text-sm text-gray-500">Atualize os dados da sua conta e gerencie a autenticação em duas etapas.</p>
      </div>

      <ProfileHeader />

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <CardAccordion :id="1" :openId="openCard" @toggle="toggleCard">
          <template #icon>
            <i class="fas fa-user text-2xl text-blue-custom-600"></i>
          </template>
          <template #title>Dados da conta</template>
          <template #summary>
            <div>
              <p class="text-sm font-medium text-gray-700">{{ user.name }}</p>
              <p class="text-xs text-gray-500">{{ user.email }}</p>
            </div>
          </template>
          <template #details>
            <ProfileInformationForm />
          </template>
        </CardAccordion>

        <CardAccordion :id="2" :openId="openCard" @toggle="toggleCard">
          <template #icon>
            <i class="fas fa-key text-2xl text-blue-custom-600"></i>
          </template>
          <template #title>Segurança</template>
          <template #summary>Configure a autenticação em duas etapas</template>
          <template #details>
            <SummaryCard :qr-code-url="qrCodeUrl" :secret-key="secretKey" />
          </template>
        </CardAccordion>

        <CardAccordion :id="3" :openId="openCard" @toggle="toggleCard">
          <template #icon>
            <i class="fas fa-shield-halved text-2xl text-blue-custom-600"></i>
          </template>
          <template #title>Privacidade</template>
          <template #summary>Preferências de comunicação e proteção de dados</template>
          <template #details>
            <PrivacyCard />
            <CommunicationsCard class="mt-4" />
          </template>
        </CardAccordion>
      </div>
    </main>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CardAccordion from '@/Components/CardAccordion.vue'
import { usePage } from '@inertiajs/vue3'
import ProfileInformationForm from './Partials/ProfileInformationForm.vue'
import SummaryCard from './Partials/SummaryCard.vue'
import PrivacyCard from './Partials/PrivacyCard.vue'
import CommunicationsCard from './Partials/CommunicationsCard.vue'
import ProfileHeader from './Partials/ProfileHeader.vue'

const props = defineProps({
  qrCodeUrl: String,
  secretKey: String,
})

const page = usePage()
const user = page.props.auth.user

const openCard = ref(null)
function toggleCard(id) {
  openCard.value = openCard.value === id ? null : id
}
</script>
