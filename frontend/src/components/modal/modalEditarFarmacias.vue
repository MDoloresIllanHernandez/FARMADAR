<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <div class="relative isolate lg:px-8">
        <div class="mx-auto max-w-6xl">
          <h1>Editar farmacia</h1>
          <GenericForm
            :fields="itemFields"
            :initialData="this.farmacia"
            :dataSelect="dataSelect"
            submitButtonText="Actualizar Farmacia"
            cancelRoute="Farmacias"
            @submit="handleItemSubmit"
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
    farmacia: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      itemFields: [
        { name: "cif", label: "CIF", type: "text", readonly: true, error: "*CIF requerido" },
        { name: "nombre", label: "Nombre", type: "text", error: "*Nombre requerido" },
        { name: "direccion", label: "Dirección", type: "text", error: "*Dirección requerida" },
        { name: "telefono", label: "Teléfono", type: "tel", error: "*Teléfono requerido" },
        { name: "email", label: "Email", type: "email", error: "*Email requerido" },
      ],
      dataSelect: [],
      requiredFields: ["cif", "nombre", "direccion", "telefono", "email"],
    };
  },
  methods: {
    // Método para manejar errores en el formulario
    errorForm(error) {
      console.log("error", error);
    },
    // Método para cerrar el modal
    closeModal() {
      this.$emit('close'); // Emitir el evento para cerrar el modal
    },
    handleItemSubmit(formData) {
      // Este if es para evitar que lo envíe dos veces
      if (formData.type) {
        return
      }
      let error = false;
      let errorField = "";
      this.requiredFields.forEach((field) => {
        if (formData[field] == null || formData[field].trim() === "") {
          error = true;
          errorField = field;
        }
      });
      if (error) {
        this.$emit('errorForm', errorField)
      } else {
        this.$emit('save', formData); // Emitir el evento para guardar cambios
      }
    },
  },
};
</script>