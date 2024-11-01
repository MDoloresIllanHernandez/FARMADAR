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
            <a v-for="item in navigation" :key="item.name" :href="item.href" class="rounded-lg p-2 text-xl font-semibold leading-6 text-primary-oscuro hover:bg-gray-50">{{ item.name }}</a>
          </div>
          <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <a :href="cerrarSesion.href" class="text-sm font-semibold leading-6 text-gray-900">Cerrar Sesión </a>
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
                  <a v-for="item in navigation" :key="item.name" :href="item.href" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-primary-oscuro hover:bg-gray-50">{{ item.name }}</a>
                </div>
                <div class="space-y-2 py-6">
                  <a :href="cerrarSesion.href" class="text-sm font-semibold leading-6 text-gray-900">Cerrar Sesión </a>
                </div>
               
              </div>
            </div>
          </DialogPanel>
        </Dialog>
      </header>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { Dialog, DialogPanel } from '@headlessui/vue'
  import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
  
  // Navigation links
  const navigation = [
    { name: 'Buscador FARMADAR', href: '/buscador-productos' },
    { name: 'Productos', href: '/productos' },
    { name: 'Farmacias', href: '/farmacias' },
    { name: 'Clientes', href: '/clientes' },
    { name: 'Reservas', href: '/reservas' }
  ]

  const cerrarSesion = { name: 'Cerrar Sesión', href: '/cerrar-sesion' }

  const buscadorProductos = { name: 'Buscador FARMADAR', href: '/buscador-productos' }

  const mobileMenuOpen = ref(false)
  
  </script>


