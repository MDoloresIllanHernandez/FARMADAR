import { createRouter, createWebHistory } from 'vue-router';
import apiClient from '../scripts/axios.js';

const routes = [
  { path: '/login', component: () => import('../views/Login.vue') },
  { path: '/inicio', component: () => import('../views/Inicio.vue') },
  { path: '/buscador-productos', name: 'Buscador', component: () => import('../views/BuscadorProductos.vue') },
  { path: '/farmacias', name: 'Farmacias', component: () => import('../views/Farmacias.vue') },
  { path: '/productos', name: 'Productos', component: () => import('../views/Productos.vue') },
  { path: '/reservas', name: 'Reservas', component: () => import('../views/Reservas.vue') },
  { path: '/cerrar-sesion', component: () => import('../views/CerrarSesion.vue') },
  { path: '/:pathMatch(.*)*', component: () => import('../views/Login.vue') },
  {
    path: '/reservas/nueva/:productId/:farmId', name: 'Reserva'
    , component: () => import('../views/ReservasNueva.vue')
    , props: true, // Permitir pasar props desde la ruta.
    beforeEnter: async (to, from, next) => {
      try {
        // Obtener las farmacias antes de entrar al componente.
        const response = await apiClient.get('/farmacia');
        if (response.data.result === 'ok' && response.data.farmacias) {
          // Pasar las farmacias como prop.
          to.params.farmacias = response.data.farmacias;
        }
      } catch (error) {
        console.error('Error al obtener las farmacias:', error);
        to.params.farmacias = []; // Manejo de errores: pasar un array vacío.
      }
      const role = sessionStorage.getItem('role');
      const idFarm = sessionStorage.getItem('id_farm');
      try {
        // Obtener los productos antes de entrar al componente.
        const response = await apiClient.get('/producto', {
          params: { role, id_farm: idFarm, source: 'producto' },
        });
        if (response.data.result === 'ok' && response.data.productos) {
          // Pasar los productos como prop.
          to.params.productos = response.data.productos;
        }
      } catch (error) {
        console.error('Error al obtener los productos:', error);
        to.params.productos = []; // Manejo de errores: pasar un array vacío.
      }
      next();
    },
  },

  { path: '/usuarios', name: 'Usuarios', component: () => import('../views/Usuarios.vue') },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Con esto indicamos que antes de ir a cualquier ruta, se comrpuebe si hay token.
router.beforeEach((to, from, next) => {
  // Rutas públicas (donde no se requiere autenticación) Login es una ruta que se puede llegar sin autenticar.
  const rutasPublicas = ['/login'];

  // Verificamos si la ruta es pública (si tuviéramos más públicas, la añadiríamos arriba con una coma. Es decir,
  // ['login', 'inicio', etc.])
  const esRutaPublica = rutasPublicas.includes(to.path);

  // Obtenemos el token desde sessionStorage
  const token = sessionStorage.getItem('farmaToken');

  // Si no hay token y la ruta no es pública, redirigir a Login
  if (!token && !esRutaPublica) {
    return next('/login');
  }
  
  // Si hay token o la ruta es pública, permitir la navegación
  next();
});

export default router;