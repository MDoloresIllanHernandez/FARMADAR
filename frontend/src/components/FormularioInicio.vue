<template>
<form @submit.prevent="submitForm" class="space-y-4 md:space-y-6 " action="#">
    <div>
        <label for="username" class="block mb-2 text-sm font-medium text-primary-oscuro dark:text-white">Usuario</label>
        <input type="text" v-model="username" name="username" id="username" class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="usuario" required="">
    </div>
    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-primary-oscuro dark:text-white">Contraseña</label>
        <input type="password"  v-model="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 " required="">
    </div>
    <button type="submit" class="w-full rounded-md bg-primary-azul px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-violeta">Iniciar</button>
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
        const responseData = await authUser(this.username, this.password);
        // Aquí puedes manejar la respuesta, como mostrar un mensaje de éxito o redirigir
        console.log('Datos recibidos:', responseData);
        if(responseData.result == 'ok' && responseData.token){
          //Guardamos el token en localStorage
          localStorage.setItem('farmaToken', responseData.token);
          //Guardamos el rol en localStorage
          //localStorage.setItem('role', 'noadmin');
          this.$router.push('/inicio');
        }else{
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

<style>

</style>