<script setup>
import FormularioInicio from '../components/FormularioInicio.vue';
import Footer from '../components/Footer.vue';
import router from '../router';


//Aquí cogemos el token del sessionStorage. Y si está, hacemos que en Login vaya directamente a Inicio, sin 
//necesidad de volver a loguearse.
const token = sessionStorage.getItem('farmaToken')
//sessionStorage.removeItem('farmaToken')
if(token){
    router.push('/inicio')
}


</script>

<template>
  <section class="bg-gray-50">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
     <img class="p-10" src="../assets/logo1.png" alt="logo">
      <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-primary-oscuro md:text-2xl">
                  Iniciar sesión
              </h1>
             <FormularioInicio></FormularioInicio>
          </div>
      </div>
  </div>
 <Footer></Footer>
</section>
 
</template>

<style scoped>

</style>
