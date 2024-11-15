<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
          <h1>Nuevo producto</h1>
          <GenericForm
            :fields="itemFields"
            :dataSelect="dataSelect"
            submitButtonText="Guardar Producto"
            cancelRoute="Productos"
            @submit="saveChanges"
            @cancel="closeModal" 
            @error="errorForm"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GenericForm from '../GenericForm.vue';

export default {
  components: { GenericForm },

  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
    farmacias: {
      type: Array,
      required: true,
    },
  },
  data() {
      return {
        itemFields: [
          { name: "id", label: "Id producto", type: "number", error: "*Id requerido"},
          { name: "producto", label: "Nombre", type: "text", error: "*Nombre requerido" },
          { name: "precio", label: "Precio", type: "text", error: "*Precio requerida" },
          { name: "stock", label: "Stock", type: "number", error: "*Stock requerido" },
        ],
        dataSelect: [
          { name: "id_farm", label: "Farmacia", type: "select", error: "*Farmacia requerida", data:this.farmacias },
        ],
      };
    },
  methods: {
    errorForm(error) {
      console.log("error", error);
    },
    closeModal() {
      console.log("cerrar modal");
      this.$emit('close'); // Emitir el evento para cerrar el modal
    },
    saveChanges(formData) {
      if (formData.id) {
        this.$emit('save', formData); // Emitir el evento para guardar cambios
      }
    },
  },
};
</script>

<style>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000; /* Asegura que esté encima de otros elementos */
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
  max-width: 600px;
  width: 100%;
  position: relative;
}

.close-button {
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 20px;
  position: absolute; /* Cambia a posición absoluta */
  right: 10px; /* Posición desde la derecha */
  top: 10px; /* Posición desde la parte superior */
}
</style>
