<?php
/**
 * Clase que hace de endpoint para la autentificación
 * Se debe mandar por POST un json con el usuario y la contraseña
 */
require_once 'src/classes/auth.class.php';
require_once 'src/response.php';

// Las siguientes dos líneas son para evitar errores de CORS
// Permite solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Si la solicitud incluye cabeceras personalizadas
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$auth = new Authentication();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$user = json_decode(file_get_contents('php://input'), true);

		$token = $auth->signIn($user);

		$response = array(
			'result' => 'ok',
			'token' => $token
		);

		Response::result(201, $response);

		break;
}