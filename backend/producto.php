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
	
		// Verificar si el rol y el id_farm vienen como parámetros
		$role = isset($params['role']) ? $params['role'] : null;
		$id_farm = isset($params['id_farm']) ? $params['id_farm'] : null;
	
		// Filtrar usuarios según el rol
		if ($role === 'superadmin') {
			$productos = $producto->get([]); // Obtener todos los productos
		} elseif ($role && $id_farm) {
			// Filtrar productos de la misma farmacia
			$productos = $producto->get(['id_farm' => $id_farm]);
		} else {
			// Si no hay rol válido, devolver error
			$response = array(
				'result' => 'error',
				'details' => 'No autorizado'
			);
			Response::result(403, $response);
			exit;
		}
	
		$response = array(
			'result' => 'ok',
			'productos' => $productos
		);
	
		Response::result(200, $response);
		break;
		
	/**
	 * Si se recibe un POST, se comprueba si se han recibido parámetros y en caso afirmativo se usa el método insert() del modelo
	 */
	case 'POST':
		$params = json_decode(file_get_contents('php://input'), true);

		if(!isset($params)){
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);

			Response::result(400, $response);
			exit;
		}


		$insert_id = $producto->insert($params);

		$response = array(
			'result' => 'ok',
			'producto' => $params
		);

		Response::result(201, $response);
		break;

	/**
	 * Cuando es PUT, comprobamos si la petición lleva el id del producto que hay que actualizar. En caso afirmativo se usa el método update() del modelo.
	 */
	case 'PUT':
		$params = json_decode(file_get_contents('php://input'), true);

		if(!isset($params) || !isset($_GET['id']) || empty($_GET['id'])){
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);

			Response::result(400, $response);
			exit;
		}

		$producto->update($_GET['id'], $params);

		$response = array(
			'result' => 'ok'
		);

		Response::result(200, $response);	
		break;

	/**
	 * Cuando se solicita un DELETE se comprueba que se envíe un id de producto. En caso afirmativo se utiliza el método delete() del modelo.
	 */
	case 'DELETE':
		if(!isset($_GET['id']) || empty($_GET['id'])){
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud'
			);

			Response::result(400, $response);
			exit;
		}

		$producto->delete($_GET['id']);

		$response = array(
			'result' => 'ok'
		);

		Response::result(200, $response);
		break;

	/**
	 * Para cualquier otro tipo de petición se devuelve un mensaje de error 404.
	 */
	default:
		$response = array(
			'result' => 'error'
		);

		Response::result(404, $response);

		break;
}
?>