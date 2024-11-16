<?php
/**
 *	Script que se usa en los endpoints para trabajar con registros de la tabla PRODUCTOS
 *	La clase "producto.class.php" es la clase del modelo, que representa a un producto de la tabla
*/
require_once 'src/response.php';
require_once 'src/classes/producto.class.php';
require_once 'src/classes/auth.class.php';

// Permitir solicitudes desde cualquier origen (esto es más amplio y no recomendable en producción)
header("Access-Control-Allow-Origin: *");

// O permitir solicitudes solo desde un origen específico
// header("Access-Control-Allow-Origin: http://localhost:5173");

// Permitir los métodos que se pueden usar en las solicitudes
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir los encabezados personalizados
header("Access-Control-Allow-Headers: Content-Type, Authorization, Api-Key, Farma-User");


// Manejar la solicitud preflight (OPTIONS) antes de ejecutar cualquier lógica de verificación
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Devolver una respuesta vacía con código 200 para que el navegador permita la solicitud real
    http_response_code(200);
    exit();
}

$auth = new Authentication();

$auth->verify();

$producto = new Producto();

/**
 * Se mira el tipo de petición que ha llegado a la API y dependiendo de ello se realiza una u otra accción
 */
switch ($_SERVER['REQUEST_METHOD']) {
	
	/**
	 * Si se ha recibido un GET se llama al método get() del modelo y se le pasan los parámetros recibidos por GET en la petición
	 */
	case 'GET':
		$params = $_GET;
		
		$productos = $producto->get($params);

		$response = array(
			'result' => 'ok',
			'productos' => $productos
		);

		Response::result(200, $response);

		break;
		
}
?>