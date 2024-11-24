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
                  <th scope="col">Farmacia origen</th>
                  <th scope="col">Farmacia destino</th>
                  <th scope="col">Producto</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Hora Inicio</th>
                  <th scope="col">Hora Fin</th>
                  <th scope="col">Cantidad</th>
                  <th scope="col">Cliente</th>
                  <th scope="col">Otros Datos</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="reserva in reservas" :key="reserva.id"
                  :class="{ 'bg-primary-turquesa': isEditing(reserva.id) }">
                  <td><span>{{ getFarmaciaName(reserva.farm_origen) }}</span></td>
                  <td><span>{{ getFarmaciaName(reserva.id_farm) }}</span></td>
                  <td><span>{{ getProductoName(reserva.id_prod) }}</span></td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="reserva.editedFecha" type="date" class="w-full" />
                    <span v-else>{{ formatFecha(reserva.fecha) }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="reserva.editedHoraInicio" type="time" class="w-full" />
                    <span v-else>{{ reserva.hora_inicio }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="reserva.editedHoraFin" type="time" class="w-full" />
                    <span v-else>{{ reserva.hora_fin }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="reserva.editedCantidad" type="number" class="w-full" />
                    <span v-else>{{ reserva.cantidad }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="reserva.editedNombre" type="text" class="w-full" />
                    <span v-else>{{ reserva.nombre }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="reserva.editedOtrosDatos" type="text" class="w-full" />
                    <span v-else>{{ reserva.otros_datos }}</span>
                  </td>
                  <td><span>{{ reserva.estado }}</span></td>
                  <td>
                    <div v-if="isEditing(reserva.id)">
                      <button @click="saveReserva(reserva)" class="boton-claro boton-claro-confirmar">
                        Confirmar
                      </button>
                      <button @click="editingId = null" class="boton-oscuro">
                        Cancelar
                      </button>
                    </div>
                    <div v-else>
                      <button v-if="reserva.estado === 'Pendiente' || showButton()" @click="confirmReserva(reserva)" class="p-0 rounded hover:bg-primary-verde">
                        <img src="../assets/check-box.png" alt="Confirmar" class="h-6 w-6" />
                      </button>
                      <button v-if="reserva.estado === 'Pendiente' || showButton()" @click="cancelReserva(reserva)" class="p-0 rounded hover:bg-primary-verde">
                        <img src="../assets/cancel.png" alt="Cancelar" class="h-6 w-6" />
                      </button>
                      <button v-if="reserva.estado === 'Pendiente' || showButton()" @click="editReserva(reserva)" class="p-0 rounded hover:bg-primary-verde">
                        <img src="../assets/edit.png" alt="Editar" class="h-6 w-6" />
                      </button>
                      <button v-if="showButton()" @click="deleteReserva(reserva)"
                        class="p-0 rounded hover:bg-primary-verde">
                        <img src="../assets/delete.png" alt="Eliminar" class="h-6 w-6" />
                      </button>
                    </div>

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
      hasSearched: false,
      editingId: null, // Control de edición
      role: sessionStorage.getItem('role'),
      idFarm: sessionStorage.getItem('id_farm')

    };
  },
  mounted() {
    this.fetchAllReservas();
  },
  methods: {

    // Método para obtener todas las reservas
    async fetchAllReservas() {
      try {

        // Obtener todas los datos que necesitamos de la api
        const responseReservas = await apiClient.get('/reserva');
        const responseFarmacias = await apiClient.get('/farmacia');
        const role = sessionStorage.getItem('role');
        const idFarm = sessionStorage.getItem('id_farm')
        const responseProductos = await apiClient.get('/producto', {
          params: { role, id_farm: idFarm, source: 'producto' },
        });

        // Asignar los datos a las variables locales
        this.reservas = responseReservas.data.reservas;
        this.farmacias = responseFarmacias.data.farmacias;
        this.productos = responseProductos.data.productos;

        //Filtrar las reservas por farmacia
        if (role !== 'superadmin') {
          this.reservas = this.reservas.filter(reserva => 
          reserva.id_farm == idFarm || reserva.farm_origen == idFarm);
        }
       
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

    //Método para formatear la fecha
    formatFecha(fecha) {
      const [año, mes, día] = fecha.split('-');
      return `${día}/${mes}/${año}`;
    },

    // Método para confirmar una reserva
    async confirmReserva(reserva) {
      try {
        // Hacer la llamada PUT a la API para confirmar la reserva
        const response = await apiClient.patch(`/reserva?id=${reserva.id}`, { estado: 'Confirmada' });

        if (response.data.result === 'ok') {
          console.log('Reserva confirmada correctamente:', response.data.reserva);
          // Actualizar la lista de reservas después de la confirmación
          await this.fetchAllReservas();
        } else {
          console.error('Error al confirmar la reserva:', response.data);
        }

      } catch (error) {
        console.error('(catch)Error al confirmar la reserva:', error);
      }
    },

    // Método para cancelar una reserva
    async cancelReserva(reserva) {
      try {
        // Hacer la llamada PUT a la API para cancelar la reserva
        const response = await apiClient.patch(`/reserva?id=${reserva.id}`, { estado: 'Cancelada' });

        if (response.data.result === 'ok') {
          console.log('Reserva cancelada correctamente:', response.data.reserva);
          // Actualizar la lista de reservas después de la cancelación
          await this.fetchAllReservas();
        } else {
          console.error('Error al cancelar la reserva:', response.data);
        }

      } catch (error) {
        console.error('(catch)Error al cancelar la reserva:', error);
      }
    },

    // Método para editar una reserva
    async editReserva(reserva) {
      this.editingId = reserva.id;
      reserva.editedFecha = reserva.fecha;
      reserva.editedHoraInicio = reserva.hora_inicio;
      reserva.editedHoraFin = reserva.hora_fin;
      reserva.editedCantidad = reserva.cantidad;
      reserva.editedNombre = reserva.nombre;
      reserva.editedOtrosDatos = reserva.otros_datos;
    },

    // Método para saber si una reserva está siendo editada
    isEditing(id) {
      return this.editingId === id;
    },

    // Método para guardar los cambios de una reserva
    async saveReserva(reserva) {
      try {
        // Hacer la llamada PUT a la API para guardar los cambios de la reserva
        const response = await apiClient.put(`/reserva?id=${reserva.id}`, {
          fecha: reserva.editedFecha,
          hora_inicio: reserva.editedHoraInicio,
          hora_fin: reserva.editedHoraFin,
          cantidad: reserva.editedCantidad,
          nombre: reserva.editedNombre,
          otros_datos: reserva.editedOtrosDatos,
        });

        if (response.data.result === 'ok') {
          console.log('Reserva actualizada correctamente:', response.data.reserva);
          // Actualizar la lista de reservas después de la actualización
          await this.fetchAllReservas();
          this.editingId = null;
        } else {
          console.error('Error al actualizar la reserva:', response.data);
        }
      } catch (error) {
        console.error('Error al actualizar la reserva:', error);
      }
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

    // Método para mostrar el botón de eliminar
    showButton() {
      if (this.role == 'superadmin') {
        return true;
      }
      return false;
    },
  }
}

</script>

<style scoped>
.boton-claro-confirmar {
  background-color: white;
  color: #453C5C;

}

.boton-claro:hover {
  background-color: #31ADA1;
  color: white;
}

.boton-oscuro {
  background-color: #59D999;
  color: #453C5C;

}

.boton-oscuro:hover {
  background-color: darkred;
  color: white;
}
</style>