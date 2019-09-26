<?php
require APPPATH . 'libraries/Rest_Controller.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
class Libro extends REST_Controller
{

	public function __construct()
	{
		parent::__construct("rest");
		$this->load->helper(['jwt', 'authorization']);
	}

	public function index_options()
	{
		return $this->response(NULL, REST_Controller::HTTP_OK);
	}

	public function index_get($id = null)
	{
		// Obtenemos todos los headers de la solicitud
		$headers = $this->input->request_headers();
		//Los enviampos como parámetro al método verify_request
		$dataValidate = AUTHORIZATION::verify_request($headers);
		if ($dataValidate == parent::HTTP_OK) {
			if (!empty($id)) {
				$data = $this->db->get_where("libro", ['isbn' => $id])->row_array();
				if ($data == null) {
					$this->response(["No hay datos coincidentes al id: " . $id], parent::HTTP_NOT_FOUND);
				}
			} else {
				$this->db->select('l.isbn, l.titulo, l.autor, g.titulo as genero');
				$this->db->from('libro l');
				$this->db->join('genero g', 'l.id_genero = g.id_genero');
				$data = $this->db->get("libro")->result();
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
				'autor' => $this->post("autor"),
				'id_genero' => $this->post("id_genero")
			];
			$this->db->insert("libro", $data);
			$query = $this->db->get("libro")->result();
			$this->response($query);
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
			$this->db->update("libro", $data, array('isbn' => $id));
			$this->response(["Registro actualizado"], parent::HTTP_OK);
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
			$this->db->delete("libro", array('isbn' => $id));
			$this->response(['Mensaje' => "Registro eliminado"], parent::HTTP_OK);
		} elseif ($dataValidate == parent::HTTP_UNAUTHORIZED) {
			$this->response(['Mensaje' => 'No tienes acceso a este servicio'], parent::HTTP_UNAUTHORIZED);
		} elseif ($dataValidate == parent::HTTP_FORBIDDEN) {
			$this->response(['Mensaje' => 'No posees los permisos necesarios para acceder al recurso'], parent::HTTP_FORBIDDEN);
		}
	}
}
