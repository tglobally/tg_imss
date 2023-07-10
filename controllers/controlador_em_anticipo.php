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

    public string $link_em_anticipo_exportar_anticipos = '';

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

        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
            print_r($error);
            die('Error');
        }

    }

    public function get_filtros(): array|stdClass{
        $filtro = array();
        $filtro_rango = array();
        $extra_join = array();
        $in = array();
        $categoria = "";

        $fecha_inicio = date('Y/m/d', strtotime("01-01-2000"));
        $fecha_final = date('Y/m/d');

        if (isset($_POST['fecha_inicio']) && $_POST['fecha_inicio'] !== "") {
            $fecha_inicio = date('Y/m/d', strtotime($_POST['fecha_inicio']));
        }

        if (isset($_POST['fecha_final']) && $_POST['fecha_final'] !== "") {
            $fecha_final = date('Y/m/d', strtotime($_POST['fecha_final']));
        }

        if (isset($_POST['em_registro_patronal_id']) && $_POST['em_registro_patronal_id'] !== "" &&
            $_POST['em_registro_patronal_id'] > 0) {
            $filtro["em_registro_patronal.id"] = $_POST['em_registro_patronal_id'];
        }

        if (isset($_POST['categorias']) && isset($_POST['categoria_id']) &&
            $_POST['categorias'] !== "" && $_POST['categoria_id'] !== "" && $_POST['categoria_id'] > 0) {
            $filtro[$_POST['categorias'] . ".id"] = $_POST['categoria_id'];
            $categoria = $_POST['categorias'];
        }

        if (isset($_POST['nom_clasificacion_id']) && !empty($_POST['nom_clasificacion_id'])){
            $in['llave'] = "nom_clasificacion.id";
            $in['values'] = array();

            foreach ($_POST['nom_clasificacion_id'] as $row){
                $in['values'][] = $row;
            }
        }

        $filtro_rango['nom_nomina.fecha_pago'] = ['valor1' => $fecha_inicio, 'valor2' => $fecha_final];

        $extra_join["tg_empleado_sucursal"]['key'] = "em_empleado_id";
        $extra_join["tg_empleado_sucursal"]['enlace'] = "em_empleado";
        $extra_join["tg_empleado_sucursal"]['key_enlace'] = "id";
        $extra_join["tg_empleado_sucursal"]['renombre'] = "tg_empleado_sucursal";

        $extra_join["com_sucursal"]['key'] = "id";
        $extra_join["com_sucursal"]['enlace'] = "tg_empleado_sucursal";
        $extra_join["com_sucursal"]['key_enlace'] = "com_sucursal_id";
        $extra_join["com_sucursal"]['renombre'] = "com_sucursal";

        $extra_join["com_cliente"]['key'] = "id";
        $extra_join["com_cliente"]['enlace'] = "com_sucursal";
        $extra_join["com_cliente"]['key_enlace'] = "com_cliente_id";
        $extra_join["com_cliente"]['renombre'] = "com_cliente";

        $columnas = array('nom_nomina_id', 'org_sucursal_dp_calle_pertenece_id', 'em_empleado_dp_calle_pertenece_id',
            'fc_factura_id', 'fc_factura_com_sucursal_id', 'nom_periodo_fecha_inicial_pago', 'nom_periodo_fecha_inicial_pago',
            'cat_sat_periodicidad_pago_nom_n_dias', 'em_empleado_salario_diario', 'em_registro_patronal_cat_sat_isn_id',
            'em_empleado_id', 'em_empleado_fecha_inicio_rel_laboral', 'fc_factura_folio', 'em_empleado_ap', 'em_empleado_am',
            'em_empleado_nombre', 'em_empleado_rfc', 'em_empleado_nss', 'em_registro_patronal_descripcion',
            'org_empresa_razon_social', 'em_empleado_salario_diario_integrado', 'org_empresa_id', 'com_cliente_razon_social',
            "em_empleado_nombre_completo");

        return array("columnas" => $columnas, "extra_join" => $extra_join, "filtro" => $filtro, "in" => $in,
            "filtro_rango" => $filtro_rango, "categoria" => $categoria, "fecha_inicio" => $fecha_inicio,
            "fecha_final" => $fecha_final);
    }

    public function exportar_anticipos(bool $header, bool $ws = false): array|stdClass
    {




        header('Location:' . $this->link_lista);
        exit;
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

    protected function init_links(): array|string
    {
        $links = parent::init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "em_anticipo", accion: "exportar_anticipos");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link reporte_ejecutivo', data: $link);
            print_r($error);
            exit;
        }
        $this->link_em_anticipo_exportar_anticipos = $link;

        return $link;
    }

    public function init_selects_inputs(): array
    {
        $keys_selects = parent::init_selects_inputs();
        if (errores::$error) {
            return $this->errores->error(mensaje: 'Error al icializar selects', data: $keys_selects);
        }

        $keys_selects = $this->init_selects(keys_selects: $keys_selects, key: "org_empresa_id", label: "Empresa:",
            cols: 12);
        $keys_selects['org_empresa_id']->required = false;


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
