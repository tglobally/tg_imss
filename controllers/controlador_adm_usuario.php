<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use stdClass;

class controlador_adm_usuario extends \gamboamartin\acl\controllers\controlador_adm_usuario {

    public function get_usuario(bool $header, bool $ws = true): array|stdClass
    {
        $keys['adm_usuario'] = array('id', 'user', 'email');

        $salida = $this->get_out(header: $header, keys: $keys, ws: $ws);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar salida', data: $salida, header: $header, ws: $ws);
        }

        return $salida;
    }


}
