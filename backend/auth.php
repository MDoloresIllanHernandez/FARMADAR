<?php
/**
 * Clase que hace de endpoint para la autentificación
 * Se debe mandar por POST un json con el usuario y la contraseña
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejo de la solicitud preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'src/classes/auth.class.php';
require_once 'src/response.php';

$auth = new Authentication();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $user = json_decode(file_get_contents('php://input'), true);

        // Llamada al método signIn
        $token = $auth->signIn($user);

        if ($token) {
            // Si la autenticación es exitosa
            $response = array(
                'result' => 'ok',
                'token' => $token
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
