<?php
/**
 * Clase que hace de endpoint para la autentificación
 * Se debe mandar por POST un json con el usuario y la contraseña
 */
require_once 'src/classes/auth.class.php';
require_once 'src/response.php';

// Las siguientes líneas son para evitar errores de CORS
// Permite solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permite solicitudes con los métodos GET, POST y OPTIONS
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Si la solicitud incluye cabeceras personalizadas
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejo de la solicitud preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$auth = new Authentication();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$user = json_decode(file_get_contents('php://input'), true);

		$authResult = $auth->signIn($user);

		if ($authResult && isset($authResult['token']) && isset($authResult['role'])) {
            // Si la autenticación es exitosa
            $response = array(
                'result' => 'ok',
                'token' => $authResult['token'],
                'role' => $authResult['role'],
                'id_farm' => $authResult['id_farm'],
                'id' => $authResult['id']
            );
            Response::result(201, $response);
        } else {
            // Si las credenciales son incorrectas
            $response = array(
                'result' => 'error',
                'message' => 'Credenciales incorrectas'
            );
            Response::result(401, $response); // Usamos el código de estado 401 Unauthorized
        }
        break;
}
		