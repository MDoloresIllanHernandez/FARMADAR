<template>
    <div class="bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">
      <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ product.nombre }}</h2>
      <p class="text-gray-600">Disponible en: {{ product.nombre_farmacia }}</p>
      <p class="text-gray-600">Stock: {{ product.stock }}</p>
      <p class="text-lg text-primary-azul font-semibold mt-2">Precio: {{ currency(product.precio) }}</p>
      
      <!-- Botón de reserva -->
       <div class="flex justify-end gap-2 mt-4">
      <button   @click="reserveProduct" class="boton-buscar">Reservar </button>
       </div>
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