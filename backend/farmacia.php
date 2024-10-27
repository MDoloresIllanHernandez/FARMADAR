<?php
/**
 *	Script que se usa en los endpoints para trabajar con registros de la tabla FARMACIAS
 *	La clase "farmacia.class.php" es la clase del modelo, que representa a una farmacia de la tabla
*/
require_once 'src/response.php';
require_once 'src/classes/farmacia.class.php';
require_once 'src/classes/auth.class.php';

// Permitir solicitudes desde cualquier origen (esto es más amplio y no recomendable en producción)
header("Access-Control-Allow-Origin: *");

// O permitir solicitudes solo desde un origen específico
// header("Access-Control-Allow-Origin: http://localhost:5173");

// Permitir los métodos que se pueden usar en las solicitudes
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir los encabezados personalizados
header("Access-Control-Allow-Headers: Content-Type, Authorization, Api-Key");


// Manejar la solicitud preflight (OPTIONS) antes de ejecutar cualquier lógica de verificación
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Devolver una respuesta vacía con código 200 para que el navegador permita la solicitud real
    http_response_code(200);
    exit();
}

$auth = new Authentication();
$auth->verify();

$farmacia = new Farmacia();

/**
 * Se mira el tipo de petición que ha llegado a la API y dependiendo de ello se realiza una u otra accción
 */
switch ($_SERVER['REQUEST_METHOD']) {
	/**
	 * Si se ha recibido un GET se llama al método get() del modelo y se le pasan los parámetros recibidos por GET en la petición
	 */
	case 'GET':
		$params = $_GET;

		$farmacias = $farmacia->get($params);

		$response = array(
			'result' => 'ok',
			'farmacias' => $farmacias
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


		$insert_id = $farmacia->insert($params);

		$response = array(
			'result' => 'ok',
			'farmacia' => $params
		);

		Response::result(201, $response);
		break;

	/**
	 * Cuando es PUT, comprobamos si la petición lleva el id de la farmacia que hay que actualizar. En caso afirmativo se usa el método update() del modelo.
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

		$farmacia->update($_GET['id'], $params);

		$response = array(
			'result' => 'ok'
		);

		Response::result(200, $response);	
		break;

	/**
	 * Cuando se solicita un DELETE se comprueba que se envíe un id de farmacia. En caso afirmativo se utiliza el método delete() del modelo.
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

		$farmacia->delete($_GET['id']);

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