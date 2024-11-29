<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <div class="relative isolate lg:px-8">
        <div class="mx-auto max-w-6xl">
          <h1>Editar usuario</h1>
          <GenericForm
            :fields="itemFields"
            :initialData="this.user"
            :dataSelect="dataSelect"
            submitButtonText="Actualizar Usuario"
            cancelRoute="Usuarios"
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
    user: {
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
        role: sessionStorage.getItem('role'),
        roles: ['superadmin', 'admin', 'usu'],
        itemFields: [
          { name: "id", label: "Id usuario", type: "number", error: "*Id requerido", readonly: true },
          { name: "nombre", label: "Nombre usuario", type: "text", error: "*Nombre requerido" },
          { name: "username", label: "Username", type: "text", error: "*Username requerido"},
          { name: "role", label: "Rol", type: "text", error: "*Rol requerido",readonly:this.role=='superadmin'?false:true },
        ],
        dataSelect: [
          { name: "id_farm", label: "Farmacia", type: "select", error: "*Farmacia requerida", data:this.farmacias, readonly:this.role=='superadmin'?false:true },
        ],
      };
  },
  methods: {
    closeModal() {
      this.$emit('close'); // Emitir el evento para cerrar el modal
    },
    handleItemSubmit(formData) {
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
  max-height: 100%; /* Limita el alto al 80% del viewport */
  overflow-y: auto; /* Habilita el scroll si el contenido supera el alto */
  display: flex;
  flex-direction: column; /* Asegura que el contenido respete la estructura */
  justify-content: space-between; /* Distribuye los elementos adecuadamente */
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
