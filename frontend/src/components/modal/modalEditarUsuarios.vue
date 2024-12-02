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
    // Método para cerrar el modal
    closeModal() {
      this.$emit('close'); // Emitir el evento para cerrar el modal
    },
    // Método para guardar los cambios en el usuario
    handleItemSubmit(formData) {
      if (formData.id) {
        this.$emit('save', formData); // Emitir el evento para guardar los cambios
      }
    },
  },
};
</script>