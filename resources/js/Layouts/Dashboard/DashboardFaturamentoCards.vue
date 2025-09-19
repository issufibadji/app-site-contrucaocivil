<script setup>
import Card from '@/Components/Card.vue'
defineProps({
  establishment: Object,
})
</script>
<template>
  <Card icon="fas fa-layer-group" title="Agendar Serviço">
    <!-- Aqui pode vir uma lista de cartões ou link para gerenciar -->
      <!-- Card: Faturamento -->
   <div class="bg-white dark:bg-blue-custom-800 rounded-lg shadow p-4 flex items-start gap-4">
      <i class="fas fa-dollar-sign text-3xl text-blue-600 mt-1"></i>
      <div>
        <h2 class="text-lg font-semibold text-blue-custom-800 dark:text-white">Faturamento</h2>
        <p
          :class="{
            'text-blue-600 dark:text-blue-400': establishment?.revenue >= 1000,
            'text-yellow-500 dark:text-yellow-400': establishment?.revenue > 0 && establishment?.revenue < 1000,
            'text-red-500 dark:text-red-400': establishment?.revenue <= 0
          }"
          class="text-2xl font-bold"
        >
          R$ {{ establishment?.revenue?.toFixed(2) || '0.00' }}
        </p>
      </div>
    </div>
  </Card>
</template>
