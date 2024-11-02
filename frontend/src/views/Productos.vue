<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Lista de productos</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="productos" class="sr-only">Productos</label>
          <input id="productos" v-model="searchQuery" @keyup.enter="searchProducts" type="text"
            placeholder="Introduce el nombre del producto..." />
          <button @click="searchProducts" class="boton-claro"> Buscar </button>
          <button @click="addProduct" class="boton-oscuro"> Añadir Producto</button>
        </div>
        <div v-if="hasSearched">
          <div v-if="products.length" class="grid div-cards">
            <GenericCard
              v-for="product in products"
              :key="product.id"
              :title="product.nombre"
              :detail1="'Id: ' + product.id"
              :detail2="'Stock: ' + product.stock"
              :detail3="'Farmacia: ' + product.nombre_farmacia"
              :detail4="'Precio: ' + currency(product.precio)"
              :price="currency(product.precio)"
              :data="product"
              @edit="editProduct"
              @delete="deleteProduct"
            />
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
import GenericCard from './../components/GenericCard.vue';
import apiClient from '../scripts/axios.js';

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
        //Consultar productos
        const response = await apiClient.get('/producto');
        if (response.data.result == 'ok' && response.data.productos) {
          this.products = response.data.productos.filter(product => 
            product.nombre.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        
        //Consultar farmacias
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

          this.hasSearched = true;
        }
      } catch (error) {
        console.error('Error al obtener los productos o farmacias:', error);
      }
    },      
    // Método para añadir un producto
    async addProduct() {
      // Lógica para añadir un producto
      console.log("Añadiendo producto");
      
      // Redirigir a la vista nuevo producto
      this.$router.push({ name: 'ProductosNuevo' });

    },
    editProduct(product) {
      // Lógica para editar el producto
      console.log("Editando producto:", product);
    },
    deleteProduct(product) {
      // Lógica para eliminar el producto
      console.log("Eliminando producto:", product);
    },
    currency(value) {
      if (!value || isNaN(value)) return '0.00€';
      return `${parseFloat(value).toFixed(2)}€`;
    }
  },
  components: {
    Navbar,
    Footer,
    GenericCard
  }
}
</script>
