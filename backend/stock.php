<?php
/**
 * Endpoint para la gestión del stock de productos
 * 
 */
require_once 'src/response.php';
require_once 'src/classes/producto.class.php';
require_once 'src/classes/auth.class.php';

// Permitir solicitudes desde cualquier origen (esto es más amplio y no recomendable en producción)
header("Access-Control-Allow-Origin: *");

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

// Verificar la autenticación
$auth = new Authentication();
$auth->verify();

// Crear una instancia de la clase Producto
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

		// Validar parámetros
		if (empty($params['id']) || empty($params['id_farm'])) {
			Response::result(400, array(
				'result' => 'error',
				'message' => 'Faltan parámetros requeridos: id y/o id_farm.'
			));
			exit();
		}

		// Consultar el stock del producto
		try {
			$resultado = $producto->getByParams('id', $params['id'], 'id_farm', $params['id_farm']);

			if (!empty($resultado)) {
				$stock = $resultado[0]['stock'];
				Response::result(200, array(
					'result' => 'ok',
					'stock' => $stock
				));
			} else {
				Response::result(404, array(
					'result' => 'error',
					'message' => 'Producto no encontrado en la farmacia especificada.'
				));
			}
		} catch (Exception $e) {
			Response::result(500, array(
				'result' => 'error',
				'message' => 'Error al consultar el stock: ' . $e->getMessage()
			));
		}
		break;

	default:
		Response::result(405, array(
			'result' => 'error',
			'message' => 'Método no permitido.'
		));
		break;

	/**
	 * Si se ha recibido un PUT se llama al método updateStock() del modelo y se le pasan los parámetros recibidos por PUT en la petición
	 */
	case 'PUT':
		$data = json_decode(file_get_contents('php://input'), true);


		if (empty($data['id']) || empty($data['id_farm']) || !isset($data['newStock'])) {
			Response::result(400, array(
				'result' => 'error',
				'message' => 'Faltan parámetros requeridos: id, id_farm, y/o el stock.'
			));
			exit();
		}

		try {
			$producto->updateStock($data['id'], $data['id_farm'], $data['newStock']);
			Response::result(200, array(
				'result' => 'ok',
				'message' => 'Stock actualizado correctamente.'
			));
		} catch (Exception $e) {
			Response::result(500, array(
				'result' => 'error',
				'message' => 'Error al actualizar el stock: ' . $e->getMessage()
			));
		}
		break;

}


