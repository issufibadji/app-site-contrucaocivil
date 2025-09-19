<template>
  <div>
    <PublicLayoutHeader page-title="Perfil" />
    <section class="bg-white py-8">
      <div class="container mx-auto px-6">
        <h1 class="text-3xl font-bold mb-4">{{ professional.user?.name }}</h1>
        <p class="mb-4">{{ professional.description }}</p>
        <h2 class="text-xl font-semibold mb-2">Serviços</h2>
        <ul class="list-disc ml-6 mb-6">
          <li v-for="service in professional.services" :key="service.id">
            {{ service.name }} - {{ service.price }} MZN
          </li>
        </ul>
        <div v-if="professional.reviews && professional.reviews.length">
          <h2 class="text-xl font-semibold mb-2">Avaliações (Média: {{ averageRating }})</h2>
          <ul class="space-y-2">
            <li v-for="rev in professional.reviews" :key="rev.id" class="border-b pb-2">
              <span class="font-semibold">{{ rev.rating }} ⭐</span> - {{ rev.comment }}
            </li>
          </ul>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import PublicLayoutHeader from '@/Layouts/PublicLayoutHeader.vue'
import { computed } from 'vue'

const props = defineProps({
  professional: Object
})

const averageRating = computed(() => {
  if (!props.professional.reviews || props.professional.reviews.length === 0) return 0
  const total = props.professional.reviews.reduce((sum, r) => sum + r.rating, 0)
  return (total / props.professional.reviews.length).toFixed(1)
})
</script>
