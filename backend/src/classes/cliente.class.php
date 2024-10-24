<?php
/**
 * Clase para el modelo que representa a la tabla "clientes".
 */
require_once 'src/response.php';
require_once 'src/database.php';

class Cliente extends Database
{
	/**
	 * Atributo que indica la tabla asociada a la clase del modelo
	 */
	private $table = 'clientes';

	/**
	 * Array con los campos de la tabla que se pueden usar como filtro para recuperar registros
	 */
	private $allowedConditions_get = array(
		'dni',
		'telefono',
	);

	/**
	 * Array con los campos de la tabla que se pueden proporcionar para insertar registros
	 */
	private $allowedConditions_insert = array(
		'dni',
		'nombre',
		'telefono',
		'email',
		
	);

	/**
	 * Método para validar los datos que se mandan para insertar un registro, comprobar campos obligatorios, valores válidos, etc.
	 */
	private function validate($data){
		
		if(!isset($data['dni']) || empty($data['dni']) ){
			$response = array(
				'result' => 'error',
				'details' => 'El campo dni es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['nombre']) || empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo nombre no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['telefono']) || empty($data['telefono']) ){
			$response = array(
				'result' => 'error',
				'details' => 'El campo teléfono es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		
		if(count($this->getByParams('dni', $data['dni']))>0){
			$response = array(
				'result' => 'error',
				'details' => 'Ese cliente ya existe en la base de datos'
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
		
		if(isset($data['dni']) && empty($data['dni'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo dni no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}
		if(isset($data['nombre']) && empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo nombre no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}
		if(isset($data['telefono']) && empty($data['telefono'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo teléfono no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}
		return true;
	}

	/**
	 * Método para recuperar registros, pudiendo indicar algunos filtros 
	 */
	public function get($params){
		foreach ($params as $key => $param) {
			if(!in_array($key, $this->allowedConditions_get)){
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
	
				Response::result(400, $response);
				exit;
			}
		}

		$clientes = parent::getDB($this->table, $params);

		return $clientes;
	}

	/**
	 * Método para recuperar registros por un parametro-valor (dni, 00000000X)
	 */
	public function getByParams($params, $value){
		

		$clientes = parent::getDB($this->table, array($params=>$value));

		return $clientes;
	}

	/**
	 * Método para guardar un registro en la base de datos, recibe como parámetro el JSON con los datos a insertar
	 */
	public function insert($params)
	{
		foreach ($params as $key => $param) {
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

		if($this->validate($params)){
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
					'result' => 'error',
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
				'result' => 'error',
				'details' => 'No hubo cambios'
			);

			Response::result(200, $response);
			exit;
		}
	}
}

?>