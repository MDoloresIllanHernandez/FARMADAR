<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
        <h1 class="text-4xl py-4 font-bold sm:text-4xl text-primary-azul">Lista de reservas</h1>
        <div class="mt-6 flex gap-x-4 pb-8">
          <label for="reservas" class="sr-only">reservas</label>
          <input id="reservas" v-model="searchQuery" @keyup.enter="searchProducts" type="text"
            placeholder="Introduce el nombre de la reserva..."
            class="min-w-0 flex-auto p-2 border border-primary-oscuro rounded" />
          <button @click="searchProducts" class="boton-claro"> Buscar </button>
          <button @click="addProduct" class="boton-oscuro"> Añadir reserva </button>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>
<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
export default {
  components: {
    Navbar,
    Footer
  }
}
</script>
