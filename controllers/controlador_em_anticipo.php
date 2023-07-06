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
use gamboamartin\system\system;

class controlador_em_anticipo extends \tglobally\tg_empleado\controllers\controlador_em_anticipo {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct($link, $paths_conf);
        $this->seccion_titulo = "Anticipos";
        $this->titulo_accion = "Listado de Anticipos";

        $acciones = $this->define_acciones_menu(acciones: array("Alta" => $this->link_alta,
            "Importar Registros" => $this->link_em_anticipo_importar_anticipos));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al integrar acciones para el menu', data: $acciones);
            print_r($error);
            die('Error');
        }

    }

    protected function campos_view(): array
    {
        $keys = new stdClass();
        $keys->inputs = array('codigo', 'descripcion', 'monto', 'n_pagos', 'comentarios');
        $keys->fechas = array('fecha_prestacion', 'fecha_inicio_descuento', 'fecha_inicio', "fecha_final");
        $keys->selects = array();

        $init_data = array();
        $init_data['em_tipo_anticipo'] = "gamboamartin\\empleado";
        $init_data['em_empleado'] = "gamboamartin\\empleado";
        $init_data['em_tipo_descuento'] = "gamboamartin\\empleado";
        $init_data['org_empresa'] = "gamboamartin\\organigrama";

        $campos_view = $this->campos_view_base(init_data: $init_data, keys: $keys);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al inicializar campo view', data: $campos_view);
        }

        return $campos_view;
    }

    protected function init_datatable(): stdClass
    {
        $columns["em_anticipo_id"]["titulo"] = "Id";
        $columns["org_sucursal_descripcion"]["titulo"] = "Empresa";
        $columns["em_registro_patronal_descripcion"]["titulo"] = "Registro Patronal";
        $columns["em_empleado_nombre"]["titulo"] = "Remunerado";
        $columns["em_empleado_nombre"]["campos"] = array("em_empleado_ap", "em_empleado_am");
        $columns["em_anticipo_monto"]["titulo"] = "Amortización";

        $filtro = array("em_anticipo.id", "em_empleado.nss", "em_empleado.nombre", "em_empleado.ap", "em_empleado.am",
            "em_tipo_anticipo.descripcion", "em_anticipo.monto", "em_anticipo.fecha_prestacion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        $datatables->menu_active = true;

        return $datatables;
    }

    public function init_selects_inputs(): array
    {
        $keys_selects = parent::init_selects_inputs();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al icializar selects', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "org_empresa_id", label: "Empresa:",
            cols: 12);

        return $keys_selects;
    }

    protected function key_selects_txt(array $keys_selects): array
    {
        $keys_selects = parent::key_selects_txt($keys_selects);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al icializar selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_inicio',
            keys_selects: $keys_selects, place_holder: 'Fecha Inicio', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        $keys_selects = (new \base\controller\init())->key_select_txt(cols: 6, key: 'fecha_final',
            keys_selects: $keys_selects, place_holder: 'Fecha Final', required: false);
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al maquetar key_selects', data: $keys_selects);
        }

        return $keys_selects;
    }

    public function lista(bool $header, bool $ws = false): array
    {
        $alta = parent::alta($header, $ws);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar sidebar', data: $alta);
            print_r($error);
            die('Error');
        }

        return (array)$alta;
    }

}
