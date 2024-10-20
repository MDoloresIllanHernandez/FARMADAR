import { createRouter, createWebHistory } from 'vue-router';
import Farmacias from '../views/Farmacias.vue';
import Clientes from '../views/Clientes.vue';
import Productos from '../views/Productos.vue';
import Reservas from '../views/Reservas.vue';
import NotFound from '../views/NotFound.vue';
import Inicio from '../views/Inicio.vue';

const routes = [
  {path: '/', component: Inicio},
  { path: '/farmacias', component: Farmacias },
  { path: '/clientes', component: Clientes },
  { path: '/productos', component: Productos },
  { path: '/reservas', component: Reservas },
  { path: '/:pathMatch(.*)*', component: NotFound }, // Ruta para 404
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;