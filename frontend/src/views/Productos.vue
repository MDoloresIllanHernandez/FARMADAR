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
          <button @click="searchProducts" class="boton-claro"> Buscar</button>
          <button @click="openCreateModal" class="boton-oscuro"> Añadir Producto </button>
        </div>
        <div v-if="loading" class="loading-overlay">
          <div class="spinner"></div>
        </div>
        <div v-if="hasSearched">
          <div v-if="products.length" class="grid div-cards">
            <GenericCard v-for="product in products"
              :key="product.id"
              :title="product.nombre"
              :detail1="'Id: ' + product.id"
              :detail2="'Stock: ' + product.stock"
              :detail3="'Farmacia: ' + product.nombre_farmacia"
              :detail4="'Precio: ' + currency(product.precio)"
              :price="currency(product.precio)"
              :data="product"
              @edit="openEditModal(product)"
              @delete="openDeleteModal(product)"
            />
          </div>
          <div v-else>
            <p>No se encontraron productos.</p>
          </div>
        </div>
      </div>
    </div>
    <Footer />
    <!-- Modal para crear producto -->
    <ModalCreate
      v-if="isModalCreateVisible"
      :isVisible="isModalCreateVisible"
      :farmacias="farmacias"
      @save="addProduct"
      @close="closeModalCreate"
     
    />
    <!-- Modal para editar producto -->
    <ModalEditar
      v-if="isModalEditarVisible"
      :isVisible="isModalEditarVisible"
      :product="selectedProduct"
      :farmacias="farmacias"
      @save="editProduct"
      @close="isModalEditarVisible = false"
      
    />
    <!-- Modal para eliminar producto -->
    <ModalDelete
      v-if="isModalDeleteVisible"
      :isVisible="isModalDeleteVisible"
      :product="selectedProduct"
      @confirm="deleteProduct"
      @cancel="isModalDeleteVisible = false"
    
    />  
  </div>
</template>

<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
import GenericCard from './../components/GenericCard.vue';
import apiClient from '../scripts/axios.js';
import ModalCreate from '../components/modal/modalCreateProductos.vue';
import ModalEditar from '../components/modal/modalEditarProductos.vue';
import ModalDelete from '../components/modal/modalDeleteProductos.vue';

export default {
  components: {
    Navbar, Footer, GenericCard, ModalEditar, ModalCreate, ModalDelete },

  data() {
    return {
      searchQuery: '',
      products: [],
      farmacias: [],
      hasSearched: false,
      loading: false,
      isModalEditarVisible: false,
      isModalCreateVisible: false,
      isModalDeleteVisible: false,
      selectedProduct: null, // Almacena el producto seleccionado para editar
    };
  },
  methods: {
    async openEditModal(product) {
      // Copiar el producto para no modificar la referencia original
      this.selectedProduct = { ...product }; 
      // Consultar las farmacias 
      await this.searchFarmacias(); 
      this.farmacias = this.farmacias.map(farmacia => ({ ...farmacia, selected: farmacia.id === product.id_farm }));
      // Mostrar el modal
      this.isModalEditarVisible = true; 
    },
    async openCreateModal() {
      // Consultar las farmacias 
      await this.searchFarmacias(); 
      // Mostrar el modal
      this.isModalCreateVisible = true; 
    },
    async openDeleteModal(product) {
      // Copiar el producto para no modificar la referencia original
      this.selectedProduct = { ...product }; 
      // Mostrar el modal
      this.isModalDeleteVisible = true; 
    },
    closeModalCreate() {
      // Cerrar el modal
      this.isModalCreateVisible = false; 
    },
    async searchFarmacias() {
      try {
        const pharmaciesResponse = await apiClient.get('/farmacia');
        if (pharmaciesResponse.data.result === 'ok' && pharmaciesResponse.data.farmacias) {
          this.farmacias = pharmaciesResponse.data.farmacias;
        }
      } catch (error) {
        console.error('Error al obtener las farmacias:', error);
      }
    },
    // Método para buscar productos
    async searchProducts() {
      this.loading = true;
      this.products = []; // Limpiar la lista de productos
      try {
        // Recuperar role y id_farm desde sessionStorage
        const role = sessionStorage.getItem('role');
        const idFarm = sessionStorage.getItem('id_farm');

        // Consultar usuarios enviando role e id_farm como parámetros
        const response = await apiClient.get('/producto', {
          params: { role, id_farm: idFarm, source: 'producto' },
        });
        if (response.data.result == 'ok' && response.data.productos) {
          this.products = response.data.productos.filter(product => 
            product.nombre.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        //Consultar farmacias
        await this.searchFarmacias(); 
          // Asociar el nombre de la farmacia al producto correspondiente
          this.products = this.products.map(product => {
            const farmacia = this.farmacias.find(f => f.id === product.id_farm);
            return {
              ...product,
              nombre_farmacia: farmacia ? farmacia.nombre : 'Desconocido'
            };
          });
        }
          this.hasSearched = true;
      } catch (error) {
        console.error('Error al obtener los productos', error);
      } finally {
        this.loading = false; // Stop loading
      }
    },      
    // Método para añadir un producto
    async addProduct(formData) {
      this.loading = true;
      try {
        const response = await apiClient.post('/producto', {
          id: formData.id,
          nombre: formData.producto,
          stock: formData.stock,
          precio: formData.precio,
          id_farm: formData.id_farm
        });
        if (response.data.result === 'ok') {
          console.log('Producto añadido correctamente');
          await this.searchProducts();
        }
      } catch (error) {
        console.error('Error al añadir el producto:', error);
      } finally {
        this.loading = false; // Stop loading
      }
      this.isModalCreateVisible = false;
    },
    // Método para editar un producto
    async editProduct(product) {
      this.loading = true;
      try {
        const response = await apiClient.put(`/producto?id=${product.id}`, {
          nombre: product.nombre,
          stock: product.stock,
          precio: product.precio,
          id_farm: product.id_farm
        });
        if (response.data.result === 'ok') {
          console.log('Producto editado correctamente');
          await this.searchProducts(); // Actualizar la lista de productos
        } 
      } catch (error) {
        console.error('Error al editar el producto:', error);
      } finally {
      this.loading = false; // Stop loading
    }
      this.isModalEditarVisible = false 
    },
    // Método para eliminar un producto
    async deleteProduct() {
      this.loading = true;
      try {
        const response = await apiClient.delete(`/producto?id=${this.selectedProduct.id}`);
        if (response.data.result === 'ok') {
          console.log('Producto eliminado correctamente:', response.data.producto);
          await this.searchProducts(); // Actualizar la lista de productos
        }
      } catch (error) {
        console.error('Error al eliminar el producto:', error);
      } finally {
      this.loading = false; // Stop loading
    }
      this.isModalDeleteVisible = false;  
    },
    // Método para formatear el precio como moneda
    currency(value) {
      if (!value || isNaN(value)) return '0.00€';
      return `${parseFloat(value).toFixed(2)}€`;
    }
  },
}
</script>
<style>
/* Spinner styles */
.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Overlay styles */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8); /* Slight overlay background */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure it’s above other elements */
}
</style>