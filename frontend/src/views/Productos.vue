<template>
  <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
    <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Productos</h1>
    <p class="text-xl sm:text-xl text-primary-oscuro">¿Qué productos desea buscar?</p>
    <div class="mt-6 flex gap-x-4 pb-8">
      <label for="productos" class="sr-only">Productos</label>
      <input
        id="productos"
        v-model="searchQuery"
        @keyup.enter="searchProducts"
        type="text"
        placeholder="Introzca el nombre del producto..."
        class="min-w-0 flex-auto p-2 border border-primary-oscuro rounded"
      />
   
      <button @click="searchProducts" class="flex-none rounded-md bg-primary-azul px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-verde hover:text-primary-oscuro focus-visible:outline-primary-oscuro">
        Buscar
      </button>
    </div>
    <div v-if="hasSearched">
    <div v-if="products.length">
      <div v-for="product in products" :key="product.id" class="card mb-4 p-4 border border-gray-300 rounded">
        <h2 class="text-xl font-bold">{{ product.name }}</h2>
        <p>{{ product.description }}</p>
        <p class="text-gray-500">{{ product.price | currency }}</p>
      </div>
    </div>
    <div v-else>
      <p>No se encontraron productos.</p>
    </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchQuery: '',
      products: [],
      hasSearched: false
    };
  },
  methods: {
    searchProducts() {
      // Aquí deberías realizar la búsqueda de productos.
      // Este es un ejemplo estático, reemplázalo con tu lógica de búsqueda.
      this.products = [
        { id: 1, name: 'Producto 1', description: 'Descripción del producto 1', price: 100 },
        { id: 2, name: 'Producto 2', description: 'Descripción del producto 2', price: 200 }
      ].filter(product => product.name.toLowerCase().includes(this.searchQuery.toLowerCase()));

      this.hasSearched = true;
    }
  },
  filters: {
    currency(value) {
      return `$${value.toFixed(2)}`;
    }
  }
};
</script>

<style scoped>
.card {
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>