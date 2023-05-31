<?php
namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_org_empresa extends \gamboamartin\organigrama\controllers\controlador_org_empresa {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        $html_base = new html();
        parent::__construct(link: $link, html: $html_base);

        $this->titulo_accion = "Listado de Empresas";

        $this->sidebar['lista']['titulo'] = "Empresas";
        $this->sidebar['lista']['menu'] = array();

    }

    public function menu_item(string $menu_item_titulo, string $link, bool $menu_seccion_active = false,bool $menu_lateral_active = false): array
    {
        $menu_item = array();
        $menu_item['menu_item'] = $menu_item_titulo;
        $menu_item['menu_seccion_active'] = $menu_seccion_active;
        $menu_item['link'] = $link;
        $menu_item['menu_lateral_active'] = $menu_lateral_active;

        return $menu_item;
    }

    public function modifica(bool $header, bool $ws = false, array $keys_selects =  array()): array|stdClass
    {
        $r_modifica = parent::modifica($header, $ws, $keys_selects);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Modifica Empresa";

        return $r_modifica;
    }


}
