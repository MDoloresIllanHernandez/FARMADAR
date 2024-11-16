<template>
  <div>
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Editar usuario</h1>
        <GenericForm
          :fields="itemFields"
          :initialData="existingItemData"
          submitButtonText="Actualizar usuario"
          cancelRoute="Usuarios"
          @submit="handleItemSubmit"
        />
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
import GenericForm from './../components/GenericForm.vue';

export default {
  components: { Navbar, Footer, GenericForm },
  data() {
    return {
      itemFields: [
        { name: "id", label: "Id usuario", type: "number", error: "*Id usuario", readonly: true },
        { name: "usuario", label: "Nombre usuario", type: "text", error: "*Nombre requerido" },
        { name: "username", label: "Username", type: "text", error: "*Username requerido" },
        { name: "id_farm", label: "Farmacia", type: "number", error: "*Id de la farmacia requerido" },
      ],
      existingItemData: {
        id: 1,
        usuario: "Usuario de prueba",
        farmacia: "Usuario de prueba",
        id_farm: 1
      }
    };
  },
  methods: {
    handleItemSubmit(formData) {
      // Lógica para manejar el envío del formulario de usuario
      console.log("Usuario enviado:", formData);
    }
  }
};
</script>