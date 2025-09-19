<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AdminDashboardCards from '@/Layouts/AdminDashboardCards.vue'
import ClientDashboardCards from '@/Layouts/ClientDashboardCards.vue'
import ProfessionalDashboardCards from '@/Layouts/ProfessionalDashboardCards.vue'
import DashboardAppointmentsChart from '@/Layouts/Charts/DashboardAppointmentsChart.vue'
import DashboardAppointmentsCalendar from '@/Layouts/Charts/DashboardAppointmentsCalendar.vue'

// Dados da página
const page = usePage()
const user = page.props.auth.user
const establishment = page.props.establishment
const weeklyAppointments = page.props.weeklyAppointments
const appointmentsWeek = page.props.appointmentsWeek

// Papéis do usuário
const roles = user.roles || []

// Helpers

const isclient = roles.includes('client')
const isProfessional = roles.includes('professional')
const isAdmin = roles.includes('admin')
const isSuperMaster = roles.includes('master')
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <div class="py-4">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

        <h1 class="text-2xl font-semibold text-blue-custom-900 dark:text-white mb-4">Dashboard</h1>

        <!-- Alerta -->
        <div
          v-if="!establishment.address || !establishment.phone"
          class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md"
        >
          <p class="font-semibold">Estabelecimento incompleto:</p>
          <ul class="ml-4 list-disc">
            <li v-if="!establishment.address">Endereço não preenchido.</li>
            <li v-if="!establishment.phone">Telefone não cadastrado.</li>
          </ul>
        </div>

        <!-- Cards específicos por perfil -->
        <ClientDashboardCards v-if="isclient" />

        <template v-if="isProfessional">
          <ProfessionalDashboardCards />
          <DashboardAppointmentsCalendar :appointmentsWeek="appointmentsWeek" />
        </template>

        <template v-if="isAdmin || isSuperMaster">
          <AdminDashboardCards />
          <DashboardAppointmentsChart :data="weeklyAppointments" />
        </template>

      </div>
    </div>
  </AdminLayout>
</template>

