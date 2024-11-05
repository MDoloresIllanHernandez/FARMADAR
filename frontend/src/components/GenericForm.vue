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
          :max="field.max"
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
      <div v-for="field in dataSelect" :key="field.name" class="mb-4">
        <label :for="field.name" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>
  
        <select
        v-if="field.type === 'select' && field.data.length > 0"
        :id="field.name"
        :disabled="field.readonly?true:null"
        v-model="formData[field.name]"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"
        >
          <option v-for="option in field.data" :key="option.id" :value="option.id">
            {{ option.nombre }}
          </option>
        </select>
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
    },
    dataSelect: {
      type: Array,
      required: true,
    },
  },
  data() {
    const formData = {};
    this.fields.forEach(field => {
      formData[field.name] = this.initialData[field.name] || '';
    });

    this.dataSelect?.forEach(field => {
      const selectedOption = field.data?.find(option => option.selected);
      if (selectedOption) {
        formData[field.name] = selectedOption.id;
      } else {
        formData[field.name] = '';
      }
    })
  
    return {
      formData
    };
  },
  methods: {
    handleSubmit() {
      // Detener el envío si no hay valor en el campo de farmacia si se le ha pasado data
      if (this.dataSelect && this.dataSelect.length > 0 && !this.formData.id_farm) {
      console.log("No hay farmacia seleccionada");
      // Mostrar un mensaje de error en el formulario o similar
      this.$emit("error", "Por favor selecciona una farmacia.");
      return; // No emitir "submit" ni cerrar el modal
      }
      // Lógica para manejar el envío del formulario
      this.$emit("submit", this.formData);
    },
    handleCancel() {
      // Lógica para manejar la cancelación del formulario
      console.log("Formulario cancelado");
      // Emitir un evento de cancelación del modal
      this.$emit("cancel");
      //Redirigir a la ruta de cancelación en las vistas
      this.$router.push({ name: this.cancelRoute });
    }
  }
};
</script>
