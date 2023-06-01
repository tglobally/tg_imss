<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_im_tipo_movimiento extends \gamboamartin\im_registro_patronal\controllers\controlador_im_tipo_movimiento {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Tipos de Movimiento';
        $this->seccion_titulo = "Tipos de Movimiento";

        $this->titulo_accion = "Listado de Tipos de Movimiento";

        $acciones = $this->define_acciones_menu(acciones: array("alta" => $this->link_alta));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al integrar acciones para el menu', data: $acciones);
            print_r($error);
            die('Error');
        }

    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = parent::alta($header, $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Alta Tipo de Movimiento";

        return $r_alta;

    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = parent::modifica($header, $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Modifica Tipo de Movimiento";

        return $r_modifica;
    }



}
