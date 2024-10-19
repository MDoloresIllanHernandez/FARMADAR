<template>
<form class="space-y-4 md:space-y-6 " action="#">
    <div>
        <label for="email" class="block mb-2 text-sm font-medium text-primary-oscuro dark:text-white">Usuario</label>
        <input type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="usuario" required="">
    </div>
    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-primary-oscuro dark:text-white">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-primary-oscuro rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 " required="">
    </div>
    <button type="submit" class="w-full rounded-md bg-primary-azul px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-turquesa focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-violeta">Iniciar</button>
</form>

</template>

<script setup>

</script>

<style>

</style>