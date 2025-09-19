<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import NotificationsDropdown from '@/Components/NotificationsDropdown.vue'

const page = usePage()
const user = computed(() => page.props.auth.user)
const sidebarOpen = ref(false)
const sidebarCollapsed = ref(false)
const isDark = ref(false)
const dropdownOpen = ref(false)
const sideMenus = computed(() => page.props.sideMenus ?? [])
const roles = computed(() => page.props.auth.user.roles ?? [])
const permissions = computed(() => page.props.auth.user.permissions ?? [])
const appIcon = computed(() => page.props.app?.icon || '/images/logotipo3.png')
const appName = computed(() => page.props.app?.name || 'Tcham Services')

const filteredMenus = computed(() =>
  sideMenus.value.filter(m => {
    if (!m.active) return false
    if (m.acl && !permissions.value.includes(m.acl)) return false
    return [1, 2, 3].includes(m.level)
  })
)


onMounted(() => {
  isDark.value = localStorage.getItem('theme') === 'dark'
  updateHtmlClass()
  sidebarCollapsed.value = localStorage.getItem('sidebar-collapsed') === '1'
})

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value
}
function toggleSidebarCollapse() {
  sidebarCollapsed.value = !sidebarCollapsed.value
  localStorage.setItem('sidebar-collapsed', sidebarCollapsed.value ? '1' : '0')
}
function toggleDarkMode() {
  isDark.value = !isDark.value
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
  updateHtmlClass()
}
function updateHtmlClass() {
  const html = document.documentElement
  isDark.value ? html.classList.add('dark') : html.classList.remove('dark')
}
function logout() {
  router.post(route('logout'))
}

// ✅ Agrupando por level (grupos visuais)
const groupedMenus = computed(() => {
  // filtra e pega só os itens de topo
  const parents = filteredMenus.value
    .filter(m => m.parent_id === null)
    .sort((a,b) => a.order - b.order);

  const groups = {};

  parents.forEach(item => {
    const groupName =
      item.level === 1 ? 'Área Operacional'
      : item.level === 2 ? 'Gestão da Administração'
      : item.level === 3 ? 'Administração do Sistema'
      : 'Outros';

    if (!groups[groupName]) groups[groupName] = [];

    // pega os filhos, ordena e injeta no array
    const children = filteredMenus.value
      .filter(child => child.parent_id === item.id)
      .sort((a,b) => a.order - b.order)
      .map(child => ({
        ...child,
        style: item.style
      }));

    groups[groupName].push({ ...item, children });
  });

  return groups;
});



function defaultTextClass(groupName) {
  if (groupName === 'Área Operacional') return 'text-camel-300'
  if (groupName === 'Gestão da Administração') return 'text-vanilla-300'
  if (groupName === 'Administração do Sistema') return 'text-mint-300'
  return 'text-gray-400'
}
</script>

<template>
  <div class="flex min-h-screen bg-blue-custom-50 dark:bg-gray-800">
    <!-- Sidebar -->
    <aside :class="[
      'fixed z-40 lg:static transform transition-all duration-300 ease-in-out bg-blue-custom-extra text-white flex flex-col',
      sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      sidebarCollapsed ? 'w-20' : 'w-64'
    ]">
      <div :class="[
        'p-4 border-b border-blue-custom-extra flex items-center justify-center transition-all duration-300',
        sidebarCollapsed ? 'px-2' : 'px-4'
      ]">
        <img :src="appIcon" :alt="appName" :class="[sidebarCollapsed ? 'h-12' : 'h-32', 'w-auto transition-all duration-300']" />
      </div>
      <div v-if="!sidebarCollapsed" class="p-2">
        <div class="relative">
          <input type="text" class="w-full bg-blue-custom-extra text-white rounded-md pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Search...">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-blue-custom-500"></i>
          </div>
        </div>
      </div>
        <nav :class="['flex-1 space-y-2 overflow-y-auto', sidebarCollapsed ? 'px-1' : 'px-2']">
        <Link
          href="/dashboard"
          :class="[
            'flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-blue-custom-extra text-white hover:bg-blue-custom-700 transition-colors',
            sidebarCollapsed ? 'justify-center' : ''
          ]"
        >
            <i :class="['fas fa-home', sidebarCollapsed ? 'text-lg' : 'mr-3 w-4']"></i>
            <span v-if="!sidebarCollapsed" class="ml-2">Dashboard</span>
        </Link>

        <template v-for="(items, groupName) in groupedMenus" :key="groupName">
            <div class="mt-4 border-t border-blue-custom-extra pt-2">
            <p v-if="!sidebarCollapsed" :class="'text-xs px-4 uppercase tracking-widest mb-1 ' + defaultTextClass(groupName)">
                {{ groupName }}
            </p>

            <template v-for="item in items" :key="item.id">
                <!-- Menu sem filhos -->
                <Link
                v-if="!item.children.length"
                :href="`/${item.route}`"
                :class="[
                    'flex items-center px-4 py-2 text-sm hover:text-white hover:bg-blue-custom-700 rounded-lg transition-colors',
                    item?.style || defaultTextClass(groupName),
                    sidebarCollapsed ? 'justify-center' : ''
                ]"
                >
                <i :class="['fas', item.icon, sidebarCollapsed ? 'text-lg' : 'mr-3 w-4']"></i>
                <span v-if="!sidebarCollapsed" class="ml-2">{{ item.description }}</span>
                </Link>

                <!-- Menu com filhos (dropdown) -->
                <div v-else :class="sidebarCollapsed ? 'px-0' : 'pl-2'">
                <p
                    class="flex items-center px-4 py-2 text-sm font-semibold text-white"
                    :class="[
                        item?.style || defaultTextClass(groupName),
                        sidebarCollapsed ? 'justify-center' : ''
                    ]"
                >
                    <i :class="['fas', item.icon, sidebarCollapsed ? 'text-lg' : 'mr-3 w-4']"></i>
                    <span v-if="!sidebarCollapsed" class="ml-2">{{ item.description }}</span>
                </p>
                <div v-if="!sidebarCollapsed" class="ml-4">
                    <Link
                    v-for="child in item.children"
                    :key="child.id"
                    :href="`/${child.route}`"
                    :class="[
                        'flex items-center px-4 py-2 text-sm hover:text-white hover:bg-blue-custom-700 rounded-lg',
                        child?.style || item?.style || defaultTextClass(groupName)
                    ]"
                    >
                    <i :class="`fas ${child.icon} mr-3 w-4`"></i> {{ child.description }}
                    </Link>
                </div>
                </div>
            </template>
            </div>
        </template>
        </nav>

      <div class="p-4 border-t border-blue-custom-extra space-y-3">
        <button
          type="button"
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-custom-600 hover:bg-blue-custom-700 rounded-lg transition-colors"
          @click="toggleSidebarCollapse"
        >
          <i :class="['fas', sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left']"></i>
          <span v-if="!sidebarCollapsed">{{ sidebarCollapsed ? 'Expandir menu' : 'Recolher menu' }}</span>
        </button>
        <div class="flex items-center justify-center">
          <img class="h-8 w-8 rounded-full" :src="`https://ui-avatars.com/api/?name=${user.name}`" alt="Avatar">
          <div v-if="!sidebarCollapsed" class="ml-3 text-left">
            <p class="text-sm font-medium text-white">{{ user.name }}</p>
            <p class="text-xs text-blue-custom-400">View profile</p>
          </div>
        </div>
      </div>
    </aside>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <header class="flex items-center justify-between bg-white dark:bg-gray-900 px-4 h-16 border-b dark:border-gray-700 shadow-sm">
        <button class="lg:hidden text-blue-custom-600 dark:text-blue-custom-300" @click="toggleSidebar">
          <i class="fas fa-bars text-xl"></i>
        </button>
        <div class="flex items-center space-x-6 ml-auto relative">
          <NotificationsDropdown />
          <button @click="toggleDarkMode" class="text-blue-custom-600 dark:text-blue-custom-300 hover:text-yellow-400">
            <i :class="isDark ? 'fas fa-sun' : 'fas fa-moon'" class="text-lg"></i>
          </button>
          <div class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
              <img class="h-8 w-8 rounded-full" :src="`https://ui-avatars.com/api/?name=${user.name}`" alt="">
              <span class="hidden md:inline-block text-sm font-medium text-blue-custom-600 dark:text-blue-custom-200">{{ user.name }}</span>
              <i class="fas fa-chevron-down text-xs text-blue-custom-500"></i>
            </button>
            <div v-if="dropdownOpen" class="fixed inset-0 z-40" @click="dropdownOpen = false"></div>
            <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-blue-custom-extra border border-blue-custom-200 dark:border-blue-custom-700 rounded-md shadow-lg z-50">
              <Link href="/profile" class="block px-4 py-2 text-sm text-blue-custom-700 dark:text-blue-custom-200 hover:bg-blue-custom-100 dark:hover:bg-blue-custom-700">
                <i class="fas fa-user mr-2"></i> Meu Perfil
              </Link>
              <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-blue-custom-700 dark:text-blue-custom-200 hover:bg-blue-custom-100 dark:hover:bg-blue-custom-700">
                <i class="fas fa-sign-out-alt mr-2"></i> Sair
              </button>
            </div>
          </div>
        </div>
      </header>
      <!-- SLOT PARA PÁGINAS -->
      <main class="flex-1 p-6 bg-white dark:bg-gray-900">
        <slot />
      </main>
    </div>
  </div>
</template>

<style>
html.dark {
  background-color: #1f2937;
}
</style>
