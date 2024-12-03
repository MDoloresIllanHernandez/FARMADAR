<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <div class="flex items-center space-x-4">
          <h1>Buscador FARMADAR</h1>
          <img class="h-8 w-auto" src="/icono.png" alt="Icono FARMADAR" />
        </div>
        <p class="text-xl sm:text-xl text-primary-oscuro">¿Qué productos deseas reservar?</p>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="productos" class="sr-only">Productos</label>
          <input id="productos" v-model="searchQuery" @keyup.enter="searchProducts" type="text" ref="searchInput"
            placeholder="Introduce el nombre del producto..." />
          <button @click="searchProducts" class="boton-claro"> Buscar </button>
        </div>
        <div v-if="loading" class="loading-overlay">
          <div class="spinner"></div>
        </div>
        <div class="d-flex" style="display: flex; justify-content: space-between; align-items: flex-start;">
          <div v-if="hasSearched" style="flex: 1; margin-right: 20px;">
          <div v-if="products.length">
            <CardReservas v-for="product in products" 
              :key="product.id" 
              :product="product" 
            />
          </div>
          <div v-else>
            <p>{{ noProductMessage || 'No se encontró ese producto.' }}</p>
          </div>
          </div>
          <div v-if="showMap" class="map-container" style="flex: 1;">
            <div ref="map"  style="width: 100%; height: 500px;"></div>
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
import CardReservas from '../components/CardReservas.vue';
import apiClient from '../scripts/axios.js';
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";

export default {
  components: { Navbar, Footer, CardReservas },
 
  data() {
    return {
      noProductMessage: null,
      map: null,
      searchQuery: '',
      products: [],
      farmacias: [],
      coordenadas: [],
      hasSearched: false,
      loading: false,
      showMap: false,
      role: sessionStorage.getItem('role'),
      idFarm: sessionStorage.getItem('id_farm')
    };
  },

  mounted() {
    //Poner el foco en el input de búsqueda al cargar la página
    this.$refs.searchInput.focus();
    
  },

  methods: {
    // Método para obtener las coordenadas usando la API de geocodificación de Nominatim (OpenStreetMap)
    async getCoordenadas(address) {
      const encodedAddress = encodeURIComponent(address);
      try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodedAddress}`);
        const data = await response.json();
        if (data && data[0]) {
          return [parseFloat(data[0].lon), parseFloat(data[0].lat)];
        } else {
          console.error("No se pudieron obtener las coordenadas para la dirección:", address);
          return null;
        }
      } catch (error) {
        console.error("Error al obtener las coordenadas:", error);
        return null;
      }
    },


    // Método para inicializar el mapa
    async initMap() {
      const userRole = this.role;
      let coords;

      if (userRole === 'superadmin') {
        // Mostrar Murcia Centro para superUsuario
        coords = [-1.1307, 37.9835]; // Coordenadas de Murcia Centro
      } else {
        // Obtener la dirección de la farmacia asociada al usuario
        const farmacia = this.farmacias.find(f => f.id === this.idFarm);
        if (farmacia) {
          coords = await this.getCoordenadas(farmacia.direccion);
        }
        if (!coords) {
          // Si no se encuentra dirección, usar Murcia Centro como fallback
          coords = [-1.1307, 37.9835];
        }
      }

      this.map = new maplibregl.Map({
        container: this.$refs.map,
        style: "https://basemaps.cartocdn.com/gl/voyager-gl-style/style.json",
        center: coords,
        zoom: 14
      });

      // Añadir los controles de navegación
      this.map.addControl(new maplibregl.NavigationControl(), "top-right"); // Controles en la esquina superior derecha

      // Añadir marcador central según el rol
      new maplibregl.Marker({
        color: 'red'
      })
        .setLngLat(coords)
        .setPopup(new maplibregl.Popup().setHTML(`<h3>${userRole === 'superadmin' ? 'Murcia Centro' : 'Mi Farmacia'}</h3>`))
        .addTo(this.map);
    },


    // Método para mostrar el mapa
    async showMapHandler() {
      this.showMap = true;

      // Obtener farmacias si aún no se han cargado
      if (!this.farmacias.length) {
        try {
          const pharmaciesResponse = await apiClient.get('/farmacia');
          if (pharmaciesResponse.data.result === 'ok' && pharmaciesResponse.data.farmacias) {
            this.farmacias = pharmaciesResponse.data.farmacias;
          }
        } catch (error) {
          console.error("Error al cargar farmacias:", error);
        }
      }

      // Inicializar el mapa después de cargar las farmacias
      this.$nextTick(async () => {
        await this.initMap();
      });
    },

    // Método para buscar productos
    async searchProducts() {
      this.loading = true;
      this.products = []; // Limpiar productos
      try {
        // Consultar productos
        const productsResponse = await apiClient.get('/buscadorProductos');
        if (productsResponse.data.result === 'ok' && productsResponse.data.productos) {
          this.products = productsResponse.data.productos.filter(product =>
            product.nombre.toLowerCase().includes(this.searchQuery.toLowerCase()) 
          );
        //Comprobamos si hay productos con stock 0
        const productosConStockCero = this.products.filter(product => product.stock == '0');
        // Si no existe el producto buscado
        if(this.products.length == 0) {
          this.noProductMessage = null;
        }
        // Si hay productos con stock 0
        if(this.products.length != 0 && this.products.length == productosConStockCero.length) {
          this.noProductMessage = 'No hay stock disponible en ninguna farmacia';
          this.products = [];
        }
          // Consultar farmacias
          const pharmaciesResponse = await apiClient.get('/farmacia');
          if (pharmaciesResponse.data.result === 'ok' && pharmaciesResponse.data.farmacias) {
            const farmacias = pharmaciesResponse.data.farmacias;

            //Recojo el id de la farmacia del sessionStorage
            const idFarm = sessionStorage.getItem('id_farm');
            const userRole = sessionStorage.getItem('role');

            // Asociar el nombre de la farmacia al producto correspondiente
            let farmaciasProducto = [];
            this.products = this.products.map(product => {
              const farmacia = farmacias.find(f => f.id === product.id_farm);
              farmaciasProducto.push(farmacia);
              return {
                ...product,
                nombre_farmacia: farmacia ? farmacia.nombre : 'Desconocido'
              };
            })
            // Filtrar productos por farmacia si el usuario es distinto de superadmin
            if (userRole !== 'superadmin') {
              this.products = this.products.filter(product => product.id_farm !== idFarm);
            }
            // Quitar duplicados 
            farmaciasProducto = Array.from(
              new Set(farmaciasProducto.map((farmacia) => farmacia.cif))
            ).map((cif) => {
              return farmaciasProducto.find((farmacia) => farmacia.cif === cif);
            });
            let farmaciasApintar = [];
            if (farmaciasProducto.length) {
              farmaciasApintar = farmaciasProducto;
            } else {
              farmaciasApintar = farmacias;
            }
            await this.showMapHandler();
            // Añadir los marcadores para las farmacias
            for (const farmacia of farmaciasApintar) {
                  const coords = await this.getCoordenadas(farmacia.direccion);
                  if (coords) {
                    new maplibregl.Marker({
                      color: farmacia.id == this.idFarm ? 'red' : '#3FB1CE'
                    })
                      .setLngLat(coords)
                      .setPopup(new maplibregl.Popup().setHTML(`<h3>${farmacia.nombre}</h3><p>${farmacia.direccion}</p>`))
                      .addTo(this.map);
                  }
                }     
          }
          this.hasSearched = true;
        }
      } catch (error) {
        console.error('Error al obtener los productos o farmacias:', error);
      } finally {
        this.loading = false; // Stop loading
      }
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
.map-container {
  position: sticky; /* El contenedor será sticky */
  top: 0; /* Se pegará en la parte superior */
  height: 100vh; /* Ocupa el 100% de la altura del viewport */
  width: 100%; /* Ocupa el 100% del ancho del contenedor */
}
/* Overlay styles */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  /* Slight overlay background */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  /* Ensure it’s above other elements */
}
</style>
