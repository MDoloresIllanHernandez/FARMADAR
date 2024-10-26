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
            <ProductCard 
              v-for="product in products" 
              :key="product.id" 
              :product="product"
              @reserve="handleReserve" />
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
      const response = await apiClient.get('/producto');
        if (response.data.result == 'ok' && response.data.productos) {
          this.products = response.data.productos.filter(product => 
          product.nombre.toLowerCase().includes(this.searchQuery.toLowerCase()));

          this.hasSearched = true;
        }
    },
    currency(value) {
      if (!value || isNaN(value)) return '0.00€';
      return `${parseFloat(value).toFixed(2)}€`;
    }
  },


  components: {
    Navbar,
    Footer,
    ProductCard
  }
};
</script>

