<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <div class="relative isolate lg:px-8">
        <div class="mx-auto max-w-6xl">
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
    const userRole = sessionStorage.getItem('role');
    const userFarm = sessionStorage.getItem('id_farm');
      return {
        role: userRole,
        itemFields: [
          { name: "id", label: "Id producto", type: "number", error: "*Id requerido"},
          { name: "producto", label: "Nombre", type: "text", error: "*Nombre requerido" },
          { name: "precio", label: "Precio", type: "text", error: "*Precio requerido" },
          { name: "stock", label: "Stock", type: "number", error: "*Stock requerido" },
        ],
        dataSelect: [
          { name: "id_farm", label: "Farmacia", type: "select", error: "*Farmacia requerida", data:this.filterFarmacias(userRole, userFarm) },
        ],
        requiredFields: ["id", "producto", "precio", "stock", "id_farm"],
      };
    },
  methods: {
    filterFarmacias(role, userFarm){
      if(role === 'admin' || role === 'usu'){
        return this.farmacias.filter(farmacia => farmacia.id === userFarm);
      }
      return this.farmacias;
    },
    errorForm(error) {
      this.$emit('errorForm', error)
    },
    closeModal() {
      console.log("cerrar modal");
      this.$emit('close'); // Emitir el evento para cerrar el modal
    },
    saveChanges(formData) {
      // Este if es para evitar que lo envíe dos veces
      if(formData.type){
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