<template>
    <div v-if="isVisible" class="modal-overlay">
      <div class="modal-content">
        <h2 class="text-lg font-bold mb-4">¿Estás seguro de que deseas eliminar esta farmacia?</h2>
        <p>Serán eliminados todos los <b>productos</b>, <b>reservas</b> y <b>usuarios</b> asociadas a la misma</p>
        <p>Esta acción no se puede deshacer.</p>
        
        <div class="flex justify-end gap-2 mt-6">
          <button @click="cancel" class="boton-claro-fantasma">Cancelar</button>
          <button @click="confirmDelete" class="boton-oscuro">Confirmar</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      isVisible: {
        type: Boolean,
        required: true,
      },
    },
    methods: {
      confirmDelete() {
        this.$emit('confirm'); // Emite un evento para confirmar la eliminación
      },
      cancel() {
        this.$emit('cancel'); // Emite un evento para cancelar la eliminación
      }
    }
  };
  </script>
  
  <style scoped>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
  }
  
  .modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 100%;
  }
  
  
  </style>
  