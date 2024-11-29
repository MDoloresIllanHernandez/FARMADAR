<template>
      <header class="absolute inset-x-0 top-0 z-50 bg-gray-100">
        <!--Versión de escritorio-->
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
          <div class="flex lg:flex-1">
            <a :href="buscadorProductos.href" class="-m-1.5 p-1.5">
              <span class="sr-only">FARMADAR</span>
              <img class="h-8 w-auto" src="/icono.png" alt="Icono FARMADAR" />
            </a>
          </div>
          <div class="flex lg:hidden">
            <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-primary-oscuro" @click="mobileMenuOpen = true">
              <span class="sr-only">Abrir menu</span>
              <Bars3Icon class="h-6 w-6" aria-hidden="true" />
            </button>
          </div>
          <div class="hidden lg:flex lg:gap-x-12">
            <span v-for="item in navigation" >
              <a v-if="item.name!='Usuarios' || (item.name=='Usuarios' && (roleUsuario!='usu' && roleUsuario!='Usuario'))" :key="item.name" :href="item.href" class="boton-navbar">{{ item.name }}</a>
            </span>
           
          </div>
          <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <!-- Ícono y Nombre del Usuario -->
            <div class="flex items-center mr-4">
              <img
                src="../assets/logo_user.png"
                alt="Icono Usuario"
                class="h-6 w-6 rounded-full mr-2"
              />
              <span class="text-sm font-semibold text-gray-900">
                <small>{{ usuarioNombre }}</small> 
              </span>
            </div>
            <!-- Cerrar Sesión -->
            <a :href="cerrarSesion.href" class="text-sm font-semibold leading-6 text-gray-900">
              <img
                src="../assets/logo_cerrarSesion.png"
                alt="Icono Cerrar Sesión"
                class="h-6 w-6 rounded-full mr-2"
                title="Cerrar Sesión"
              /> </a>
          </div>
        </nav>

        <!--Versión móvil-->
        <Dialog class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
          <div class="fixed inset-0 z-50" />
          <DialogPanel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-primary-oscuro/10">
            <div class="flex items-center justify-between">
              <a :href="buscadorProductos.href" class="-m-1.5 p-1.5">
                <span class="sr-only">FARMADAR</span>
                <img class="h-8 w-auto" src="../../public/icono.png" alt="Icono" />
              </a>
              <button type="button" class="-m-2.5 rounded-md p-2.5 text-primary-oscuro" @click="mobileMenuOpen = false">
                <span class="sr-only">Cerrar menu</span>
                <XMarkIcon class="h-6 w-6" aria-hidden="true" />
              </button>
            </div>
            <div class="mt-6 flow-root">
              <div class="-my-6 divide-y divide-gray-500/10">
                <div class="space-y-2 py-6">
                  <span v-for="item in navigation" >
                     <a v-if="item.name!='Usuarios' || (item.name=='Usuarios' && (roleUsuario!='usu' && roleUsuario!='Usuario'))" :key="item.name" :href="item.href" class="boton-navbar-movil">{{ item.name }}</a>
                  </span>
                 
                </div>
                <div class="space-y-2 py-6">
                  <!-- Ícono y Nombre del Usuario en Móvil -->
                  <div class="flex items-center space-y-2 py-6">
                    <img
                      src="../assets/logo_user.png"
                      alt="Icono Usuario"
                      class="h-6 w-6 rounded-full mr-2"
                    />
                    <span class="text-sm font-semibold text-gray-900">
                      <small>{{ usuarioNombre }}</small> 
                    </span>
                  </div>
                  <!-- Cerrar Sesión en Móvil -->
                  <a :href="cerrarSesion.href" class="flex text-sm font-semibold leading-6 text-gray-900">
                    <img
                      src="../assets/logo_cerrarSesion.png"
                      alt="Icono Cerrar Sesión"
                      class="h-6 w-6 rounded-full mr-2"
                    /> Cerrar Sesion</a>
                </div>
              </div>
            </div>
          </DialogPanel>
        </Dialog>
      </header>
  </template>
  
  <script setup>
  import { ref,onMounted } from 'vue'
  import { Dialog, DialogPanel } from '@headlessui/vue'
  import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
  import apiClient from '../scripts/axios.js';
  // Navigation links
  const navigation = [
    { name: 'Buscador FARMADAR', href: '/buscador-productos' },
    { name: 'Reservas', href: '/reservas' },
    { name: 'Productos', href: '/productos' },
    { name: 'Farmacias', href: '/farmacias' },
    { name: 'Usuarios', href: '/usuarios' },
  ]

  const cerrarSesion = { href: '/cerrar-sesion' }

  const buscadorProductos = { href: '/buscador-productos' }

  const mobileMenuOpen = ref(false)
  
  const roleUsuario = ref(sessionStorage.getItem('role')) || 'usu';
  let usuarioNombre = ref('Usuario');
 // Obtener el ID del usuario almacenado en sessionStorage
const userId = sessionStorage.getItem('id');
onMounted(async () => {
  if (userId) {
    try {
      const idFarm = ref(sessionStorage.getItem('id_farm'));
      const id = ref(sessionStorage.getItem('id'));
      const response = await apiClient.get('/usuario', {
          params: { role:roleUsuario.value, id_farm: idFarm.value },
        });
      if (response.data.result == 'ok' && response.data.usuarios) {
        const data = response.data.usuarios;
        usuarioNombre.value = data.filter(item=>item.id==id.value)[0]?.nombre || 'Usuario'; 
      }else{
        throw new Error('Error al obtener los datos del usuario');
      }
    
    } catch (error) {
      console.error('Error al cargar el usuario:', error);
      usuarioNombre.value = 'Usuario';
    }
  }
})
  </script>


