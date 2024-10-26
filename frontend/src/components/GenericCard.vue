<template>
  <div class="bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ title }}</h2>
    <p class="text-gray-600">{{ detail1 }}</p>
    <p class="text-gray-600">{{ detail2 }}</p>
    <p class="text-gray-600">{{ detail3 }}</p>
    <p class="text-gray-600">{{ detail4 }}</p>

    <!-- Botones de acción -->
    <div class="flex justify-end gap-2 mt-4">
      <button @click="editItem" class="bg-primary-azul text-white font-semibold py-2 px-4 rounded hover:bg-gray-500 transition-colors">
        Editar
      </button>
      <button @click="deleteItem" class="bg-red-500 text-white font-semibold py-2 px-4 rounded hover:bg-red-600 transition-colors">
        Eliminar
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
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
      required: true
    }
  },
  methods: {
    editItem() {
      this.$emit('edit', this.data);
    },
    deleteItem() {
      this.$emit('delete', this.data);
    }
  }
};
</script>