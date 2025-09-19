<template>
  <!-- Head da página com título e favicon dinâmicos -->
  <Head :title="computedTitle">
    <link rel="icon" :href="appIcon" />
  </Head>

  <!-- HEADER FIXO COM ELEVAÇÃO E STICKY -->
  <header
    v-bind="$attrs"
    class="bg-blue-custom-extra text-white shadow-lg sticky top-0 z-50"
  >
    <div class="container mx-auto flex items-center justify-between py-4 px-6">
      <!-- Logo / Home -->
      <Link href="/" class="text-2xl font-bold no-underline">
        <div class="border-b border-blue-custom-800 flex items-center justify-between">
          <img :src="appIcon" :alt="appName" class="h-10 w-auto" />
          <span class="font-bold">{{ appName }}</span>
        </div>
      </Link>

      <!-- Navegação -->
      <nav class="space-x-6">
        <Link
          href="/welcome"
          class="no-underline hover:no-underline focus:no-underline active:no-underline visited:no-underline"
        >
          Login
        </Link>
        <Link
          href="/welcome"
          class="no-underline hover:underline visited:no-underline focus:no-underline active:no-underline"
        >
          Cadastro
        </Link>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  /**
   * Título da página exibido no <head>
   */
  pageTitle: String
})

const page = usePage()
const appName = computed(() => page.props.app?.name || 'Justiça Federal')
const appIcon = computed(() => page.props.app?.icon || '/images/logo-jf.png')
const computedTitle = computed(() =>
  props.pageTitle ? `${props.pageTitle} - ${appName.value}` : appName.value
)
</script>

<style scoped>

</style>
