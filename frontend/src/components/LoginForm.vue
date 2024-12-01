<template>
  <div ref="map" style="width: 100%; height: 500px;"></div>
  <form @submit.prevent="submitForm" class="space-y-4 md:space-y-6 " action="#">
    <div>
      <label for="username">Usuario</label>
      <input type="text" v-model="username" name="username" id="username" placeholder="usuario" required=""
        class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
    </div>
    <div>
      <label for="password">Contraseña</label>
      <input type="password" v-model="password" name="password" id="password" placeholder="••••••••" required=""
        class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
    </div>
    <button type="submit" class="w-full boton-claro">Iniciar</button>
  </form>
</template>

<script>
// Importa la lógica del formulario desde el archivo externo
import { authUser } from '../scripts/auth.js';
import maplibregl from "maplibre-gl";
import "maplibre-gl/dist/maplibre-gl.css";
export default {
  mounted() {
    // Inicializa el mapa
    const map = new maplibregl.Map({
      container: this.$refs.map, // Div donde se renderizará el mapa
      style: "https://basemaps.cartocdn.com/gl/voyager-gl-style/style.json", // URL del estilo compatible con OpenStreetMap
      center: [-3.7038, 40.4168], // Coordenadas de la Puerta del Sol, Madrid
      zoom: 15, // Nivel de zoom para ver detalles de la calle
    });

    // Añade un marcador en el centro del mapa
    new maplibregl.Marker()
      .setLngLat([-3.7038, 40.4168]) // Coordenadas del marcador
      .addTo(map);
      // Añade los controles de navegación (+, -, rotación)
    map.addControl(new maplibregl.NavigationControl(), "top-right"); // Posición: esquina superior derecha
  
  },
  data() {
    return {
      username: '',
      password: ''
    };
  },
  methods: {
    // Llama a la función que está en el archivo externo axios
    async submitForm() {
      try {
        // Envía los datos del formulario a la API
        const responseData = await authUser(this.username, this.password);
        // Manejamos la respuesta con un console.log y un if
        if (responseData.result == 'ok' && responseData.token && responseData.role && responseData.id_farm && responseData.id 
        ) {
          console.log('Datos recibidos:', responseData);
          //Guardamos el token en sessionStorage
          sessionStorage.setItem('farmaToken', responseData.token);

          sessionStorage.setItem('role', responseData.role);

          sessionStorage.setItem('id_farm', responseData.id_farm);

          sessionStorage.setItem('id', responseData.id);


          this.$router.push('/inicio');
        } else {
          // Aquí puedes manejar el error, como mostrar un mensaje de error
          console.log('Error en la respuesta:', responseData);
        }

      } catch (error) {
        console.error('Error al enviar el formulario:', error);
      }
    }
  }
};
</script>
