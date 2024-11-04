<template>
  <div>
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Nuevo usuario</h1>
        <GenericForm
          :fields="itemFields"
          submitButtonText="Guardar usuario"
          cancelRoute="Usuarios"
          @submit="handleItemSubmit"
        />
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import Navbar from '../components/Navbar.vue';
import Footer from '../components/Footer.vue';
import GenericForm from '../components/GenericForm.vue';

export default {
  components: { Navbar, Footer, GenericForm },
  data() {
    return {
      itemFields: [
        { name: "nombre", label: "Nombre", type: "text", error: "*Nombre requerido" },
        { name: "username", label: "Nombre de usuario", type: "text", error: "*Nombre de usuario requerido" },
        { name: "password", label: "Contraseña", type: "text", error: "*Contreseña requerida" },
        { name: "rol", label: "Rol", type: "text", error: "*Rol requerido" },
      ],
    };
  },
  methods: {
    handleItemSubmit(formData) {
      // Lógica para manejar el envío del formulario de usuario
      console.log("usuario enviado:", formData);
    }
  }
};
</script>