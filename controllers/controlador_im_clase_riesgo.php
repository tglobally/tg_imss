<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_im_clase_riesgo extends \gamboamartin\im_registro_patronal\controllers\controlador_im_clase_riesgo {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Clase Riesgo';
    }



}
