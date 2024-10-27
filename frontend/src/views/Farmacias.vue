<template>
    <div class="bg-white">
      <Navbar />
      <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
          <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Lista de farmacias</h1>
          <div class="mt-6 flex gap-x-4 pb-8">
            <label for="farmacias" class="sr-only">farmacias</label>
            <input id="farmacias" v-model="searchQuery" @keyup.enter="searchProducts" type="text"
              placeholder="Introduce el nombre de la farmacia..."
              class="min-w-0 flex-auto p-2 border border-primary-oscuro rounded" />
            <button @click="searchFarmacia"
              class="flex-none rounded-md bg-primary-azul px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa hover:text-primary-oscuro focus-visible:outline-primary-oscuro">
              Buscar
            </button>
            <button @click="addFarmacia"
              class="flex-none rounded-md bg-primary-violeta px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa hover:text-primary-oscuro focus-visible:outline-primary-oscuro">
              Añadir farmacia
            </button>
          </div>
          <div v-if="hasSearched">
            <div v-if="farmacias.length">
              <div v-for="farmacia in farmacias" :key="farmacia.cif"
                class="card mb-4 p-4 border border-gray-300 rounded">
                <h2 class="text-xl font-bold">{{ farmacia.nombre }}</h2>
                <p>{{ farmacia.direccion }}</p>
                <p>{{ farmacia.telefono }}</p>
              </div>
            </div>
            <div v-else>
              <p>No se encontraron farmacias.</p>
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
import apiClient from '../scripts/axios.js';

export default {
  data() {
    return {
      searchQuery: '',
      farmacias: [],
      hasSearched: false
    };
  },
  methods: {
    async searchFarmacia() {
      const response = await apiClient.get('/farmacia');
        if (response.data.result == 'ok' && response.data.farmacias) {
          this.farmacias = response.data.farmacias.filter(farmacia => farmacia.nombre.toLowerCase().includes(this.searchQuery.toLowerCase()));
        }
        this.hasSearched = true;
    }
  },
  components: {
    Navbar,
    Footer
  }
}

</script>

<style>

</style>