<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_nom_nomina extends \gamboamartin\nomina\controllers\controlador_nom_nomina {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->seccion_titulo = "Nominas";
        $this->titulo_accion = "Listado de Nominas";



    }



}
