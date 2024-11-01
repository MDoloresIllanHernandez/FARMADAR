<template>
  <div>
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1>Nuevo producto</h1>
        <GenericForm
          :fields="itemFields"
          submitButtonText="Guardar Producto"
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
        { name: "id", label: "Id del producto", type: "number", error: "*Id requerido" },
        { name: "nombre", label: "Nombre del producto", type: "text", error: "*Nombre requerido" },
        { name: "precio", label: "Precio", type: "number", error: "*Fecha requerida" },
        { name: "stock", label: "Stock", type: "number", error: "*Stock requerido" },
        { name: "id_farm", label: "Farmacia", type: "number", error: "*Id de la farmacia requerido" },
      ],
    };
  },
  methods: {
    handleItemSubmit(formData) {
      // Lógica para manejar el envío del formulario de producto
      console.log("Producto enviado:", formData);
    }
  }
};
</script>