<template>
  <AdminLayout>
    <main class="p-6">
      <h1 class="text-2xl font-bold mb-4 text-blue-custom-800">Meu Perfil</h1>
      <ProfileHeader class="mb-8" />
      <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Dados Obrigatórios -->
          <CardAccordion
            :id="1"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-user text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Dados Obrigatórios</template>
            <template #summary>Dados da sua conta</template>
            <template #details>
              <RequiredDataCard :user="user" />
            </template>
          </CardAccordion>

          <!-- Documentos / Informações Pessoais -->
          <CardAccordion
            :id="2"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-id-card text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Informações Pessoais</template>
            <template #summary>
              Informações do seu documento de identidade
            </template>
            <template #details>
              <DocumentCard
                :professional="professional"
                :categories="categories"
                @saved="toggleCard(null)"
              />
            </template>
          </CardAccordion>

          <!-- Endereços -->
          <CardAccordion
            :id="3"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-home text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Endereços</template>
            <template #summary>
              {{ primaryAddress ? formatAddress(primaryAddress) : 'Adicionar endereço' }}
            </template>
            <template #details>
              <AddressesForm :address="primaryAddress" />
            </template>
          </CardAccordion>

          <!-- 2FA / Resumo -->
          <CardAccordion
            :id="4"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-user-lock text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Segurança</template>
            <template #summary>Configure autenticação em 2 fatores</template>
            <template #details>
              <SummaryCard :qr-code-url="qrCodeUrl" :secret-key="secretKey" />
            </template>
          </CardAccordion>

          <!-- Cartões -->
          <CardAccordion
            :id="5"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-credit-card text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Cartões</template>
            <template #summary>Gerencie seus cartões salvos</template>
            <template #details>
              <PaymentCard />
            </template>
          </CardAccordion>
        <!-- Privacidade -->
          <CardAccordion
            :id="5"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-user-shield text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Privacidade</template>
            <template #summary>Gerencie seus cartões salvos</template>
            <template #details>
              <PrivacyCard />
            </template>
          </CardAccordion>
          <!-- Comunicações -->
          <CardAccordion
            :id="6"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-comment-dots text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Comunicações</template>
            <template #summary>
              Escolha que tipo de informação você quer receber.
            </template>
            <template #details>
              <CommunicationsCard />
            </template>
          </CardAccordion>
           <!--  Historico de serviçao Realizados/Solicitados-->
          <CardAccordion
            :id="6"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-briefcase text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>Historico de Serviços</template>
            <template #summary>
              Historicos de Serviços Realizados/Solicitados..
            </template>
            <template #details>
              <ServiceHistoryCard />
            </template>
          </CardAccordion>
          <!--  Historico de serviçao Realizados/Solicitados-->
          <CardAccordion
            :id="6"
            :openId="openCard"
            @toggle="toggleCard"
          >
            <template #icon>
              <i class="fas fa-layer-group text-2xl text-blue-custom-600"></i>
            </template>
            <template #title>plano</template>
            <template #summary>
            Faça upgrade do seu plano
            </template>
            <template #details>
              <PlanCard />
            </template>
          </CardAccordion>
        </div>
      </div>
    </main>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CardAccordion from '@/Components/CardAccordion.vue'
import RequiredDataCard from './Partials/RequiredDataCard.vue'
import DocumentCard from './Partials/DocumentCard.vue'
import AddressesForm from './Partials/AddressesForm.vue'
import SummaryCard from './Partials/SummaryCard.vue'
import PaymentCard from './Partials/PaymentCard.vue'
import CommunicationsCard from './Partials/CommunicationsCard.vue'
import PrivacyCard from './Partials/PrivacyCard.vue'
import ProfileHeader from './Partials/ProfileHeader.vue'
import ServiceHistoryCard from './Partials/ServiceHistoryCard.vue'
import PlanCard from './Partials/PlanCard.vue'

// Props vindas do controller Inertia
const props = defineProps({
  mustVerifyEmail: Boolean,
  status: String,
  qrCodeUrl: String,
  secretKey: String,
  professional: Object,
  categories:   Array,
  addresses:   Array,
})

// usuário e endereço principal
const page = usePage()
const user = page.props.auth.user
const primaryAddress = computed(() => props.addresses?.[0] || null)

// controle de card aberto
const openCard = ref(null)
function toggleCard(id) {
  openCard.value = openCard.value === id ? null : id
}

// helper pra formatar o endereço no summary
function formatAddress(addr) {
  return `${addr.street}, ${addr.city}`
}
</script>
