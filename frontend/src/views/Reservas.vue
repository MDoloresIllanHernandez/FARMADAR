<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Reservas</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <input id="productos" v-model="searchQuery" @input="searchReservas" type="text"
            placeholder="Introduce el nombre del producto o del cliente..." />
          <button @click="searchReservas" class="boton-claro"> Buscar</button>
        </div>
        <div v-if="hasSearched">
          <div v-if="reservas.length" class="flex justify-center">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmacia
                    origen</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmacia
                    destino</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Inicio
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora Fin</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Otros Datos
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="reserva in reservas" :key="reserva.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ getFarmaciaName(reserva.farm_origen) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ getFarmaciaName(reserva.id_farm) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ getProductoName(reserva.id_prod) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.fecha }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.hora_inicio }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.hora_fin }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.cantidad }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.nombre }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.otros_datos }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ reserva.estado }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button @click="editReserva(reserva)" class="text-indigo-600 hover:text-indigo-900">Editar</button>
                    <button @click="deleteReserva(reserva)"
                      class="text-red-600 hover:text-red-900 ml-4">Eliminar</button>
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
      farmacias: [],
      productos: [],
      hasSearched: false
    };
  },
  mounted() {
    this.fetchAllReservas();
  },
  methods: {
  async fetchAllReservas() {
    try {
      // Obtener todas los datos que necesitamos de la api
      const responseReservas = await apiClient.get('/reserva');
      const responseFarmacias = await apiClient.get('/farmacia');
      const responseProductos = await apiClient.get('/producto');

      // Asignar los datos a las variables locales
      this.reservas = responseReservas.data.reservas;
      this.farmacias = responseFarmacias.data.farmacias;
      this.productos = responseProductos.data.productos;

      // Ordenar las reservas primero por fecha (más recientes primero)
      // Luego, dentro de la misma fecha, ordenarlas por hora de fin (más alta primero)
      this.reservas.sort((a, b) => {
        const fechaA = new Date(a.fecha);
        const fechaB = new Date(b.fecha);

        // Primero comparar las fechas
        if (fechaB - fechaA !== 0) {
          return fechaB - fechaA; // Si las fechas son diferentes, ordenar por fecha
        }

        // Si las fechas son iguales, comparar las horas de fin
        // Convertir la hora de fin en minutos para hacer la comparación
        const [horaFinA, minutoFinA] = a.hora_fin.split(':').map(Number);
        const [horaFinB, minutoFinB] = b.hora_fin.split(':').map(Number);

        // Convertir hora y minutos a minutos totales para una comparación sencilla
        const totalMinutosA = horaFinA * 60 + minutoFinA;
        const totalMinutosB = horaFinB * 60 + minutoFinB;

        return totalMinutosB - totalMinutosA; // Ordenar de mayor a menor
      });

      // Marcar que ya se ha realizado una búsqueda
      this.hasSearched = true;
    } catch (error) {
      console.error('Error al obtener los datos:', error);
    }
  },

  // Filtrar las reservas basadas en la búsqueda
  searchReservas() {
    if (this.searchQuery.trim() === '') {
      this.fetchAllReservas(); // Si no hay búsqueda, cargar todas las reservas
    } else {
      // Filtrar las reservas que coincidan con el nombre del cliente o el nombre del producto
      this.reservas = this.reservas.filter(reserva => {
        const clienteMatch = reserva.nombre.toLowerCase().includes(this.searchQuery.toLowerCase());
        const productoMatch = this.getProductoName(reserva.id_prod).toLowerCase().includes(this.searchQuery.toLowerCase());
        return clienteMatch || productoMatch;
      });
    }
  },

  // Método para obtener el nombre de la farmacia
  getFarmaciaName(farmaciaId) {
    const farmacia = this.farmacias.find(farmacia => farmacia.id === farmaciaId);
    return farmacia ? farmacia.nombre : 'No disponible';
  },

  // Método para obtener el nombre del producto
  getProductoName(productoId) {
    const producto = this.productos.find(producto => producto.id === productoId);
    return producto ? producto.nombre : 'No disponible';
  },

  // Método para editar una reserva
  editReserva(reserva) {
    // Lógica para editar la reserva
  },
  // Método para eliminar una reserva
  async deleteReserva(reserva) {
    try {
      // Hacer la llamada DELETE a la API usando el id de la reserva
      const response = await apiClient.delete(`/reserva?id=${reserva.id}`);
      
      if (response.data.result === 'ok') {
        console.log('Reserva eliminada correctamente:', response.data.producto);
        
        // Actualizar la lista de reservas después de la eliminación
        await this.fetchAllReservas();
      } else {
        console.error('Error al eliminar la reserva:', response.data);
      }
    } catch (error) {
      console.error('Error al eliminar la reserva', error);
    }
  },
}


};
</script>
