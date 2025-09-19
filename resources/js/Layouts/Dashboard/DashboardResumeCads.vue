<template>
  <Card icon="fas fa-layer-group" title="Agendar Serviço">
     <!-- Card: Resumo -->
    <div class="bg-white dark:bg-blue-custom-800 rounded-lg shadow p-4 flex items-start gap-4">
      <i class="fas fa-chart-pie text-2xl text-blue-custom-500 mt-1"></i>
      <div>
        <h2 class="text-lg font-semibold text-blue-custom-800 dark:text-white">Resumo</h2>
        <p class="text-blue-custom-600 dark:text-blue-custom-200">Agendamentos: {{ establishment?.appointments_count }}</p>
        <p class="text-blue-custom-600 dark:text-blue-custom-200">Prestadores: {{ establishment?.professionals_count }}</p>
        <p class="text-blue-custom-600 dark:text-blue-custom-200">Clientes: {{ establishment?.clients_count }}</p>
      </div>
    </div>
  </Card>
</template>

<script setup>
import Card from '@/Components/Card.vue'
</script>
