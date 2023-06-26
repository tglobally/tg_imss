<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace tglobally\tg_imss\controllers;



use gamboamartin\errores\errores;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_em_anticipo extends \gamboamartin\empleado\controllers\controlador_em_anticipo {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->seccion_titulo = "Anticipos";
        $this->titulo_accion = "Listado de Anticipos";

        $acciones = $this->define_acciones_menu(acciones: array("Importar Registros" => $this->link_em_anticipo_lee_archivo));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al integrar acciones para el menu', data: $acciones);
            print_r($error);
            die('Error');
        }
    }

}
