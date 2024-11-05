<?php
/**
 * Clase para trabajar con la autentificación en la API
 * Hace uso de las clases implementadas en la carpeta "jwt" para realizar la autentificación mediante token
 * El token se genera a partir del id del usuario, por lo que cada usuario tendrá siempre un token distinto. 
 * Además del id, para generar el token se hace uso de una clave secreta que es un atributo de la clase
 */
require_once 'jwt/JWT.php';
require_once 'src/authModel.php';
require_once 'src/response.php';
use Firebase\JWT\JWT;

class Usuario extends Database
{
	/**
	 * Tabla donde estarán los usuarios
	 */
	private $table = 'usuario';

	/**
	 * Clave secreta para realizar la encriptación y desencriptación del token, se debería cambiar por una clave robusta
	 */
	private $key = 'clave_secreta';


	public function createUser($username, $password, $nombre, $role)
	{
		if(empty($username) || empty($password)){
			$response = array(
				'result' => 'error',
				'details' => 'Los campos username y password son obligatorios'
			);

			Response::result(400, $response);
			exit;
		}

		// Cifrar el password usando SHA-256
		$hashedPassword = hash('sha256', $password);

		// Crear los datos para el token
		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'nombre' => $nombre
			)
		);

		// Generar el token JWT
		$jwt = JWT::encode($dataToken, $this->key);

		// Guardar el usuario en la base de datos
		$data = array(
			'username' => $username,
			'password' => $hashedPassword,
			'nombre' => $nombre,
			'token' => $jwt,
			'role' => $role
		);

		return parent::insertDB($this->table, $data);

	}



}
