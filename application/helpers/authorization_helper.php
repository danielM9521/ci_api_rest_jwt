<?php

class AUTHORIZATION
{
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
	}
	
	public static function verify_request($token)
	{
		// Extraemos el token si está seteado en el header
		if (!empty($token)) {
			$token = $token;
		} 
		// sino devolvemos un codigo de estado 401
		else {
			return $data = REST_Controller::HTTP_UNAUTHORIZED;
			exit();
		}

		//Validamos que la cabecera Authorization no esté vacía o nula
		if ($token == "" || empty($token) || $token == null) {
			return $data = REST_Controller::HTTP_FORBIDDEN;
			exit();
		} 
		
		//Si no es así podemos manipular el token
		else {
			try {
				// Validamos el token
				// Si la validación es correcta nos devolverá el nombre de usuario, si no, nos devolverá false
				$data = self::validateToken($token);
				if ($data === false) {
					return $data = REST_Controller::HTTP_FORBIDDEN;
					exit();
				} else {
					return $data = REST_Controller::HTTP_OK;
				}
			} catch (Exception $e) {
				return $data = REST_Controller::HTTP_UNAUTHORIZED;
				exit();
			}
		}
	}

}
