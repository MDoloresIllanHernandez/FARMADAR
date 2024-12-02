<template>
  <form @submit.prevent="submitForm" class="space-y-4 md:space-y-6" action="#">
    <div>
      <label for="username">Usuario</label>
      <input 
        type="text" 
        v-model="username" 
        name="username" 
        id="username" 
        placeholder="usuario" 
        required=""
        ref="usernameInput"
        class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
    </div>
    <div>
      <label for="password">Contraseña</label>
      <input 
        type="password" 
        v-model="password" 
        name="password" 
        id="password" 
        placeholder="••••••••" 
        required=""
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
  mounted() {
    // Establece el foco en el campo de usuario al montar el componente
    this.$refs.usernameInput.focus();
  },
  methods: {
    async submitForm() {
      try {
        // Envía los datos del formulario a la API
        const responseData = await authUser(this.username, this.password);

        if (
          responseData.result == "ok" &&
          responseData.token &&
          responseData.role &&
          responseData.id_farm &&
          responseData.id
        ) {
          // Guardar datos en sessionStorage
          sessionStorage.setItem("farmaToken", responseData.token);
          sessionStorage.setItem("role", responseData.role);
          sessionStorage.setItem("id_farm", responseData.id_farm);
          sessionStorage.setItem("id", responseData.id);

          this.$router.push("/inicio");
        } else {
          console.log("Error en la respuesta:", responseData);
        }
      } catch (error) {
        this.$swal.fire({
          icon: "error",
          title: "Usuario y/o contraseña incorrecta.",
          showConfirmButton: true,
        });
        console.error("Error al enviar el formulario:", error);
      }
    },
  },
};
</script>
