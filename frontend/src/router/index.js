import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  { path: '/login', component: () => import('../views/Login.vue') },
  { path: '/inicio', component: () => import('../views/Inicio.vue')},
  { path: '/buscador-productos', component: () => import('../views/BuscadorProductos.vue') },
  { path: '/farmacias', component: () => import('../views/Farmacias.vue') },
  { path: '/clientes', component: () => import('../views/Clientes.vue')},
  { path: '/productos', component: () => import('../views/Productos.vue') },
  { path: '/reservas', component: () => import('../views/Reservas.vue') },
  { path: '/cerrar-sesion', component: () => import('../views/CerrarSesion.vue') },
  { path: '/:pathMatch(.*)*', component: () => import('../views/Login.vue') }, // Ruta para 404
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

  // Obtenemos el token desde localStorage
  const token = localStorage.getItem('farmaToken');

  // Si no hay token y la ruta no es pública, redirigir a Login
  if (!token && !esRutaPublica) {
    return next('/login');
  }

  // Si hay token o la ruta es pública, permitir la navegación
  next();
});

export default router;