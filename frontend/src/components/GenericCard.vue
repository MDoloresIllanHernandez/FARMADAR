<template>
  <div class="bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ title }}</h2>
    <p class="text-gray-600">{{ detail1 }}</p>
    <p class="text-gray-600">{{ detail2 }}</p>
    <p class="text-gray-600">{{ detail3 }}</p>
    <p class="text-gray-600">{{ detail4 }}</p>

    <!-- Botones de acción -->
    <div class="flex gap-2 mt-4">
      <button v-if="showEdit()" @click="editItem" class="boton-claro"> Editar </button>
      <button v-if="showDelete()" @click="deleteItem" class="boton-oscuro"> Eliminar </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      role: sessionStorage.getItem('role'),
      idFarm: sessionStorage.getItem('id_farm'),
      id: sessionStorage.getItem('id')
    };
  },

  props: {
    calledFrom: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    detail1: {
      type: String,
      required: true
    },
    detail2: {
      type: String,
      required: true
    },
    detail3: {
      type: String,
      required: true
    },
    detail4: {
      type: String,
      required: true
    },
    data: {
      type: Object,
      required: false
    }
  },

  methods: {
    //Método para mostrar el botón de editar
    showEdit() {
      if (this.calledFrom == 'Farmacias' && this.role == 'usu') {
        return false;
      }
      if (this.calledFrom == 'Farmacias' && this.data?.id != this.idFarm) {
        if (this.role == 'superadmin') {
          return true;
        }
        return false;
      }
      if (this.calledFrom == 'Usuarios') {
        if (this.data?.id == this.id) {
          return false;
        }
        if (this.role == 'usu') {
          return false;
        }
        if (this.role == 'superadmin') {
          return true;
        }
      }
      return true;
    },

    //Método para mostrar el botón de eliminar
    showDelete() {
      if (this.calledFrom == 'Farmacias' && (this.role == 'usu' || this.role == 'admin')) {
        return false;
      }
      if (this.calledFrom == 'Usuarios') {
        if (this.data?.id == this.id) {
          return false;
        }
        if (this.role == 'usu') {
          return false;
        }
        if (this.role == 'superadmin') {
          return true;
        }
      }
      return true;
    },

    //Método para emitir el evento de edición
    editItem() {
      this.$emit('edit', this.data);
    },

    //Método para emitir el evento de eliminación
    deleteItem() {
      this.$emit('delete', this.data);
    }
  }
};
</script>

<style scoped>
.boton-oscuro:hover{
  background-color: darkred;
  color: white;
}
</style>