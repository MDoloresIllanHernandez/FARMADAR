<template>
    <form @submit.prevent="login" class="space-y-4 md:space-y-6">
      <div>
        <label for="username" class="block mb-2 text-sm font-medium text-primary-oscuro dark:text-white">Usuario</label>
        <input v-model="username" type="text" id="username" class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="usuario" required>
      </div>
      <div>
        <label for="password" class="block mb-2 text-sm font-medium text-primary-oscuro dark:text-white">Contraseña</label>
        <input v-model="password" type="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700" required>
      </div>
      <button type="submit" class="w-full rounded-md bg-primary-azul px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-violeta">Iniciar</button>
    </form>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      username: '',
      password: ''
    };
  },
  methods: {
    async login() {
      try {
        const data = {
          username: this.username,
          password: this.password,
        };

        // Llamada POST a auth.php
        const response = await axios.post('http://localhost/dwes/FARMADAR-1/backend/auth.php', data);

        //IMPORTANTE PARA LAS LLAMADAS:
        // Las llamadas POST (nuevo) cogen tres parámetros axios.post(url, data, headers)
        // Si es GET, axios.get(url, headers)     .... etc.

        // Si la respuesta es exitosa
        if (response.data.result === 'ok') {
          alert('Inicio de sesión correcto');

          // Redirige a la página de inicio
          this.$router.push('/inicio');

          console.log('token:', response.data.token);

          localStorage.setItem('farmaToken', response.data.token)


        } else {
          // Manejo de respuestas con result no esperado
          alert('Respuesta inesperada del servidor');
        }

      } catch (error) {
        // Verificar el código de respuesta HTTP
        if (error.response) {
          if (error.response.status === 403) {
            // Credenciales incorrectas
            alert('El usuario y/o la contraseña son incorrectas.');
          } else if (error.response.status === 400) {
            // Faltan campos
            alert(error.response.data.details); // Muestra el mensaje que viene del backend
          } else {
            // Otro tipo de error
            alert('Error durante la autenticación: ' + error.response.status);
          }
        } else {
          // Error de red o sin respuesta del servidor
          console.error('Error durante la autenticación:', error);
          alert('Error durante el inicio de sesión');
        }
      }
    }
  }
}


</script>


  <style>
  </style>
  