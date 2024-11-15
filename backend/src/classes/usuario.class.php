<?php
/**
 * Clase para el modelo que representa a la tabla "productos".
 */
require_once 'src/response.php';
require_once 'src/database.php';
use \Firebase\JWT\JWT;


class Usuario extends Database
{
	/**
	 * Atributo que indica la tabla asociada a la clase del modelo
	 */
	private $table = 'usuario';

	private $key = 'clave_secreta';


	/**
	 * Array con los campos de la tabla que se pueden usar como filtro para recuperar registros
	 */
	private $allowedConditions_get = array(
		'id_farm',
		'username',
	);

	/**
	 * Array con los campos de la tabla que se pueden proporcionar para insertar registros
	 */
	private $allowedConditions_insert = array(
		'nombre',
		'username',
		'password',
		'role',
		'id_farm'
	);

	/**
	 * Método para validar los datos que se mandan para insertar un registro, comprobar campos obligatorios, valores válidos, etc.
	 */
	private function validate($data){
		
		if(!isset($data['id_farm']) || empty($data['id_farm'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo id_farm es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		if(!isset($data['nombre']) || empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo nombre es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		//pendiente controlar que el precio sea positivo
		if(!isset($data['username']) || empty($data['username'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo username es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		//pendiente controlar que el stock sea positivo
		if(!isset($data['password']) || empty($data['password'])){
			$response = array(
				'result' => 'error',
				'details' => 'El password es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		//pendiente controlar que el stock sea positivo
		if(!isset($data['role']) || empty($data['role'])){
			$response = array(
				'result' => 'error',
				'details' => 'El rol es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		
		return true;
	}

	/**
	 * Método para validar que al modificar un registro, los campos obligatorios están cumplimentados
	 */
	private function validateUpdate($data){
		
		if(isset($data['id']) && empty($data['id'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo id no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['id_farm']) && empty($data['id_farm'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo id_farm no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['username']) && empty($data['username'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo username no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		//pendiente controlar que el precio sea positivo
		if(isset($data['nombre']) && empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo nombre no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		//pendiente controlar que el stock sea positivo
		if(isset($data['role']) && empty($data['role'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo rol no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}
		
		return true;
	}

	/**
	 * Método para recuperar registros, pudiendo indicar algunos filtros 
	 */
	public function get($params) {
		foreach ($params as $key => $param) {
			if (!in_array($key, $this->allowedConditions_get)) {
				unset($params[$key]);
			}
		}
	
		$usuarios = parent::getDB($this->table, $params);
		return $usuarios;
	}

	/**
	 * Método para recuperar registros por un parametro-valor
	 */
	public function getByParams($params1, $value1, $params2, $value2 ){
		

		$usuarios = parent::getDB($this->table, array($params1=>$value1, $params2=>$value2));

		return $usuarios;
	}

	
	/**
	 * Método para guardar un registro en la base de datos, recibe como parámetro el JSON con los datos a insertar
	 */
	public function insert($params)
	{
		// Validar campos permitidos
		foreach ($params as $key => $param) {
			if (!in_array($key, $this->allowedConditions_insert)) {
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
				Response::result(400, $response);
				exit;
			}
		}
	
		// Codificar el password con hash 'sha256'
		if (isset($params['password'])) {
			$params['password'] = hash('sha256', $params['password']);
		}
	
		// Crear el token JWT
		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'username' => $params['username'],
				'nombre' => $params['nombre']
			)
		);
		$jwt = JWT::encode($dataToken, $this->key); 
	
		// Añadir el token a los parámetros
		$params['token'] = $jwt;
	
		// Validar e insertar en la base de datos
		if ($this->validate($params)) {
			return parent::insertDB($this->table, $params);
		}
	}



	/**
	 * Método para actualizar un registro en la base de datos, se indica el id del registro que se quiere actualizar
	 */
	public function update($id, $params)
	{
		foreach ($params as $key => $parm) {
			if(!in_array($key, $this->allowedConditions_insert)){
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
	
				Response::result(400, $response);
				exit;
			}
		}

		if($this->validateUpdate($params)){
			$affected_rows = parent::updateDB($this->table, $id, $params);

			if($affected_rows==0){
				$response = array(
					'result' => 'ok',
					'details' => 'No hubo cambios'
				);

				Response::result(200, $response);
				exit;
			}
		}
	}

	/**
	 * Método para borrar un registro de la base de datos, se indica el id del registro que queremos eliminar
	 */
	public function delete($id)
	{
		$affected_rows = parent::deleteDB($this->table, $id);

		if($affected_rows==0){
			$response = array(
				'result' => 'ok',
				'details' => 'No hubo cambios'
			);

			Response::result(200, $response);
			exit;
		}
	}
}

?>