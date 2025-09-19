<!-- resources/js/Components/CardModal.vue -->
<template>
  <div>
    <!-- 1) Card resumido clicável -->
    <div
      class="bg-white rounded-lg shadow p-4 flex items-center gap-3 cursor-pointer hover:shadow-lg transition"
      @click="toggle"
    >
      <slot name="icon"/>
      <h2 class="text-lg font-semibold text-blue-custom-800">
        <slot name="title"/>
      </h2>
    </div>

    <!-- 2) Modal overlay -->
    <teleport to="body">
      <transition name="fade">
        <div
          v-if="isOpen"
          class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
          @click.self="toggle"
        >
          <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full p-6 relative">
            <!-- Close button -->
            <button
              class="absolute top-3 right-3 text-gray-500 hover:text-gray-800"
              @click="toggle"
              aria-label="Fechar"
            >✕</button>

            <!-- Header -->
            <div class="flex items-center gap-2 mb-4">
              <slot name="icon"/>
              <h2 class="text-xl font-bold text-blue-custom-800">
                <slot name="title"/>
              </h2>
            </div>

            <!-- Detalhes da modal -->
            <div class="space-y-4">
              <slot name="details"/>
            </div>
          </div>
        </div>
      </transition>
    </teleport>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { toRefs } from 'vue'

const props = defineProps({
  id:        { type: [String, Number], required: true },
  openId:    { type: [String, Number, null], default: null }
})
const emit = defineEmits(['toggle'])

const { id, openId } = toRefs(props)
const isOpen = computed(() => id.value === openId.value)

function toggle() {
  emit('toggle', isOpen.value ? null : id.value)
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
