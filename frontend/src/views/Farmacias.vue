<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Lista de farmacias</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="farmacias" class="sr-only">farmacias</label>
          <input id="farmacias" v-model="searchQuery" @keyup.enter="searchFarmacias" type="text"
            placeholder="Introduce el nombre de la farmacia..." />
          <button @click="searchFarmacias" class="boton-claro"> Buscar </button>
          <button @click="addFarmacia" class="boton-oscuro"> Añadir farmacia </button>
        </div>
        <div v-if="hasSearched">
          <div v-if="farmacias.length" class="grid div-cards">
            <GenericCard v-for="farmacia in farmacias" 
            :key="farmacia.id" 
            :title="farmacia.nombre"
            :detail1="'CIF: ' + farmacia.cif" :data="farmacia"
            :detail2="'Dirección: ' + farmacia.direccion" 
            :detail3="'Teléfono: ' + farmacia.telefono"
            :detail4="'Email: ' + farmacia.email" 
            @edit="editFarmacia" 
            @delete="deleteFarmacia" />
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
import GenericCard from './../components/GenericCard.vue';

export default {
  data() {
    return {
      searchQuery: '',
      farmacias: [],
      hasSearched: false
    };
  },
  methods: {
    async searchFarmacias() {
      try {
        // Consultar farmacias
        const response = await apiClient.get('/farmacia');
        console.log('Respuesta de la API:', response.data); // Log de la respuesta de la API
        if (response.data.result == 'ok' && response.data.farmacias) {
          this.farmacias = response.data.farmacias.filter(farmacia =>
            farmacia.nombre.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
          this.hasSearched = true;

        } else {
          console.error('Error en la respuesta de la API:', response.data);
        }
      } catch (error) {
        console.error('Error al obtener las farmacias:', error);
      }

    },
    // Método para añadir una farmacia
    async addFarmacia() {
      const response = await apiClient.post('/farmacia');
    },
    editFarmacia(farmacia) {
      // Lógica para editar la farmacia
      console.log("Editando farmacia:", farmacia);
    },
    deleteFarmacia(farmacia) {
      // Lógica para eliminar la farmacia
      console.log("Eliminando farmacia:", farmacia);
    }
  },
  components: {
    Navbar,
    Footer,
    GenericCard
  }
}
</script>
