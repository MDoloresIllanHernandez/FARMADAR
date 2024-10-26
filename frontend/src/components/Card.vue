<template>
    <div class="bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">
      <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ product.nombre }}</h2>
      <p class="text-gray-600">Stock: {{ product.stock }}</p>
      <p class="text-gray-600">ID: {{ product.id }}</p>
      <p class="text-gray-600">Farmacia: {{ product.id_farm }}</p>
      <p class="text-lg text-primary-azul font-semibold mt-2">Precio: {{ currency(product.precio) }}</p>
      
      <!-- Botón de reserva -->
      <button 
        @click="reserveProduct"
        class="mt-4 w-full bg-primary-azul text-white font-semibold py-2 px-4 rounded hover:bg-primary-turquesa transition-colors">
        Reservar
      </button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      product: {
        type: Object,
        required: true
      }
    },
    methods: {
      currency(value) {
        if (!value || isNaN(value)) return '0.00€';
        return `${parseFloat(value).toFixed(2)}€`;
      },
      reserveProduct() {
        this.$emit('reserve', this.product);
      }
    }
  };
  </script>