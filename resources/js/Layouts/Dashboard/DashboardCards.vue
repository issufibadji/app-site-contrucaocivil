<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import CardModal from '@/Components/CardModal.vue'

import DashboardAgenderCards from './DashboardAgenderCards.vue'
import DashboardRequestsReceivedCards from './DashboardRequestsReceivedCards.vue'
import DashboardServiceHistoryCard from './DashboardServiceHistoryCard.vue'
import DashboardReviewsReceivedCards from './DashboardReviewsReceivedCards.vue'
import DashboardMessagesCads from './DashboardMessagesCads.vue'
import DashboardResumeCads from './DashboardResumeCads.vue'
import DashboardConfigUserCards from './DashboardConfigUserCards.vue'
import DashboardConfigCards from './DashboardConfigCards.vue'
import DashboardFaturamentoCards from './DashboardFaturamentoCards.vue'
import DashboardProvidersCard from './DashboardProvidersCard.vue'
import DashboardModerationCard from './DashboardModerationCard.vue'
import DashboardLogsCard from './DashboardLogsCard.vue'

const page = usePage()
const user = page.props.auth.user
const roles = user.roles || []

const cardsConfig = {
  client: [
    { id: 1, icon: 'fas fa-calendar text-2xl text-blue-custom-600', title: 'Agendar Serviço', component: DashboardAgenderCards },
    { id: 2, icon: 'fas fa-history text-2xl text-blue-custom-600', title: 'Histórico de Serviços', component: DashboardServiceHistoryCard },
    { id: 3, icon: 'fas fa-star text-2xl text-blue-custom-600', title: 'Avaliações Recebidas', component: DashboardReviewsReceivedCards },
    { id: 4, icon: 'fas fa-comment-dots text-2xl text-blue-custom-600', title: 'Mensagens / Suporte', component: DashboardMessagesCads },
  ],
  professional: [
    { id: 5, icon: 'fas fa-inbox text-2xl text-blue-custom-600', title: 'Solicitações Recebidas', component: DashboardRequestsReceivedCards },
    { id: 6, icon: 'fas fa-history text-2xl text-blue-custom-600', title: 'Histórico de Serviços', component: DashboardServiceHistoryCard },
    { id: 7, icon: 'fas fa-star text-2xl text-blue-custom-600', title: 'Avaliações Recebidas', component: DashboardReviewsReceivedCards },
    { id: 8, icon: 'fas fa-comment-dots text-2xl text-blue-custom-600', title: 'Mensagens / Suporte', component: DashboardMessagesCads },
  ],
  admin: [
    { id: 9,  icon: 'fas fa-chart-pie text-2xl text-blue-custom-600', title: 'Resumo Geral (KPIs)', component: DashboardResumeCads },
    { id: 10, icon: 'fas fa-user-cog text-2xl text-blue-custom-600', title: 'Gestão de Usuários', component: DashboardConfigUserCards },
    { id: 11, icon: 'fas fa-users text-2xl text-blue-custom-600', title: 'Gestão de Prestadores', component: DashboardProvidersCard },
    { id: 12, icon: 'fas fa-shield-alt text-2xl text-blue-custom-600', title: 'Moderação de Conteúdo', component: DashboardModerationCard },
    { id: 13, icon: 'fas fa-cogs text-2xl text-blue-custom-600', title: 'Configurações', component: DashboardConfigCards },
  ],
}
cardsConfig['master'] = [...cardsConfig.admin, { id: 14, icon: 'fas fa-file-alt text-2xl text-blue-custom-600', title: 'Logs de Acesso & Auditoria', component: DashboardLogsCard }]

const allowedCards = computed(() => {
  const ids = new Set()
  let result = []
  roles.forEach(r => {
    const cards = cardsConfig[r] || []
    cards.forEach(c => {
      if (!ids.has(c.id)) {
        ids.add(c.id)
        result.push(c)
      }
    })
  })
  return result
})

const openCard = ref(null)
function toggleCard(id) {
  openCard.value = openCard.value === id ? null : id
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4 text-blue-custom-800">
      👤 Bem-vindo, {{ user.name }}!
    </h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <CardModal
        v-for="card in allowedCards"
        :key="card.id"
        :id="card.id"
        :openId="openCard"
        @toggle="toggleCard"
      >
        <template #icon>
          <i :class="card.icon" />
        </template>
        <template #title>
          {{ card.title }}
        </template>
        <template #details>
          <component :is="card.component" :user="user" />
        </template>
      </CardModal>
    </div>
  </div>
</template>
