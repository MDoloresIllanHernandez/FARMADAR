<template>
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

export default {
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
        console.log('Datos recibidos:', responseData);
        if (responseData.result == 'ok' && responseData.token && responseData.role) {
          //Guardamos el token en localStorage
          sessionStorage.setItem('farmaToken', responseData.token);

          sessionStorage.setItem('role', responseData.role);

          //Guardamos el rol en localStorage
          //localStorage.setItem('role', 'noadmin');
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
