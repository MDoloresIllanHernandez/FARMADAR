<script setup>
import router from '../router';

//Para CERRAR SESIÓN, quitamos el token del localStorage
localStorage.removeItem('farmaToken')

//Y volvemos a la pantalla de Login
router.push('/login')
</script>



