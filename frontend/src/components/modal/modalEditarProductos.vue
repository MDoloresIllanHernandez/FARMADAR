<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <div class="relative isolate lg:px-8">
        <div class="mx-auto max-w-6xl">
          <h1>Editar producto</h1>
          <GenericForm
            :fields="itemFields"
            :initialData="this.product"
            :dataSelect="dataSelect"
            submitButtonText="Actualizar Producto"
            cancelRoute="Productos"
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
    product: {
      type: Object,
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
          { name: "id", label: "Id producto", type: "number", error: "*Id requerido", readonly: true },
          { name: "nombre", label: "Nombre producto", type: "text", error: "*Nombre requerido" },
          { name: "precio", label: "Precio", type: "text", error: "*Precio requerido" },
          { name: "stock", label: "Stock", type: "number", error: "*Stock requerido" },
        ],
        dataSelect: [
          { name: "id_farm", label: "Farmacia", type: "select", error: "*Farmacia requerida", data:this.farmacias, readonly: true },
        ],
        requiredFields: ["id", "nombre", "precio", "stock", "id_farm"],
      };
  },
  methods: {
    errorForm(error) {
      console.log("error", error);
    },
    closeModal() {
      this.$emit('close'); // Emitir el evento para cerrar el modal
    },
    handleItemSubmit(formData) {
      // Este if es para evitar que lo envíe dos veces
      if(formData.type) {
        return 
      }
      let error = false;
      let errorField = "";
      this.requiredFields.forEach((field) => {
        if (formData[field] == null || (typeof(formData[field])!='number' && formData[field].trim() === "")) {
          error = true;
          errorField=field
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
