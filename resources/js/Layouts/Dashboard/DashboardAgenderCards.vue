<script setup>
import Card from '@/Components/Card.vue';
defineProps({
  service: Object,
})
</script>
<template>
  <Card icon="fas fa-calendar" title="Agendar Serviço">
    <!-- Card: Agendar seviços -->
      <div>
        <p :class="service?.name ? 'text-blue-custom-600' : 'text-red-500 dark:text-red-400'">📅
          Tipos: {{ service?.name || 'Nome não cadastrado' }}
        </p>
        <p class="text-sm text-blue-custom-500">Ativos: {{ service?.cnpj || '---' }}</p>
          <p class="text-sm text-blue-custom-500">Inativos: {{ service?.cnpj || '---' }}</p>
        <p class="text-sm text-blue-custom-500">Total: {{ service?.cnpj || '---' }}</p>

      </div>

  </Card>
</template>

