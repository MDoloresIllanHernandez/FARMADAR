<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Reservas</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <!-- Aquí puedes agregar filtros o botones adicionales -->
        </div>
        <div v-if="hasSearched">
          <div v-if="reservas.length">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmacia</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Inicio</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Fin</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Otros Datos</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="reserva in reservas" :key="reserva.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.id_prod }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.id_farm }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.fecha }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.hora_inicio }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.hora_fin }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.cantidad }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.nombre }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.otros_datos }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.estado }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button @click="editReserva(reserva)" class="text-indigo-600 hover:text-indigo-900">Editar</button>
                    <button @click="deleteReserva(reserva)" class="text-red-600 hover:text-red-900 ml-4">Eliminar</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else>
            <p>No se encontraron reservas.</p>
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
import apiClient from '../scripts/axios.js';

export default {
  components: { Navbar, Footer },
  data() {
    return {
      searchQuery: '',
      reservas: [],
      hasSearched: false
    };
  },
  mounted() {
    this.fetchAllReservas();
  },
  methods: {
    async fetchAllReservas() {
      try {
        const response = await apiClient.get('/reserva');
        this.reservas = response.data.reservas;
        this.hasSearched = true;
      } catch (error) {
        console.error('Error al obtener las reservas:', error);
      }
    },
    editReserva(reserva) {
      // Lógica para editar la reserva
    },
    deleteReserva(reserva) {
      // Lógica para eliminar la reserva
    }
  }
};
</script>
<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Reservas</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <!-- Aquí puedes agregar filtros o botones adicionales -->
        </div>
        <div v-if="hasSearched">
          <div v-if="reservas.length">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmacia</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Inicio</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Fin</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Otros Datos</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="reserva in reservas" :key="reserva.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.id_prod }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.id_farm }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.fecha }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.hora_inicio }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.hora_fin }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.cantidad }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.nombre }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.otros_datos }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.estado }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button @click="editReserva(reserva)" class="text-indigo-600 hover:text-indigo-900">Editar</button>
                    <button @click="deleteReserva(reserva)" class="text-red-600 hover:text-red-900 ml-4">Eliminar</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else>
            <p>No se encontraron reservas.</p>
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
import apiClient from '../scripts/axios.js';

export default {
  components: { Navbar, Footer },
  data() {
    return {
      searchQuery: '',
      reservas: [],
      hasSearched: false
    };
  },
  mounted() {
    this.fetchAllReservas();
  },
  methods: {
    async fetchAllReservas() {
      try {
        const response = await apiClient.get('/reserva');
        this.reservas = response.data.reservas;
        this.hasSearched = true;
      } catch (error) {
        console.error('Error al obtener las reservas:', error);
      }
    },
    editReserva(reserva) {
      // Lógica para editar la reserva
    },
    deleteReserva(reserva) {
      // Lógica para eliminar la reserva
    }
  }
};
</script>
