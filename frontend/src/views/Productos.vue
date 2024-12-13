<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Lista de productos</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="productos" class="sr-only">Productos</label>
          <input id="productos" v-model="searchQuery" @keyup.enter="searchProducts" type="text" ref="searchInput"
            placeholder="Introduce el nombre del producto..." />
          <button @click="searchProducts" class="boton-claro"> Buscar</button>
          <button v-if="showAdd()" @click="openCreateModal" class="boton-oscuro"> Añadir Producto </button>
        </div>
        <div v-if="loading" class="loading-overlay">
          <div class="spinner"></div>
        </div>
        <div v-if="hasSearched">
          <div v-if="products.length" class="grid div-cards">
            <GenericCard v-for="product in products"
              :calledFrom="'Productos'"
              :key="product.id"
              :title="product.nombre"
              :detail1="'CN: ' + product.id"
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
      @errorForm="mostrarError"
    />

    <!-- Modal para editar producto -->
    <ModalEditar
      v-if="isModalEditarVisible"
      :isVisible="isModalEditarVisible"
      :product="selectedProduct"
      :farmacias="farmacias"
      @save="editProduct"
      @close="isModalEditarVisible = false"
      @errorForm="mostrarError"
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
  components: { Navbar, Footer, GenericCard, ModalEditar, ModalCreate, ModalDelete },

  data() {
    return {
      role: sessionStorage.getItem('role'), //Almacena el role
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

  mounted() {
    this.$refs.searchInput.focus();
  },

  methods: {
    // Método para mostrar el botón de añadir producto
    showAdd() {
      return true;
    },

    // Método para abrir el modal de crear
    async openCreateModal() {
      // Consultar las farmacias 
      await this.searchFarmacias();
      // Mostrar el modal
      this.isModalCreateVisible = true;
    },

    // Método para abrir el modal de editar
    async openEditModal(product) {
      // Copiar el producto para no modificar la referencia original
      this.selectedProduct = { ...product };
      // Consultar las farmacias 
      await this.searchFarmacias();
      this.farmacias = this.farmacias.map(farmacia => ({ ...farmacia, selected: farmacia.id === product.id_farm }));
      // Mostrar el modal
      this.isModalEditarVisible = true;
    },

    // Método para abrir el modal de eliminar
    async openDeleteModal(product) {
      // Copiar el producto para no modificar la referencia original
      this.selectedProduct = { ...product };
      // Mostrar el modal
      this.isModalDeleteVisible = true;
    },

    // Método para cerrar el modal de crear
    closeModalCreate() {
      this.isModalCreateVisible = false;
    },

    // Método para mostrar un error
    mostrarError(errorField) {
      this.$swal.fire({
        icon: "error",
        title: `El campo ${errorField} es obligatorio`,
        showConfirmButton: true,
      });
    },

    // Método para buscar farmacias
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

        // Consultar productos enviando role e id_farm como parámetros
        const response = await apiClient.get('/producto', {
          params: { role, id_farm: idFarm, source: 'producto' },
        });
        if (response.data.result == 'ok' && response.data.productos) {
          this.products = response.data.productos.filter(product =>
            product.nombre.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
          // Mostrar un mensaje si hay productos sin stock
          const outOfStockProducts = this.products.filter(product => Number(product.stock) == 0);
          if (role !== 'superadmin' && outOfStockProducts.length > 0) {
            this.$swal.fire({
              icon: "warning",
              title: `Tiene ${outOfStockProducts.length} productos sin stock`,
              text: outOfStockProducts.map(product => product.nombre).join(', '),
              showConfirmButton: true,
            });
          }
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
          this.isModalCreateVisible = false;
          this.$swal.fire({
            icon: "success",
            title: "Producto añadido correctamente",
            showConfirmButton: false,
            timer: 2000
          });
          await this.searchProducts();
        }
      } catch (error) {
        this.$swal.fire({
          icon: "error",
          title: `Error al añadir el producto: ${error.response?.data?.details}`,
          showConfirmButton: true,
        });
        await this.searchProducts(); // Actualizar la lista de productos
      } finally {
        this.loading = false; // Stop loading
      }
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
          this.isModalEditarVisible = false;
          this.$swal.fire({
            icon: "success",
            title: "Producto editado correctamente",
            showConfirmButton: false,
            timer: 2000
          });
          await this.searchProducts(); // Actualizar la lista de productos
        }
      } catch (error) {
        this.$swal.fire({
          icon: "error",
          title: `Error al editar el producto: ${error.response?.data?.details}`,
          showConfirmButton: true,
        });
        await this.searchProducts();
      } finally {
        this.loading = false; // Stop loading
      }
    },

    // Método para eliminar un producto
    async deleteProduct() {
      this.loading = true;
      try {
        const response = await apiClient.delete(`/producto?id=${this.selectedProduct.id}`);
        if (response.data.result === 'ok') {
          this.isModalDeleteVisible = false;
          this.$swal.fire({
            icon: "success",
            title: "Producto eliminado correctamente",
            showConfirmButton: false,
            timer: 2000
          });
          await this.searchProducts(); // Actualizar la lista de productos
        }
      } catch (error) {
        this.$swal.fire({
          icon: "error",
          title: `Error al eliminar el producto: ${error.response?.data?.details}`,
          showConfirmButton: true,
        });
        await this.searchProducts();
      } finally {
        this.loading = false; // Stop loading
      }
    },

    // Método para formatear el precio como moneda
    currency(value) {
      if (!value || isNaN(value)) return '0.00€';
      return `${parseFloat(value).toFixed(2)}€`;
    }
  },
}
</script>