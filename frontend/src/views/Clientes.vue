<template>
    <div class="bg-white">
      <Navbar />
      <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
          <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Lista de clientes</h1>
          <div class="mt-6 flex gap-x-4 pb-8">
            <label for="clientes" class="sr-only">clientes</label>
            <input id="clientes" v-model="searchQuery" @keyup.enter="searchProducts" type="text"
              placeholder="Introduce el nombre del cliente..."
              class="min-w-0 flex-auto p-2 border border-primary-oscuro rounded" />
            <button @click="searchProducts"
              class="flex-none rounded-md bg-primary-azul px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa hover:text-primary-oscuro focus-visible:outline-primary-oscuro">
              Buscar
            </button>
            <button @click="addProduct"
              class="flex-none rounded-md bg-primary-violeta px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa hover:text-primary-oscuro focus-visible:outline-primary-oscuro">
              Añadir cliente
            </button>
          </div>
          <div v-if="hasSearched">
            <div v-if="filteredProducts.length">
              <div v-for="product in filteredProducts" :key="product.id"
                class="card mb-4 p-4 border border-gray-300 rounded">
                <h2 class="text-xl font-bold">{{ product.name }}</h2>
                <p>{{ product.description }}</p>
                <p class="text-gray-500">{{ product.price | currency }}</p>
              </div>
            </div>
            <div v-else>
              <p>No se encontraron clientes.</p>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </div>
  </template>
<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';

export default {
components: {
    Navbar,
    Footer
}
}
</script>

<style>

</style>