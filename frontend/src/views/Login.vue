<template>
    <section class="bg-gray-100">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <img class="p-6 w-4/5 h-auto"  src="../assets/farmadar.png" alt="logo">
            <div class="w-96 bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-primary-oscuro md:text-2xl">
                        Iniciar sesión
                    </h1>
                    <FormularioInicio/>
                </div>
            </div>
        </div>
        <Footer/>
    </section>
</template>

<script setup>
import FormularioInicio from '../components/LoginForm.vue';
import Footer from '../components/Footer.vue';
import router from '../router';

//Aquí cogemos el token del sessionStorage. 
//Y si está, hacemos que en Login vaya directamente a Inicio, sin necesidad de volver a loguearse.
const token = sessionStorage.getItem('farmaToken')

if(token){
    router.push('/inicio')
}

</script>

