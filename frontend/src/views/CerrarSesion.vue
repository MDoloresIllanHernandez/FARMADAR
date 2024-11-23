<script setup>
import router from '../router';

//Para CERRAR SESIÓN, quitamos el token del localStorage
sessionStorage.removeItem('farmaToken')
sessionStorage.removeItem('user')
sessionStorage.removeItem('id_farm')
sessionStorage.removeItem('role')

//Y volvemos a la pantalla de Login
router.push('/login')
</script>



