<template>
  <div class="bg-white">
    <Navbar />
    <div class="relative isolate px-6 pt-14 lg:px-8">
      <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
        <div class="text-center">
          <h1 class="text-4xl font-bold sm:text-6xl text-primary-oscuro">Bienvenido/a a</h1>
          <img class="p-10" src="../assets/farmadar.png" alt="logo">
          <p class="text-xl font-bold sm:text-xl text-primary-oscuro">Tu buscador de medicamentos de confianza</p>
          <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="/buscador-productos" class="boton-claro">Comenzar</a>
          </div>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script setup>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
</script>
