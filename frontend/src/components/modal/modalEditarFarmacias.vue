<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
          <h1>Editar farmacia</h1>
          <GenericForm
            :fields="itemFields"
            :initialData="this.farmacia"
            :dataSelect="dataSelect"
            submitButtonText="Actualizar Farmacia"
            cancelRoute="Farmacias"
            @submit="handleItemSubmit"
            @cancel="closeModal"
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
    farmacia: {
      type: Object,
      required: true,
    },
  },

  data() {
      return {
        itemFields: [
          { name: "cif", label: "CIF", type: "text", error: "*CIF requerido" },
          { name: "nombre", label: "Nombre", type: "text", error: "*Nombre requerido" },
          { name: "direccion", label: "Dirección", type: "text", error: "*Dirección requerida" },
          { name: "telefono", label: "Teléfono", type: "tel", error: "*Teléfono requerido" },
          { name: "email", label: "Email", type: "mail", error: "*Email requerido" },
        ],
        dataSelect: [
          
        ],
      };
  },
  methods: {
    closeModal() {
      this.$emit('close'); // Emitir el evento para cerrar el modal
    },
    handleItemSubmit(formData) {
      if (formData.cif) {
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
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 100%;
  position: relative; /* Asegúrate de que el contenido del modal esté posicionado */
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
