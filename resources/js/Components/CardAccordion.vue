<template>
  <div
    class="bg-white rounded-lg shadow p-4 flex flex-col"
    :class="isOpen ? 'col-span-full' : ''"
  >
    <!-- header clicável -->
    <div
      class="flex items-center justify-between cursor-pointer select-none"
      @click="emitToggle(id)"
    >
      <div class="flex items-center gap-2">
        <!-- slot para ícone -->
        <slot name="icon">
          <!-- ícone default: caixa -->
          <i class="fas fa-box-open text-blue-custom-600 text-xl"></i>
        </slot>
        <h2 class="text-lg font-semibold text-blue-custom-800">
          <slot name="title" />
        </h2>
      </div>
      <div>
        <i
          :class="[
            'transition-transform duration-200',
            isOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'
          ]"
        ></i>
      </div>
    </div>

    <!-- resumo / subtítulo quando fechado -->
    <div
      v-if="!isOpen && $slots.summary"
      class="mt-2 text-gray-600 text-sm"
    >
      <slot name="summary" />
    </div>

    <!-- conteúdo full quando aberto -->
    <div v-show="isOpen" class="mt-4">
      <slot name="details" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { toRefs } from 'vue'

const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  },
  openId: {
    type: [String, Number, null],
    default: null
  }
})
const emit = defineEmits(['toggle'])

const { id: _id, openId } = toRefs(props)

const isOpen = computed(() => _id.value === openId.value)

function emitToggle(id) {
  emit('toggle', id)
}
</script>

<style scoped>
/* se quiser, pode adicionar uma pequena sombra extra quando abrir */
</style>
