<script setup>
import Login from './views/Login.vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';


</script>

<template>
<div class="bg-white">
    <Navbar />

    <div class="relative isolate px-6 pt-14 lg:px-8">
      <router-view />
   </div>

    <Footer />

</div>
</template>

<style scoped>

</style>
