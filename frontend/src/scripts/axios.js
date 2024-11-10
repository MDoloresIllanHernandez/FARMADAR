import axios from 'axios';
import {URL_API} from '../constants/constants'

// Crear una instancia personalizada de Axios
const apiClient = axios.create({
  baseURL: `${URL_API}`, // Reemplaza con la URL base de tu API
  headers: {
    'Content-Type': 'application/json'
  }
});

// Agregar un interceptor para inyectar el token en cada petición
apiClient.interceptors.request.use((config) => {
  // Obtener el token desde el sessionStorage 
  const token = sessionStorage.getItem('farmaToken');
 
  if (token) {
    // Si existe un token, lo agregamos al encabezado de api-key
    const user = JSON.parse(sessionStorage.getItem('user')); 
    config.headers['api-key'] = `${token}`;
  }
  return config;
  
}, (error) => {
  // Manejo de errores en la petición
  return Promise.reject(error);
});

export default apiClient;
