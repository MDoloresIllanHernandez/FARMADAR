<template>
  <div class="max-w-lg mx-auto p-6 bg-white rounded-md shadow-md">
    <form @submit.prevent="handleSubmit">
      <div v-for="field in fields" :key="field.name" class="mb-4">
        <label :for="field.name" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>
        <input
          v-if="field.type !== 'textarea'"
          :id="field.name"
          :type="field.type"
          v-model="formData[field.name]"
          :readonly="field.readonly"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"
        />
        <textarea
          v-else-if="field.type === 'textarea'"
          :id="field.name"
          v-model="formData[field.name]"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"
        ></textarea>
        <span v-if="field.error && !formData[field.name]" class="text-red-600 text-sm">
          {{ field.error }}
        </span>
      </div>
      <div class="flex justify-end gap-2">
        <button type="submit" class="boton-claro">{{ submitButtonText }}</button>
        <button type="button" @click="handleCancel" class="boton-oscuro">Cancelar</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    fields: {
      type: Array,
      required: true
    },
    submitButtonText: {
      type: String,
      default: "Enviar"
    },
    initialData: {
      type: Object,
      default: () => ({})
    },
    cancelRoute: {
      type: String,
      required: true
    }
  },
  data() {
    const formData = {};
    this.fields.forEach(field => {
      formData[field.name] = this.initialData[field.name] || '';
    });
    return {
      formData
    };
  },
  methods: {
    handleSubmit() {
      // Lógica para manejar el envío del formulario
      console.log("Formulario enviado:", this.formData);
      this.$emit("submit", this.formData);
    },
    handleCancel() {
      // Lógica para manejar la cancelación del formulario
      console.log("Formulario cancelado");
      this.$router.push({ name: this.cancelRoute });
    }
  }
};
</script>

<style scoped>
/* Opcional: agrega estilos personalizados */
</style>