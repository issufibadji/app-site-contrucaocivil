<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm, Link, Head } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import { computed } from 'vue'

const props = defineProps({
  service:       Object,
  mode:          String,
  professionals: Object,
  categories:    Array,
})

const form = useForm({
  category_id:   props.service?.category_id  || '',
  name:          props.service?.name         || '',
  duration_min:  props.service?.duration_min || '',
  price:         props.service?.price        || '',
  image:         null,
  descrition:    props.service?.descrition   || '',
  professionals: props.service?.professionals?.map(p => p.id) || [],
})

// Computed que retorna só os serviços da categoria selecionada
const filteredServices = computed(() => {
  const cat = props.categories.find(c => c.id === form.category_id)
  return cat ? cat.services : []
})

function submit() {
  if (props.mode === 'edit') {
    form.put(route('services.update', props.service.uuid))
  } else {
    form.post(route('services.store'))
  }
}
</script>

<template>
  <AdminLayout>
    <Head :title="props.mode === 'edit' ? 'Editar Serviço' : 'Cadastrar Serviço'" />

    <div class="max-w-3xl mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">
        {{ props.mode === 'edit' ? 'Editar Serviço' : 'Cadastrar Serviço' }}
      </h1>

      <form @submit.prevent="submit" class="space-y-6">

        <!-- 1) Categoria -->
        <div>
          <InputLabel for="category_id" value="Categoria" />
          <select
            id="category_id"
            v-model="form.category_id"
            class="input"
          >
            <option disabled value="">Selecione</option>
            <option
              v-for="cat in props.categories"
              :key="cat.id"
              :value="cat.id"
            >
              {{ cat.name }}
            </option>
          </select>
          <InputError :message="form.errors.category_id" class="mt-1" />
        </div>

        <!-- 2) Seleção de serviço dentro da categoria -->
        <div v-if="filteredServices.length">
        <InputLabel for="name" value="Escolha um serviço existente" />
        <select
            id="name"
            v-model="form.name"
            class="input"
        >
            <option disabled value="">Selecione...</option>
            <option v-for="srv in filteredServices" :key="srv.id" :value="srv.name">
            {{ srv.name }}
            </option>
        </select>
        <InputError :message="form.errors.name" class="mt-1" />
        </div>

        <!-- 3) Campos básicos: Nome / Duração / Preço -->
        <div class="grid grid-cols-4 gap-4">
          <div class="col-span-2">
            <InputLabel for="name" value="Nome" />
            <input id="name" v-model="form.name" type="text" class="input" />
            <InputError :message="form.errors.name" class="mt-1" />
          </div>

          <div>
            <InputLabel for="duration_min" value="Duração (min)" />
            <input
              id="duration_min"
              v-model="form.duration_min"
              type="number"
              class="input"
            />
            <InputError :message="form.errors.duration_min" class="mt-1" />
          </div>

          <div>
            <InputLabel for="price" value="Valor (R$)" />
            <input
              id="price"
              v-model="form.price"
              type="number"
              step="0.01"
              class="input"
            />
            <InputError :message="form.errors.price" class="mt-1" />
          </div>
        </div>

        <!-- 4) Imagem -->
        <div>
          <InputLabel for="image" value="Imagem" />
          <input
            id="image"
            type="file"
            @change="form.image = $event.target.files[0]"
            class="input"
          />
          <InputError :message="form.errors.image" class="mt-1" />

          <!-- preview no modo edit -->
          <img
            v-if="props.mode === 'edit' && props.service?.image"
            :src="`/storage/${props.service.image}`"
            class="mt-2 h-24 object-cover rounded"
          />
        </div>

        <!-- 5) Descrição -->
        <div>
          <InputLabel for="descrition" value="Descrição" />
          <textarea
            id="descrition"
            v-model="form.descrition"
            rows="4"
            class="input"
          ></textarea>
          <InputError :message="form.errors.descrition" class="mt-1" />
        </div>

        <!-- 6) Profissionais -->
        <div>
          <InputLabel for="professionals" value="Profissionais" />
          <select
            id="professionals"
            v-model="form.professionals"
            multiple
            class="input"
          >
            <option
              v-for="(name, id) in props.professionals"
              :key="id"
              :value="id"
            >
              {{ name }}
            </option>
          </select>
          <InputError :message="form.errors.professionals" class="mt-1" />
        </div>

        <!-- 7) Botões -->
        <div class="flex justify-end gap-2">
          <Link
            :href="route('services.index')"
            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-custom-800 text-white rounded hover:bg-blue-custom-600"
            :disabled="form.processing"
          >
            {{ props.mode === 'edit' ? 'Atualizar' : 'Salvar' }}
          </button>
        </div>

      </form>
    </div>
  </AdminLayout>
</template>

<style scoped>
.input {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm
         focus:border-blue-custom-500 focus:ring-blue-custom-500;
}
</style>
