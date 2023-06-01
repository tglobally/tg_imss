<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use gamboamartin\system\system;
use html\nom_nomina_html;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_nom_nomina extends \tglobally\tg_nomina\controllers\controlador_nom_nomina {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct(link: $link,paths_conf: $paths_conf);
        $this->seccion_titulo = "Nominas";
        $this->titulo_accion = "Listado de Nominas";
        $this->lista_get_data = true;
    }

    public function lista(bool $header, bool $ws = false): array
    {
        $r_alta = System::alta(header: false, ws: false);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al generar template', data: $r_alta, header: $header, ws: $ws);
        }

        $params = $this->params_actions->crea_nomina ?? new stdClass();

        $inputs = (new nom_nomina_html(html: $this->html_base))->genera_inputs_alta(controler: $this,
            link: $this->link, params: $params);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar inputs', data: $inputs);
            print_r($error);
            die('Error');
        }

        $this->inputs = $inputs;

        return (array)$r_alta;
    }


}
