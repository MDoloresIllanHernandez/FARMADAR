<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Lista de usuarios</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="usuarios" class="sr-only">usuarios</label>
          <input id="usuarios" v-model="searchQuery" @keyup.enter="searchUsers" type="text"
            placeholder="Introduce el nombre del usuario..."
            class="min-w-0 flex-auto p-2 border border-primary-oscuro rounded" />
          <button @click="searchUsers" class="boton-claro"> Buscar </button>
          <button @click="addUser" class="boton-oscuro"> Añadir usuario </button>
        </div>
        <div v-if="hasSearched">
          <div v-if="users.length" class="grid div-cards">
            <GenericCard
              v-for="user in users"
              :key="user.id"
              :title="user.nombre"
              :detail1="'Nombre de usuario: ' + user.username"
              :detail2="'Contraseña: ' + user.password"
              :detail3="'Rol: ' + user.rol"
              :data="user"
              @edit="edituser"
              @delete="deleteuser"
            />
          </div>
          <div v-else>
            <p>No se encontraron usuarios.</p>
          </div>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>
<script>
import Navbar from '../components/Navbar.vue';
import Footer from '../components/Footer.vue';
import GenericCard from '../components/GenericCard.vue';
import apiClient from '../scripts/axios.js';

export default {
  components: { Navbar, Footer, GenericCard },

  data() {
    return {
      searchQuery: '',
      users: [],
      hasSearched: false
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user => user.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
    }
  },
  methods: {
    // Consultar usuarios
    async searchUsers() {
      try {
        const response = await apiClient.get('/usuario');
        if (response.data.result == 'ok' && response.data.users) {
          this.users = response.data.users;
          this.hasSearched = true;
        }
      } catch (error) {
        console.error(error);
      }
    },
    addUser() {
      this.$router.push('/usuarios/nuevo');
    },
    editUser(user) {
      this.$router.push(`/usuarios/${user.id}`);
    },
    deleteUser(user) {
      if (confirm(`¿Estás seguro de que deseas eliminar a ${user.name}?`)) {
        apiClient.delete(`/usuario/${user.id}`)
          .then(response => {
            if (response.data.result === 'ok') {
              this.users = this.users.filter(u => u.id !== user.id);
            }
          })
          .catch(error => console.error(error));
      }
    }
  }
}
</script>
