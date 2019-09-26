<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
class login extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Cargamos el helper para poder crear el token
		$this->load->helper(['jwt', 'authorization']);
	}

	public function validate_user($nombre_usuario, $contrasenia)
	{
		try {
			$row = $this->db->get_where('usuario', array('nombre_usuario' => $nombre_usuario, 'contrasenia' => $contrasenia))->num_rows();
			if ($row === 1) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
			$this->response(['Mensaje' => 'Ocurrió un error inesperado en el servidor'], parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function index_get()
	{
		// Creación del token de muestra
		$token = AUTHORIZATION::generateToken(['Example' => 'Hello World!']);

		// Seteamos el codigo de estado HTTP 200
		$status = parent::HTTP_OK;
		// Mensaje de respuesta
		$response = ['Mensaje' => 'Este es un token de muestra', 'status' => $status, 'token' => $token];
		// REST_Controller provee el siguiente método para enviar las respuestas
		$this->response($response, $status);
	}


	public function login_post()
	{
		// Extraemos la información de las variables post
		$username = $this->post('nombre_usuario');
		$contrasenia = $this->post('contrasenia');
		//Enviamos el username y la contraseña como parámetros para ver si existe un usuario con esas credenciales
		$data = $this->validate_user($username, $contrasenia);
		// Chequeamos que el usuario sea válido
		if ($data === true) {
			// Creamos un token con los datos del usuario y enviamos la respuesta
			$token['token'] = AUTHORIZATION::generateToken(['nombre_usuario' => $data['nombre_usuario']]);
			$token['timestamp'] = date("Y-n-j H:i:s");
			var_dump($token);
			// Seteamos el codigo de estado HTTP 200 para confirmar el éxito en la operación
			$status = parent::HTTP_OK;
			$response = ['Estado' => $status, 'token' => $token, 'Mensaje' => 'Token generado con éxito'];
			// Enviamos la respuesta
			$this->response($response, $status);
		}
		// Si el método validate_user nos devuelve false significa que no existe un usuario con esas credenciales
		else if ($data === false) {
			$this->response(['Mensaje' => 'Usuario y/o contraseña inválidos'], parent::HTTP_NOT_FOUND);
		}
	}
}
