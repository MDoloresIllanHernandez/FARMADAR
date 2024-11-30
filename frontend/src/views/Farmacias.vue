<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Lista de farmacias</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="farmacias" class="sr-only">Farmacias</label>
          <input id="farmacias" v-model="searchQuery" @keyup.enter="searchFarmacias" type="text" ref="searchInput"
            placeholder="Introduce el nombre de la farmacia..." />
          <button @click="searchFarmacias" class="boton-claro"> Buscar </button>
          <button v-if="showAdd()" @click="openCreateModal" class="boton-oscuro"> Añadir Farmacia </button>
        </div>
        <div v-if="loading" class="loading-overlay">
          <div class="spinner"></div>
        </div>
        <div v-if="hasSearched">
          <div v-if="farmacias.length" class="grid div-cards">
            <GenericCard v-for="farmacia in farmacias" 
            :calledFrom="'Farmacias'"
            :key="farmacia.id"
            :data="farmacia"
            :title="farmacia.nombre"
            :detail1="'CIF: ' + farmacia.cif" 
            :detail2="'Dirección: ' + farmacia.direccion" 
            :detail3="'Teléfono: ' + farmacia.telefono"
            :detail4="'Email: ' + farmacia.email" 
            @edit="openEditModal(farmacia)" 
            @delete="openDeleteModal(farmacia)" />
          </div>
          <div v-else>
            <p>No se encontraron farmacias.</p>
          </div>
        </div>
      </div>
    </div>
    <Footer />

    <!-- Modal para crear farmacia -->
    <ModalCreate
      v-if="isModalCreateVisible"
      :isVisible="isModalCreateVisible"
      :farmacias="farmacias"
      @save="addFarmacia"
      @close="closeModalCreate"
      @errorForm="mostrarError"
    />

    <!-- Modal para editar farmacia -->
    <ModalEditar
      v-if="isModalEditarVisible"
      :isVisible="isModalEditarVisible"
      :farmacia="selectedFarmacia"
      :farmacias="farmacias"
      @save="editFarmacia"
      @close="isModalEditarVisible = false"
      @errorForm="mostrarError" 
    />

    <!-- Modal para eliminar farmacia -->
    <ModalDelete
      v-if="isModalDeleteVisible"
      :isVisible="isModalDeleteVisible"
      :farmacia="selectedFarmacia"
      @confirm="deleteFarmacia"
      @cancel="isModalDeleteVisible = false"
    />  
  </div>
</template>

<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
import GenericCard from './../components/GenericCard.vue';
import apiClient from '../scripts/axios.js';
import ModalCreate from './../components/modal/modalCreateFarmacias.vue';
import ModalEditar from './../components/modal/modalEditarFarmacias.vue';
import ModalDelete from './../components/modal/modalDeleteFarmacias.vue';

export default {
  components: { Navbar, Footer, GenericCard, ModalCreate, ModalEditar, ModalDelete },

  data() {
    return {
      role: sessionStorage.getItem('role'),
      searchQuery: '',
      farmacias: [],
      userRole: '',
      userIdFarm: null,
      hasSearched: false,
      loading: false,
      isModalCreateVisible: false,
      isModalEditarVisible: false,
      isModalDeleteVisible: false,
      selectedFarmacia: null, // Almacena la farmacia seleccionada
    };
  },

  created() {
    // Cargamos los datos del usuario  desde sessionStorage
    this.userRole = sessionStorage.getItem('role');
    this.userIdFarm = sessionStorage.getItem('id_farm');
  },

  mounted() {
    this.$refs.searchInput.focus();
  },

  methods: {
    // Método para mostrar el botón de añadir farmacia
    showAdd() {
      if (this.role == 'usu' || this.role == 'admin') {
        return false;
      }
      return true;
    },

    // Método para abrir el modal de crear
    async openCreateModal() {
      this.isModalCreateVisible = true;
    },

    // Método para abrir el modal de editar
    async openEditModal(farmacia) {
      this.selectedFarmacia = farmacia;
      this.isModalEditarVisible = true;
    },

    // Método para abrir el modal de eliminar
    async openDeleteModal(farmacia) {
      this.selectedFarmacia = farmacia;
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
      this.loading = true;
      this.farmacia = [];
      try {
        // Consultar farmacias
        const response = await apiClient.get('/farmacia');
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
      } finally {
        this.loading = false;
      }
    },

    // Método para añadir una farmacia
    async addFarmacia(formData) {
      this.loading = true;
      try {
        const response = await apiClient.post('/farmacia', {
          cif: formData.cif,
          nombre: formData.nombre,
          direccion: formData.direccion,
          telefono: formData.telefono,
          email: formData.email,
        });
        if (response.data.result === 'ok') {
          this.isModalCreateVisible = false;
          this.$swal.fire({
            icon: "success",
            title: "Farmacia añadida correctamente",
            showConfirmButton: false,
            timer: 2000
          });
          await this.searchFarmacias();
        }
      } catch (error) {
        this.$swal.fire({
          icon: "error",
          title: `Error al añadir la farmacia: ${error.response?.data?.details}`,
          showConfirmButton: true,
        });
        await this.searchFarmacias();
      } finally {
        this.loading = false;
      }
      // this.isModalCreateVisible = false;
    },

    // Método para editar una farmacia
    async editFarmacia(farmacia) {
      this.loading = true;
      try {
        const response = await apiClient.put(`/farmacia?cif=${farmacia.cif}`, {
          cif: farmacia.cif,
          nombre: farmacia.nombre,
          direccion: farmacia.direccion,
          telefono: farmacia.telefono,
          email: farmacia.email,
        });
        if (response.data.result === 'ok') {
          this.isModalEditarVisible = false;
          this.$swal.fire({
            icon: "success",
            title: "Farmacia editada correctamente",
            showConfirmButton: false,
            timer: 2000
          });
          await this.searchFarmacias(); // Actualizar la lista de farmacias
        }
      } catch (error) {
        this.$swal.fire({
          icon: "error",
          title: `Error al editar la farmacia: ${error.response?.data?.details}`,
          showConfirmButton: true,
        });
        await this.searchFarmacias();
      } finally {
        this.loading = false;
      }
      //this.isModalEditarVisible = false;
    },

    // Método para eliminar una farmacia
    async deleteFarmacia() {
      this.loading = true;
      try {
        const response = await apiClient.delete(`/farmacia?cif=${this.selectedFarmacia.cif}`);
        if (response.data.result === 'ok') {
          this.isModalDeleteVisible = false;
          this.$swal.fire({
            icon: "success",
            title: "Farmacia eliminada correctamente",
            showConfirmButton: false,
            timer: 2000
          });
          await this.searchFarmacias(); // Actualizar la lista de farmacias
        }
      } catch (error) {
        this.$swal.fire({
          icon: "error",
          title: `Error al eliminar la farmacia: ${error.response?.data?.details}`,
          showConfirmButton: true,
        });
        await this.searchFarmacias();
      } finally {
        this.loading = false; // Stop loading
      }
      //this.isModalDeleteVisible = false;
    },
  },
}
</script>