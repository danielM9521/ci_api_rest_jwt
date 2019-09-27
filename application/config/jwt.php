<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Llave secreta para encriptar el token
$config['jwt_key'] = '123';
// El tiempo d evida del token
$config['token_timeout'] = 0.3;
