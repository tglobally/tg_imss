<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_cat_sat_regimen_fiscal extends \gamboamartin\cat_sat\controllers\controlador_cat_sat_regimen_fiscal {


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);
        $this->titulo_lista = 'Régimen Fiscal';
        $this->seccion_titulo = "Régimen Fiscal";
        $this->titulo_accion = "Listado de Regímenes Fiscales";

        $acciones = $this->define_acciones_menu(acciones: array("alta" => $this->link_alta));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al integrar acciones para el menu', data: $acciones);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = parent::alta($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Alta Régimen Fiscal";

        return $r_alta;

    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {

        $r_modifica = parent::modifica($header, $ws);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Modifica Régimen Fiscal";

        return $r_modifica;
    }


}