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

		$this->load->helper(['jwt', 'authorization']);
	}

	public function index_get($id = null)
	{
		// Obtenemos todos los headers de la solicitud
		$headers = $this->input->request_headers();
		$dataValidate = AUTHORIZATION::verify_request($headers);
		if ($dataValidate == parent::HTTP_OK) {
			if (!empty($id)) {
				$data = $this->db->get_where("genero", ['id_genero' => $id])->row_array();
				if ($data == null) {
					$this->response(['Mensaje' => 'No hay datos coincidentes al id: ' . $id], parent::HTTP_NO_CONTENT);
				}
			} else {
				$data = $this->db->get("genero")->result();
			}
			$this->response($data, parent::HTTP_OK);
		} elseif ($dataValidate == parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], parent::HTTP_UNAUTHORIZED);
		} elseif ($dataValidate == parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'No posees los permisos necesarios para acceder al recurso'], parent::HTTP_FORBIDDEN);
		}
	}

	public function index_post()
	{
		// Obtenemos todos los headers de la solicitud
		$headers = $this->input->request_headers();
		$dataValidate = AUTHORIZATION::verify_request($headers);
		if ($dataValidate == parent::HTTP_OK) {
			$data = [
				'titulo' => $this->post("titulo"),
			];
			$this->db->insert("genero", $data);
			// $this->db->select_max("id");
			// $this->db->from("tarea");
			$query = $this->db->get("genero")->result();
			$this->response($query, parent::HTTP_CREATED);
		} elseif ($dataValidate == parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], parent::HTTP_UNAUTHORIZED);
		} elseif ($dataValidate == parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'No posees los permisos necesarios para acceder al recurso'], parent::HTTP_FORBIDDEN);
		}
	}

	public function index_put($id)
	{
		// Obtenemos todos los headers de la solicitud
		$headers = $this->input->request_headers();
		$dataValidate = AUTHORIZATION::verify_request($headers);
		if ($dataValidate == parent::HTTP_OK) {
			$data = $this->put();
			$this->db->update("genero", $data, array('id_genero' => $id));
			$this->response(['Mensaje' => "Registro actualizado"], parent::HTTP_OK);
		} elseif ($dataValidate == parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], parent::HTTP_UNAUTHORIZED);
		} elseif ($dataValidate == parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'No posees los permisos necesarios para acceder al recurso'], parent::HTTP_FORBIDDEN);
		}
	}

	public function index_delete($id)
	{
		// Obtenemos todos los headers de la solicitud
		$headers = $this->input->request_headers();
		$dataValidate = AUTHORIZATION::verify_request($headers);
		if ($dataValidate == parent::HTTP_OK) {
			$this->db->delete("genero", array('id_genero' => $id));
			$this->response(['Mensaje' => "Registro eliminado"], parent::HTTP_OK);
		} elseif ($dataValidate == parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], parent::HTTP_UNAUTHORIZED);
		} elseif ($dataValidate == parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'No posees los permisos necesarios para acceder al recurso'], parent::HTTP_FORBIDDEN);
		}
	}
}
