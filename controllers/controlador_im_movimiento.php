<?php
namespace tglobally\tg_imss\controllers;

use PDO;
use stdClass;
use gamboamartin\errores\errores;
use tglobally\template_tg\html;

class controlador_im_movimiento extends \gamboamartin\im_registro_patronal\controllers\controlador_im_movimiento {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct(link: $link, html: new html());

        $sidebar = $this->init_sidebar();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar sidebar', data: $sidebar);
            print_r($error);
            die('Error');
        }
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }

        $keys_selects['em_empleado_id']->cols = 10;

        $this->row_upd->fecha = date('Y-m-d');
        $this->row_upd->salario_diario = 0;
        $this->row_upd->salario_diario_integrado = 0;
        $this->row_upd->salario_mixto = 0;
        $this->row_upd->salario_variable = 0;

        $inputs = $this->inputs(keys_selects: $keys_selects);
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }



    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion', 'salario_diario', 'salario_diario_integrado', 'observaciones',
            'factor_integracion', 'salario_mixto', 'salario_variable', 'nombre', 'ap', 'am', 'nss', 'nombre_preview',
            'ap_preview', 'am_preview', 'nss_preview', 'salario_diario_preview', 'salario_diario_integrado_preview');
        $keys->fechas = array('fecha');
        $keys->selects = array();

        $init_data = array();
        $init_data['em_empleado'] = "gamboamartin\\empleado";
        $init_data['im_tipo_movimiento'] = "gamboamartin\\im_registro_patronal";
        $init_data['em_registro_patronal'] = "gamboamartin\\empleado";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    private function init_sidebar(): stdClass|array
    {
        $menu_items = new stdClass();

        $menu_items->lista = $this->menu_item(menu_item_titulo: "Inicio", link: $this->link_lista);
        $menu_items->alta = $this->menu_item(menu_item_titulo: "Alta", link: $this->link_alta);
        $menu_items->modifica = $this->menu_item(menu_item_titulo: "Modifica", link: $this->link_modifica);
        $menu_items->importar = $this->menu_item(menu_item_titulo: "Importar", link: $this->link_im_movimiento_sube_archivo);

        $menu_items->lista['menu_seccion_active'] = true;
        $menu_items->lista['menu_lateral_active'] = true;
        $menu_items->alta['menu_seccion_active'] = true;
        $menu_items->alta['menu_lateral_active'] = true;
        $menu_items->modifica['menu_seccion_active'] = true;
        $menu_items->modifica['menu_lateral_active'] = true;
        $menu_items->importar['menu_seccion_active'] = true;
        $menu_items->importar['menu_lateral_active'] = true;

        $this->sidebar['lista']['titulo'] = "Movimientos";
        $this->sidebar['lista']['menu'] = array($menu_items->alta, $menu_items->importar);

        $menu_items->alta['menu_seccion_active'] = false;

        $this->sidebar['alta']['titulo'] = "Movimientos";
        $this->sidebar['alta']['stepper_active'] = true;
        $this->sidebar['alta']['menu'] = array($menu_items->alta);

        $menu_items->modifica['menu_seccion_active'] = false;

        $this->sidebar['modifica']['titulo'] = "Movimientos";
        $this->sidebar['modifica']['stepper_active'] = true;
        $this->sidebar['modifica']['menu'] = array($menu_items->modifica);

        $menu_items->importar['menu_seccion_active'] = false;

        $this->sidebar['sube_archivo']['titulo'] = "Movimientos";
        $this->sidebar['sube_archivo']['stepper_active'] = true;
        $this->sidebar['sube_archivo']['menu'] = array($menu_items->importar);

        return $menu_items;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'nombre',
            keys_selects: $keys_selects, place_holder: 'Nombre');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'nombre_preview',
            keys_selects: $keys_selects, place_holder: 'Nombre');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['nombre_preview']->disabled = true;

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'ap',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'ap_preview',
            keys_selects: $keys_selects, place_holder: 'Apellido Paterno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['ap_preview']->disabled = true;

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'am',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'am_preview',
            keys_selects: $keys_selects, place_holder: 'Apellido Materno');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['am_preview']->disabled = true;

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'nss',
            keys_selects: $keys_selects, place_holder: 'NSS');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 12, key: 'nss_preview',
            keys_selects: $keys_selects, place_holder: 'NSS');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['nss_preview']->disabled = true;

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'salario_diario_preview',
            keys_selects: $keys_selects, place_holder: 'Salario Diario');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['salario_diario_preview']->disabled = true;

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'salario_diario_integrado_preview',
            keys_selects: $keys_selects, place_holder: 'Salario Diario Integrado');
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }
        $keys_selects['salario_diario_integrado_preview']->disabled = true;

        $keys_selects = parent::key_selects_txt($keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }


        return  $keys_selects;
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

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $r_modifica = $this->init_modifica();
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al generar salida de template', data: $r_modifica, header: $header, ws: $ws);
        }

        $keys_selects = $this->init_selects_inputs();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar selects', data: $keys_selects, header: $header,
                ws: $ws);
        }
        $keys_selects['em_empleado_id']->cols = 10;

        $keys_selects['em_empleado_id']->id_selected = $this->registro['em_empleado_id'];
        $keys_selects['im_tipo_movimiento_id']->id_selected = $this->registro['im_tipo_movimiento_id'];
        $keys_selects['em_registro_patronal_id']->id_selected = $this->registro['em_registro_patronal_id'];

        $base = $this->base_upd(keys_selects: $keys_selects, params: array(), params_ajustados: array());
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al integrar base', data: $base, header: $header, ws: $ws);
        }

        return $r_modifica;
    }

    public function sube_archivo(bool $header, bool $ws = false)
    {
        $r_alta = $this->init_alta();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al inicializar alta', data: $r_alta, header: $header, ws: $ws);
        }

        $inputs = $this->inputs(keys_selects: array());
        if (errores::$error) {
            return $this->retorno_error(
                mensaje: 'Error al obtener inputs', data: $inputs, header: $header, ws: $ws);
        }

        return $r_alta;
    }

}
