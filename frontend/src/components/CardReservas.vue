<template>
  <div class="bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ product.nombre }}</h2>
    <p class="text-gray-600">Disponible en: {{ product.nombre_farmacia }}</p>
    <p class="text-gray-600">Stock: {{ product.stock }}</p>
    <p class="text-lg text-primary-azul font-semibold mt-2">Precio: {{ currency(product.precio) }}</p>

    <!-- Campos ocultos con la información del producto -->
    <p hidden>Product ID: {{ product.id }}</p>
    <p hidden>Farm ID: {{ product.id_farm }}</p>

    <!-- Botón de reserva -->
    <div class="flex justify-end gap-2 mt-4">
      <button @click="reserveProduct" class="boton-claro">Reservar </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    product: {
      type: Object,
      required: true
    },
    farmacias: {
      type: Array,
      required: false,
    },
  },
  methods: {
    // Método para formatear el precio a moneda
    currency(value) {
      if (!value || isNaN(value)) return '0.00€';
      return `${parseFloat(value).toFixed(2)}€`;
    },
    // Método para redirigir a la vista de reservas
    reserveProduct() {
      this.$router.push({ name: 'Reserva', params: { productId: this.product.id, farmId: this.product.id_farm } });
    }
  }
};
</script>