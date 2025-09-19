<!-- resources/js/Pages/Perfil/Partials/SummaryCard.vue -->
<template>
  <Card icon="fas fa-key" title="Segurança">
    <div class="bg-blue-custom-100 p-4 rounded shadow">
      <h3 class="text-lg font-semibold mb-2">Autenticação em 2 Fatores (2FA)</h3>
      <p class="text-sm text-gray-700 mb-4">
        Configure ou desative a autenticação em dois fatores para sua conta.
      </p>
      <TwoFactorSetup
        :qr-code-url="qrCodeUrl"
        :secret-key="secretKey"
      />
    </div>
  </Card>
</template>

<script setup>
import Card from '@/Components/Card.vue'
import TwoFactorSetup from '../TwoFactorSetup.vue'

const props = defineProps({
  qrCodeUrl: String,
  secretKey: String,
})
</script>
