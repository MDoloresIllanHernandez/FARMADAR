<template>
    <div>
      <Navbar />
      <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
          <h1>Editar farmacia</h1>
          <GenericForm
            :fields="itemFields"
            :initialData="existingItemData"
            submitButtonText="Actualizar Farmacia"
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
          { name: "cif", label: "CIF", type: "text", error: "*CIF requerido" },
          { name: "nombre", label: "Nombre de la farmacia", type: "text", error: "*Nombre requerido" },
          { name: "direccion", label: "Dirección", type: "text", error: "*Dirección requerida" },
          { name: "telefono", label: "Teléfono", type: "tel", error: "*Teléfono requerido" },
          { name: "email", label: "Email", type: "mail", error: "*Email requerido" },
        ],
        existingItemData: {
          cif: "B12345678",
          nombre: "Farmacia de prueba",
          direccion: "Calle de la Farmacia, 123",
          telefono: "123456789",
          email: "prueba@prueba.com",
        }
      };
    },
    methods: {
      handleItemSubmit(formData) {
        // Lógica para manejar el envío del formulario de farmacia
        console.log("Farmacia enviado:", formData);
      }
    }
  };
  </script>