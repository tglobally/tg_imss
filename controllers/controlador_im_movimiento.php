<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_im_movimiento extends \gamboamartin\im_registro_patronal\controllers\controlador_im_movimiento {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Movimientos Empleado';

        $this->asignar_propiedad(identificador:'im_tipo_movimiento_id', propiedades: ["label" => "Tipo Movimiento", "cols" => 6]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'em_registro_patronal_id', propiedades: ["label" => "Registro Patronal"]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'em_empleado_id', propiedades: ["label" => "Empleado"]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $alta = parent::alta($header, $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template alta',data:  $alta, header: $header,ws:$ws);
        }



        return  $alta;
    }


}
