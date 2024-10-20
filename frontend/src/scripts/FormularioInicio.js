import axios from 'axios';

// Función que maneja el envío del formulario
export async function submitFormulario(username, password) {
  // Lógica para manejar la solicitud del formulario
  if (!username || !password) {
    console.error('Por favor, completa ambos campos.');
    return;
  }

  try {
    // Enviar la solicitud POST a la API
    const response = await axios.post('http://localhost/farmadar/backend/auth', {
      username: username,
      password: password
    });
    console.log('Respuesta del servidor:', response.data);
    
    // Aquí puedes agregar la lógica de éxito (ej. redirección o notificación)
    return response.data;
  } catch (error) {
    console.error('Error en la petición:', error);
    throw error; // Lanza el error para que pueda ser manejado en el componente si es necesario
  }
}
