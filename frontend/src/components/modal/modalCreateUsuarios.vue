<template>
  <div v-if="isVisible" class="modal-overlay">
    <div class="modal-content">
      <div class="relative isolate lg:px-8">
        <div class="mx-auto max-w-6xl">
          <h1>Nuevo usuario</h1>
          <GenericForm
            :fields="itemFields"
            :dataSelect="dataSelect"
            submitButtonText="Guardar Usuario"
            cancelRoute="Usuarios"
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
        farmacias: [],
        itemFields: [
          { name: "nombre", label: "Nombre", type: "text", error: "*Nombre requerido" },
          { name: "username", label: "Username", type: "text", error: "*Username requerido" },
          { name: "password", label: "Password", type: "password", error: "*Password requerida" },
        ],
        dataSelect: [
        { name: "id_farm", label: "Farmacia", type: "select", error: "*Farmacia requerida", data:this.filterFarmacias(userRole, userFarm) },
        { name: "role", label: "Rol", type: "select", error: "*Rol requerido", data:this.filterRoles(userRole)},
        ],
        requiredFields: ["nombre", "username", "password", "role", "id_farm"],
      };
    },
  methods: {
    filterFarmacias(role, userFarm){
      if(role === 'admin'){
        return this.farmacias.filter(farmacia => farmacia.id === userFarm);
      }
      return this.farmacias;
    },
    filterRoles(role) {
      if(role === 'superadmin'){
        return [
          { id: 'admin', nombre: 'Administrador' },
          { id: 'usu', nombre: 'Usuario' },
        ];
      }
      return [
        { id: 'usu', nombre: 'Usuario' },
      ];
      //return [
      //  { id: 'admin', nombre: 'Administrador' },
      //  { id: 'usu', nombre: 'Usuario' },
      //];
    },
    errorForm(error) {
      console.log("error", error);
    },
    closeModal() {
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
        if (formData[field] == null || formData[field].trim() === "") {
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
