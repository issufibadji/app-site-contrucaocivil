<template>
    <PublicLayoutHeader
    page-title="Home"
    @search="search"
    v-bind="filterBindings"
  />
    <img v-if="appBanner" :src="appBanner" alt="Banner" class="w-full" />

    <Welcome/>

    <!-- FOOTER -->
    <footer class="bg-blue-custom-extra text-white text-center py-4">
      © 2025 {{ appName }}
    </footer>
</template>

<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import PublicLayoutHeader from '@/Layouts/PublicLayoutHeader.vue'
import Welcome from './Welcome.vue'

const props = defineProps({
  services: {
    type: Array,
    default: () => [],
  },
})


const page = usePage()
const appBanner = computed(() => page.props.app?.banner || null)
const appName = computed(() => page.props.app?.name || 'TRF2-Tribunal Regional Federal da 2.ª Região')

/// Inertia form para filtros avançados
const form = useForm({
  query: '',
  location: '',
  category: '',
  price: '',
  rating: '',
})

// repassa apenas os campos que o Header usa
const filterBindings = {
  'model-value': form.data,
  'onUpdate:model-value': (val) => {
    form.query    = val.query
    form.location = val.location
  },
}

// função de busca
function search() {
  form.get(route('public.search'), {
    preserveState: true,
    replace: true,
  })
}
</script>

<style scoped>
/* ajustes finos, se quiser mudar sombras ou espaçamentos extras */
</style>
