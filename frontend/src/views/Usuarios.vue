<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Lista de usuarios</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="usuarios" class="sr-only">Usuarios</label>
          <input id="usuarios" v-model="searchQuery" @keyup.enter="searchUsers" type="text"
            placeholder="Introduce el nombre del usuario..."
            class="min-w-0 flex-auto p-2 border border-primary-oscuro rounded" />
          <button @click="searchUsers" class="boton-claro"> Buscar </button>
          <button v-if="!isUsu" @click="addUser" class="boton-oscuro"> Añadir usuario </button>
        </div>
        <div v-if="hasSearched">
          <div v-if="users.length" class="grid div-cards">
            <GenericCard
              v-for="user in users"
              :key="user.id"
              :title="user.nombre"
              :detail1="'Username: ' + user.username"
              :detail2="'Farmacia: ' + user.nombre_farmacia"
              :detail3="'Rol: ' + user.role"
              :data="user"
              @edit="openEditModal(user)"
              @delete="openDeleteModal(user)"
            />
          </div>
          <div v-else>
            <p>No se encontraron usuarios.</p>
          </div>
        </div>
      </div>
    </div>
    <Footer />
    <!-- Modal para crear usuario -->
    <ModalCreate
      v-if="isModalCreateVisible"
      :isVisible="isModalCreateVisible"
      :farmacias="farmacias"
      @save="addUser"
      @close="closeModalCreate"
     
    />
    <!-- Modal para editar usuario -->
    <ModalEditar
      v-if="isModalEditarVisible"
      :isVisible="isModalEditarVisible"
      :user="selectedUser"
      :farmacias="farmacias"
      @save="editUser"
      @close="isModalEditarVisible = false"
      
    />
    <!-- Modal para eliminar usuario -->
    <ModalDelete
      v-if="isModalDeleteVisible"
      :isVisible="isModalDeleteVisible"
      :user="selectedUser"
      @confirm="deleteUser"
      @cancel="isModalDeleteVisible = false"
    
    />  
  </div>
</template>

<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
import GenericCard from './../components/GenericCard.vue';
import apiClient from '../scripts/axios.js';
import ModalEditar from '../components/modal/modalEditarUsuarios.vue';
import ModalCreate from '../components/modal/modalCreateUsuarios.vue';
import ModalDelete from '../components/modal/modalDeleteUsuarios.vue';

export default {
  components: {
    Navbar, Footer, GenericCard, ModalEditar, ModalCreate, ModalDelete },

  data() {
    return {
      //role : null,
      searchQuery: '',
      users: [],
      farmacias: [],
      hasSearched: false,
      loading: false,
      isModalEditarVisible: false,
      isModalCreateVisible: false,
      isModalDeleteVisible: false,
      selectedUser: null, // Almacena el usuario seleccionado para editar
    };
  },
  
  methods: {
    async openEditModal(user) {
      // Copiar el usuario para no modificar la referencia original
      this.selectedUser = { ...user }; 
      // Consultar las farmacias 
      await this.searchFarmacias(); 
      this.farmacias = this.farmacias.map(farmacia => ({ ...farmacia, selected: farmacia.id === user.id_farm }));
      // Mostrar el modal
      this.isModalEditarVisible = true; 
    },
    async openCreateModal() {
      // Consultar las farmacias 
      await this.searchFarmacias(); 
      // Mostrar el modal
      this.isModalCreateVisible = true; 
    },
    async openDeleteModal(user) {
      // Copiar el usuario para no modificar la referencia original
      this.selectedUser = { ...user }; 
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
    // Método para buscar usuarios
    async searchUsers() {
      this.loading = true;
      this.users = []; // Limpiar la lista de usuarios
      try {
        //Consultar usuarios
        const response = await apiClient.get('/usuario');
        if (response.data.result == 'ok' && response.data.usuarios) {
          this.users = response.data.usuarios.filter(user => 
            user.nombre.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        //Consultar farmacias
        await this.searchFarmacias(); 
          // Asociar el nombre de la farmacia al usuario correspondiente
          this.users = this.users.map(user => {
            const farmacia = this.farmacias.find(f => f.id === user.id_farm);
            return {
              ...user,
              nombre_farmacia: farmacia ? farmacia.nombre : 'Desconocido'
            };
          });
        }
          this.hasSearched = true;
        
      } catch (error) {
        console.error('Error al obtener los usuarios', error);
      } finally {
      this.loading = false; // Stop loading
      }
    },      
    // Método para añadir un usuario
    async addUser(formData) {
      this.loading = true;
      try {
        const response = await apiClient.post('/usuario', {
          id: formData.id,
          nombre: formData.nombre,
          username: formData.username,
          role: formData.role,
          id_farm: formData.id_farm
        });
        if (response.data.result === 'ok') {
          console.log('Usuario añadido correctamente:', response.data.usuario);
          await this.searchUsers();
        }
      } catch (error) {
        console.error('Error al añadir el usuario:', error);
      } finally {
      this.loading = false; // Stop loading
    }
      this.isModalCreateVisible = false;
    },
    // Método para editar un usuario
    async editUser(user) {
      this.loading = true;
      try {
        const response = await apiClient.put(`/usuario?id=${user.id}`, {
          nombre: user.nombre,
          username: user.username,
          role: user.role,
          id_farm: user.id_farm
        });
        if (response.data.result === 'ok') {
          console.log('Usuario editado correctamente:', response.data.usuario);
          await this.searchUsers(); // Actualizar la lista de usuarios
        } 
      } catch (error) {
        console.error('Error al editar el usuario:', error);
      } finally {
      this.loading = false; // Stop loading
    }
      this.isModalEditarVisible = false 
    },
    // Método para eliminar un usuario
    async deleteUser() {
      this.loading = true;
      try {
        const response = await apiClient.delete(`/usuario?id=${this.selectedUser.id}`);
        if (response.data.result === 'ok') {
          console.log('Usuario eliminado correctamente:', response.data.usuario);
          await this.searchUsers(); // Actualizar la lista de usuarios
        }
      } catch (error) {
        console.error('Error al eliminar el usuario:', error);
      }finally {
      this.loading = false; // Stop loading
    }
      this.isModalDeleteVisible = false;  
    },
    isUsu() {
      return sessionStorage.getItem('role') == 'usu';  
    },
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