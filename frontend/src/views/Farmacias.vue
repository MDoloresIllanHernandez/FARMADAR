<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Lista de farmacias</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="farmacias" class="sr-only">Farmacias</label>
          <input id="farmacias" v-model="searchQuery" @keyup.enter="searchFarmacias" type="text"
            placeholder="Introduce el nombre de la farmacia..." />
          <button @click="searchFarmacias" class="boton-claro"> Buscar </button>
          <button @click="openCreateModal" class="boton-oscuro"> Añadir Farmacia </button>
        </div>
        <div v-if="loading" class="loading-overlay">
          <div class="spinner"></div>
        </div>
        <div v-if="hasSearched">
          <div v-if="farmacias.length" class="grid div-cards">
            <GenericCard v-for="farmacia in farmacias" 
            :key="farmacia.id" 
            :title="farmacia.nombre"
            :detail1="'CIF: ' + farmacia.cif" :data="farmacia"
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
     
    />
    <!-- Modal para editar farmacia -->
    <ModalEditar
      v-if="isModalEditarVisible"
      :isVisible="isModalEditarVisible"
      :farmacia="selectedFarmacia"
      :farmacias="farmacias"
      @save="editFarmacia"
      @close="isModalEditarVisible = false"
      
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
  components: {
    Navbar, Footer, GenericCard, ModalCreate, ModalEditar, ModalDelete },
  
  data() {
    return {
      searchQuery: '',
      farmacias: [],
      hasSearched: false,
      loading: false,
      isModalCreateVisible: false,
      isModalEditarVisible: false,
      isModalDeleteVisible: false,
      selectedFarmacia: null,
    };
  },
  methods: {
    async openCreateModal() {
      this.isModalCreateVisible = true;
    },
    async openEditModal(farmacia) {
      this.selectedFarmacia = farmacia;
      this.isModalEditarVisible = true;
    },
    async openDeleteModal(farmacia) {
      this.selectedFarmacia = farmacia;
      this.isModalDeleteVisible = true;
    },
    closeModalCreate() {
      // Cerrar el modal
      this.isModalCreateVisible = false; 
    },
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
              title: "Farmacia modificada correctamente",
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
          console.log('Farmacia eliminada correctamente:', response.data.farmacia);
          await this.searchFarmacias(); // Actualizar la lista de farmacias
        }
      } catch (error) {
        console.error('Error al eliminar la farmacia:', error);
      } finally {
        this.loading = false; // Stop loading
      }
        this.isModalDeleteVisible = false;
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
