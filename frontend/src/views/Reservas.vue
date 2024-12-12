<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Reservas</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <input id="productos" v-model="searchQuery" @input="searchReservas" type="text" ref="searchInput"
            placeholder="Introduce el nombre del producto o del cliente..." />
          <button @click="searchReservas" class="boton-claro"> Buscar</button>
        </div>
        <div v-if="hasSearched">
          <div v-if="reservas.length" class="overflow-x-auto">
            <table class="min-w-80 divide-y divide-gray-200 ">
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
                <tr v-for="reserva in paginateReservas" :key="reserva.id"
                  :class="{ 'bg-primary-turquesa': isEditing(reserva.id) }">
                  <td><span>{{ getFarmaciaName(reserva.farm_origen) }}</span></td>
                  <td><span>{{ getFarmaciaName(reserva.id_farm) }}</span></td>
                  <td><span>{{ getProductoName(reserva.id_prod) }}</span></td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="currentReserva.fecha" type="date" class="w-full" />
                    <span v-else>{{ formatFecha(reserva.fecha) }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="currentReserva.hora_inicio" type="time"
                      class="w-full" />
                    <span v-else>{{ reserva.hora_inicio }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="currentReserva.hora_fin" type="time" class="w-full" />
                    <span v-else>{{ reserva.hora_fin }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="currentReserva.cantidad" type="number"
                      class="w-full" />
                    <span v-else>{{ reserva.cantidad }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="currentReserva.nombre" type="text" class="w-full" />
                    <span v-else>{{ reserva.nombre }}</span>
                  </td>
                  <td>
                    <input v-if="isEditing(reserva.id)" v-model="currentReserva.otros_datos" type="text"
                      class="w-full" />
                    <span v-else>{{ reserva.otros_datos }}</span>
                  </td>
                  <td><span>{{ reserva.estado }}</span></td>
                  <td>
                    <div v-if="isEditing(reserva.id)">
                      <button @click="saveReserva" class="boton-claro boton-claro-confirmar">
                        Confirmar
                      </button>
                      <button @click="editingId = null" class="boton-oscuro">
                        Cancelar
                      </button>
                    </div>
                    <div v-else>
                      <button v-if="reserva.farm_origen !== idFarm && (reserva.estado === 'Pendiente' || showButton())" @click="confirmReserva(reserva)"
                        class="p-0 rounded hover:bg-primary-verde">
                        <img src="../assets/check.png" alt="Confirmar" class="h-6 w-6" title="Confirmar reserva" />
                      </button>
                      <button v-if="reserva.estado === 'Pendiente' || showButton()" @click="cancelReserva(reserva)"
                        class="p-0 rounded hover:bg-primary-darkred">
                        <img src="../assets/cancel.png" alt="Cancelar" class="h-6 w-6" title="Cancelar reserva" />
                      </button>
                      <button v-if="reserva.estado === 'Pendiente' || showButton()" @click="editReserva(reserva)"
                        class="p-0 rounded hover:bg-primary-verde">
                        <img src="../assets/edit.png" alt="Editar" class="h-6 w-6" title="Editar reserva" />
                      </button>
                      <button v-if="showButton()" @click="deleteReserva(reserva)"
                        class="p-0 rounded hover:bg-primary-darkred">
                        <img src="../assets/delete.png" alt="Eliminar" class="h-6 w-6" title="Eliminar reserva" />
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
          <!-- Controles de paginación -->
          <div class="flex justify-around items-center mt-4">
            <button @click="prevPage" :disabled="currentPage === 1" class="boton-claro-fantasma">
              Anterior
            </button>
            <span>Página {{ currentPage }} de {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="boton-claro-fantasma">
              Siguiente
            </button>
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
      intervalId: null, // ID del intervalo para verificar las reservas expiradas
      searchQuery: '',
      reservas: [],
      farmacias: [],
      productos: [],
      hasSearched: false,
      editingId: null, // Control de edición, null si no se está editando
      currentReserva: {
        id: null,
        fecha: '',
        hora_inicio: '',
        hora_fin: '',
        cantidad: 0,
        nombre: '',
        otros_datos: '',
      }, // Datos de la reserva actualmente en edición
      role: sessionStorage.getItem('role'),
      idFarm: sessionStorage.getItem('id_farm'),
      currentPage: 1,
      itemsPerPage: 5,
    };
  },
  // Método para cargar las reservas y verificar las expiradas al montar el componente
  async mounted() {
    await this.fetchAllReservas();
    await this.checkExpiredReservas();
    // Crear un intervalo para verificar las reservas expiradas cada minuto
    this.intervalId = setInterval(async () => {
      await this.checkExpiredReservas();
    }, 60000);
    this.$refs.searchInput.focus();
  },
  beforeUnmount() {
    // Limpiar intervalo
    clearInterval(this.intervalId);
  },
  computed: {
    paginateReservas() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.reservas.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.reservas.length / this.itemsPerPage);
    }
  },
  methods: {
    // Método para ir a la página siguiente
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    // Método para ir a la página anterior
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },

    //Método para verificar si hay reservas pendientes y cancelarlas si ha pasado la fecha de fin
    async checkExpiredReservas() {
      console.warn('Verificando reservas pendientes expiradas...');
      
      // Obtener la fecha y hora actual
      const now = new Date();
      let reservasExpiradas=0
      for (let reserva of this.reservas) {
        // Construir la fecha y hora fin completa
        const fechaHoraFin = new Date(`${reserva.fecha}T${reserva.hora_fin}`);

        // Verificar si la reserva está vencida y pendiente
        if (reserva.estado?.toLowerCase() === "pendiente" && fechaHoraFin < now) {
          try {
            // Cambiar estado a "Cancelada" en la base de datos
            const response = await apiClient.patch(`/reserva?id=${reserva.id}`, { estado: "Cancelada" });

            if (response.data.result === "ok") {
              reservasExpiradas++
              // Actualizar el stock del producto
              await this.updateStock(reserva.id_prod, reserva.id_farm, reserva.cantidad);

            } else {
              console.error(`Error al cancelar la reserva ${reserva.id}:`, response.data.message);
            }
          } catch (error) {
            console.error(`Error al procesar la reserva ${reserva.id}:`, error);
          }
        }
      }
      if (reservasExpiradas>0) {
        // Refrescar la lista de reservas para reflejar los cambios
        await this.fetchAllReservas();
      }
    },

    // Método para actualizar el stock de un producto en una farmacia
    async updateStock (productId, pharmacyId, quantityToAdd) {
      try {
        // Obtener el stock actual
        const stockResponse = await apiClient.get(`/stock?id=${productId}&id_farm=${pharmacyId}`);

        if (stockResponse.data.result === "ok") {
          const nuevoStock = parseInt(stockResponse.data.stock) + parseInt(quantityToAdd);

          // Actualizar el stock con el nuevo valor
          const updateStockResponse = await apiClient.put(`/stock`, {
            id: productId,
            id_farm: pharmacyId,
            newStock: nuevoStock,
          });

          if (updateStockResponse.data.result === "ok") {
            return true; // Devolver verdadero si la actualización fue exitosa
          } else {
            console.error(`Error al actualizar el stock del producto ${productId}:`, updateStockResponse.data.message);
            return false;
          }
        } else {
          console.error (`Error al obtener el stock del producto ${productId}:`, stockResponse.data.message);
          return false;
        }
      } catch (error) {
        console.error (`Error al actualizar el stock para el producto ${productId} en la farmacia ${pharmacyId}:`, error);
        return false;
      }
    },

    // Método para obtener todas las reservas
    async fetchAllReservas () {
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
   async searchReservas() {
      if (this.searchQuery.trim() === '') {
        await this.fetchAllReservas(); // Si no hay búsqueda, cargar todas las reservas
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
      if (!fecha || typeof fecha !== 'string') {
        return 'Fecha no válida'; // Devuelve un mensaje por defecto si el valor es inválido
      }
      const [año, mes, día] = fecha.split('-');
      return `${día}/${mes}/${año}`;
    },

    // Método para confirmar una reserva
    async confirmReserva(reserva) {
      try {
        // Hacer la llamada PATCH a la API para confirmar la reserva
        const response = await apiClient.patch(`/reserva?id=${reserva.id}`, { estado: 'Confirmada' });

        if (response.data.result === 'ok') {
          this.$swal.fire({
            icon: 'success',
            title: 'Reserva confirmada',
            showConfirmButton: false,
            timer: 2000,
          });
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
        // Cancelar la reserva
        const cancelResponse = await apiClient.patch(`/reserva?id=${reserva.id}`, { estado: 'Cancelada' });

        if (cancelResponse.data.result === 'ok') {
          // Llamar al método updateStock para actualizar el stock
          const stockActualizado = await this.updateStock(reserva.id_prod, reserva.id_farm, reserva.cantidad);

          if (stockActualizado) {
            this.$swal.fire({
              icon: 'success',
              title: 'Reserva cancelada y stock actualizado',
              showConfirmButton: false,
              timer: 2000,
            });
          } else {
            console.error('Error al actualizar el stock');
          }
          // Refrescar la lista de reservas
          await this.fetchAllReservas();
        } else {
          console.error('Error al cancelar la reserva:', cancelResponse.data.message);
        }
      } catch (error) {
        console.error('Error al cancelar la reserva y/o actualizar el stock:', error);
      }
    },

    // Método para editar una reserva
    async editReserva(reserva) {
      this.editingId = reserva.id;
      this.currentReserva = {
        id: reserva.id,
        fecha: reserva.fecha,
        hora_inicio: reserva.hora_inicio,
        hora_fin: reserva.hora_fin,
        cantidad: reserva.cantidad,
        nombre: reserva.nombre,
        otros_datos: reserva.otros_datos,
      };
    },

    // Método para saber si una reserva está siendo editada
    isEditing(id) {
      return this.editingId === id;
    },

    // Método para guardar los cambios de una reserva
    async saveReserva() {
      // if (!this.currentReserva.fecha || !this.currentReserva.hora_inicio || !this.currentReserva.hora_fin || !this.currentReserva.cantidad || !this.currentReserva.nombre) {
      //   this.$swal.fire({
      //     icon: 'error',
      //     title: 'Por favor, rellene todos los campos',
      //     showConfirmButton: true,
      //   });
      //   return;
      // }
      try {
        // Hacer la llamada PUT a la API para guardar los cambios de la reserva
        const response = await apiClient.put(`/reserva?id=${this.currentReserva.id}`, {
          fecha: this.currentReserva.fecha,
          hora_inicio: this.currentReserva.hora_inicio,
          hora_fin: this.currentReserva.hora_fin,
          cantidad: this.currentReserva.cantidad,
          nombre: this.currentReserva.nombre,
          otros_datos: this.currentReserva.otros_datos,
        });
        if (response.data.result === 'ok') {
          // Mostrar un mensaje de éxito
          this.$swal.fire({
            icon: 'success',
            title: 'Reserva actualizada correctamente',
            showConfirmButton: false,
            timer: 2000,
          });
          //// Actualiza localmente si la operación fue exitosa
          const index = this.reservas.findIndex(r => r.id === this.currentReserva.id);
          if (index !== -1) {
            this.reservas[index] = { ...response.data }; // Usa la respuesta del servidor
            this.editingId = null;
            this.currentReserva = {};
          }

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
          this.$swal.fire({
            icon: 'success',
            title: 'Reserva eliminada correctamente',
            showConfirmButton: false,
            timer: 1500,
          });

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

.boton-claro-confirmar:hover {
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