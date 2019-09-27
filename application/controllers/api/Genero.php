<?php
require APPPATH . 'libraries/Rest_Controller.php';
require APPPATH . 'libraries/Format.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

class Genero extends REST_Controller
{

	public function __construct()
	{
		parent::__construct("rest");
		$this->load->helper(['jwt', 'authorization', 'date']);
	}

	public function index_get($id = null)
	{
		// Obtenemos todos los headers de la solicitud y lo asignamos a una variable
		$headers = $this->input->request_headers();
		$token = $headers['Authorization'];
		//Validamos el contenido del token
		$dataValidate = AUTHORIZATION::verify_request($token);
		//Si el token es válido comprobamos que el tiempo de vida de el token no hay finalizado
		if ($dataValidate === parent::HTTP_OK) {
			$token = AUTHORIZATION::validateTimestamp($token);
			//Si el token es válido ejecutamos el servicio
			if ($token != false) {
				if (is_object($token)) {
					if (!empty($id)) {
						$data = $this->db->get_where("genero", ['id_genero' => $id])->row_array();
						if ($data == null) {
							$this->response(['Mensaje' => 'No hay datos coincidentes al id: ' . $id], parent::HTTP_NO_CONTENT);
						}
					} else {
						$data = $this->db->get("genero")->result();
					}
					$this->response($data, parent::HTTP_OK);
				}
				//Si el tiempo de vida de el token ha finalizado mandamos un mensaje de error
			} else if ($token === false) {
				$this->response(['Mensaje' => 'El tiempo de la sesión ha finalizado'], parent::HTTP_FORBIDDEN);
			}
		} else if ($dataValidate === parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], $dataValidate);
		} else if ($dataValidate === parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'El token de acceso es incorrecto'], $dataValidate);
		}
	}

	public function index_post()
	{
		// Obtenemos todos los headers de la solicitud y lo asignamos a una variable
		$headers = $this->input->request_headers();
		$token = $headers['Authorization'];
		//Validamos el contenido del token
		$dataValidate = AUTHORIZATION::verify_request($token);
		//Si el token es válido comprobamos que el tiempo de vida de el token no hay finalizado
		if ($dataValidate === parent::HTTP_OK) {
			$token = AUTHORIZATION::validateTimestamp($token);
			//Si el token es válido ejecutamos el servicio
			if ($token != false) {
				if (is_object($token)) {
					$data = [
						'titulo' => $this->post("titulo"),
					];
					$this->db->insert("genero", $data);
					// $this->db->select_max("id");
					// $this->db->from("tarea");
					$query = $this->db->get("genero")->result();
					$this->response($query, parent::HTTP_CREATED);
				}
				//Si el tiempo de vida de el token ha finalizado mandamos un mensaje de error
			} else if ($token === false) {
				$this->response(['Mensaje' => 'El tiempo de la sesión ha finalizado'], parent::HTTP_FORBIDDEN);
			}
		} else if ($dataValidate === parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], $dataValidate);
		} else if ($dataValidate === parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'El token de acceso es incorrecto'], $dataValidate);
		}
	}

	public function index_put($id)
	{
		// Obtenemos todos los headers de la solicitud y lo asignamos a una variable
		$headers = $this->input->request_headers();
		$token = $headers['Authorization'];
		//Validamos el contenido del token
		$dataValidate = AUTHORIZATION::verify_request($token);
		//Si el token es válido comprobamos que el tiempo de vida de el token no hay finalizado
		if ($dataValidate === parent::HTTP_OK) {
			$token = AUTHORIZATION::validateTimestamp($token);
			//Si el token es válido ejecutamos el servicio
			if ($token != false) {
				if (is_object($token)) {
					$data = $this->put();
					$this->db->update("genero", $data, array('id_genero' => $id));
					$this->response(['Mensaje' => "Registro actualizado"], parent::HTTP_OK);
				}
				//Si el tiempo de vida de el token ha finalizado mandamos un mensaje de error
			} else if ($token === false) {
				$this->response(['Mensaje' => 'El tiempo de la sesión ha finalizado'], parent::HTTP_FORBIDDEN);
			}
		} else if ($dataValidate === parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], $dataValidate);
		} else if ($dataValidate === parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'El token de acceso es incorrecto'], $dataValidate);
		}
	}

	public function index_delete($id)
	{
		// Obtenemos todos los headers de la solicitud y lo asignamos a una variable
		$headers = $this->input->request_headers();
		$token = $headers['Authorization'];
		//Validamos el contenido del token
		$dataValidate = AUTHORIZATION::verify_request($token);
		//Si el token es válido comprobamos que el tiempo de vida de el token no hay finalizado
		if ($dataValidate === parent::HTTP_OK) {
			$token = AUTHORIZATION::validateTimestamp($token);
			//Si el token es válido ejecutamos el servicio
			if ($token != false) {
				if (is_object($token)) {
					$this->db->delete("genero", array('id_genero' => $id));
					$this->response(['Mensaje' => "Registro eliminado"], parent::HTTP_OK);
				}
				//Si el tiempo de vida de el token ha finalizado mandamos un mensaje de error
			} else if ($token === false) {
				$this->response(['Mensaje' => 'El tiempo de la sesión ha finalizado'], parent::HTTP_FORBIDDEN);
			}
		} else if ($dataValidate === parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], $dataValidate);
		} else if ($dataValidate === parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'El token de acceso es incorrecto'], $dataValidate);
		}
	}
}
