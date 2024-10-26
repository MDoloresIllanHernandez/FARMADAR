<template>
  <div class="bg-white">

    <Navbar />

    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Productos</h1>
        <p class="text-xl sm:text-xl text-primary-oscuro">¿Qué productos desea buscar?</p>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="productos" class="sr-only">Productos</label>
          <input id="productos" v-model="searchQuery" @keyup.enter="searchProducts" type="text"
            placeholder="Introduce el nombre del producto..."
            class="min-w-0 flex-auto p-2 border border-primary-oscuro rounded" />

          <button @click="searchProducts"
            class="flex-none rounded-md bg-primary-azul px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa hover:text-primary-oscuro focus-visible:outline-primary-oscuro">
            Buscar
          </button>
        </div>
        <div v-if="hasSearched">
          <div v-if="products.length">
            <ProductCard v-for="product in products" :key="product.id" :product="product" @reserve="handleReserve" />
          </div>
          <div v-else>
            <p>No se encontraron productos.</p>
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
import ProductCard from './../components/Card.vue';
import apiClient from '../scripts/axios.js';
import axios from 'axios';

export default {
  data() {
    return {
      searchQuery: '',
      products: [],
      hasSearched: false
    };
  },
  methods: {
    async searchProducts() {
      try {
        // Consultar productos
        const productsResponse = await apiClient.get('/producto');
        if (productsResponse.data.result === 'ok' && productsResponse.data.productos) {
          this.products = productsResponse.data.productos.filter(product =>
            product.nombre.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
/*
          // Consultar farmacias
          const pharmaciesResponse = await apiClient.get('/farmacia');
          if (pharmaciesResponse.data.result === 'ok' && pharmaciesResponse.data.farmacias) {
            const farmacias = pharmaciesResponse.data.farmacias;

            // Asociar el nombre de la farmacia al producto correspondiente
            this.products = this.products.map(product => {
              const farmacia = farmacias.find(f => f.id === product.id_farm);
              return {
                ...product,
                nombre_farmacia: farmacia ? farmacia.nombre : 'Desconocido'
              };
            });
          }
*/
          this.hasSearched = true;
        }
      } catch (error) {
        console.error('Error al obtener los productos o farmacias:', error);
      }
    },
    // Método para formatear el precio a moneda
    currency(value) {
      if (!value || isNaN(value)) return '0.00€';
      return `${parseFloat(value).toFixed(2)}€`;
    },
    // Método para gestionar la reserva de un producto
    handleReserve(product) {
      // Lógica para realizar la reserva
      console.log(`Reserva realizada para el producto: ${product.nombre}`);
      // Aquí se podría realizar una llamada API o cualquier lógica adicional para gestionar la reserva
    }
  },  


  components: {
    Navbar,
    Footer,
    ProductCard
  }
};
</script>
