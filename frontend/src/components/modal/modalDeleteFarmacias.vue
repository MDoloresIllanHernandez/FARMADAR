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
.modal-content {
  max-width: 400px;
}
</style>