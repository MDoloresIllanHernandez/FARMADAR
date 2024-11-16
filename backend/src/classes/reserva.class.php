<?php
/**
 * Clase para el modelo que representa a la tabla "reservas".
 */
require_once 'src/response.php';
require_once 'src/database.php';

class Reserva extends Database
{
	/**
	 * Atributo que indica la tabla asociada a la clase del modelo
	 */
	private $table = 'reservas';

	/**
	 * Array con los campos de la tabla que se pueden usar como filtro para recuperar registros
	 */
	private $allowedConditions_get = array(
		'id',
		'id_prod',
		'id_farm',
		'nombre',
		'farm_origen'
	);

	/**
	 * Array con los campos de la tabla que se pueden proporcionar para insertar registros
	 */
	private $allowedConditions_insert = array(
		'id_prod',
		'id_farm',
		'farm_origen',
		'fecha',
		'hora_inicio',
		'hora_fin',
		'cantidad',
		'nombre',
		'otros_datos',
		'estado'
		
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
		if(!isset($data['id_prod']) || empty($data['id_prod'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo id_prod es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		if(!isset($data['farm_origen']) || empty($data['farm_origen'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo farm_origen es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}


		if(!isset($data['fecha']) || empty($data['fecha'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo fecha es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['hora_inicio']) || empty($data['hora_inicio'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo hora_inicio es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['hora_fin']) || empty($data['hora_fin'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo hora_fin es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}
		
		//pendiente controlar que la cantidad sea positiva
		if(!isset($data['cantidad']) || empty($data['cantidad'])){
			$response = array(
				'result' => 'error',
				'details' => 'La cantidad es obligatoria'
			);

			Response::result(400, $response);
			exit;
		}


		if(!isset($data['nombre']) || empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo datos_cliente es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['otros_datos']) || empty($data['otros_datos'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo otros_datos es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['estado']) || empty($data['estado'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo estado es obligatorio'
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

		if(isset($data['id_prod']) && empty($data['id_prod'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo id_prod no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['fecha']) && empty($data['fecha'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo fecha no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['hora_inicio']) && empty($data['hora_inicio'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo hora_inicio no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['hora_fin']) && empty($data['hora_fin'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo hora_fin no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		//pendiente controlar que la cantidad sea positiva
		if(isset($data['cantidad']) && empty($data['cantidad'])){
			$response = array(
				'result' => 'error',
				'details' => 'La cantidad es obligatoria'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['nombre']) && empty($data['nombre'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo datos_cliente no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['otros_datos']) && empty($data['otros_datos'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo otros_datos no puede estar vacío'
			);

			Response::result(400, $response);
			exit;
		}

		if(isset($data['estado']) && empty($data['estado'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo estado no puede estar vacío'
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

		$reservas = parent::getDB($this->table, $params);

		return $reservas;
	}

	/**
	 * Método para recuperar registros por un parametro-valor
	 */
	public function getByParams($params1, $value1, $params2, $value2 ){
		

		$reservas = parent::getDB($this->table, array($params1=>$value1, $params2=>$value2));

		return $reservas;
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
			$affected_rows = parent::updateProductDB($this->table, $id, $params);

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