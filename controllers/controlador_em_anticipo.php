<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace tglobally\tg_imss\controllers;



use gamboamartin\comercial\models\com_cliente;
use gamboamartin\empleado\models\em_anticipo;
use gamboamartin\errores\errores;
use gamboamartin\plugins\exportador;
use PDO;
use stdClass;
use gamboamartin\system\system;
use tglobally\tg_nomina\controllers\Reporte_Template;

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
        $categoria = "";

        $fecha_inicio = date('Y/m/d', strtotime("01-01-2000"));
        $fecha_final = date('Y/m/d');

        if (isset($_POST['fecha_inicio']) && $_POST['fecha_inicio'] !== "") {
            $fecha_inicio = date('Y/m/d', strtotime($_POST['fecha_inicio']));
        }

        if (isset($_POST['fecha_final']) && $_POST['fecha_final'] !== "") {
            $fecha_final = date('Y/m/d', strtotime($_POST['fecha_final']));
        }

        if (isset($_POST['categorias']) && isset($_POST['org_empresa_id']) &&
            $_POST['categorias'] !== "" && $_POST['org_empresa_id'] !== "" && $_POST['org_empresa_id'] > 0) {
            $filtro[$_POST['categorias'] . ".id"] = $_POST['org_empresa_id'];
            $categoria = $_POST['categorias'];
        }

        $filtro_rango['em_anticipo.fecha_prestacion'] = ['valor1' => $fecha_inicio, 'valor2' => $fecha_final];

        $extra_join["org_empresa"]['key'] = "id";
        $extra_join["org_empresa"]['enlace'] = "org_sucursal";
        $extra_join["org_empresa"]['key_enlace'] = "org_empresa_id";
        $extra_join["org_empresa"]['renombre'] = "org_empresa";

        $extra_join["adm_usuario"]['key'] = "id";
        $extra_join["adm_usuario"]['enlace'] = "em_anticipo";
        $extra_join["adm_usuario"]['key_enlace'] = "usuario_alta_id";
        $extra_join["adm_usuario"]['renombre'] = "adm_usuario";

        return array("extra_join" => $extra_join, "filtro" => $filtro, "filtro_rango" => $filtro_rango,
            "categoria" => $categoria, "fecha_inicio" => $fecha_inicio,
            "fecha_final" => $fecha_final);
    }

    private function fill_data(array $anticipos): array
    {
        $registros = array();

        foreach ($anticipos as $anticipo) {

            $extra_join["com_sucursal"]['key'] = "com_cliente_id";
            $extra_join["com_sucursal"]['enlace'] = "com_cliente";
            $extra_join["com_sucursal"]['key_enlace'] = "id";
            $extra_join["com_sucursal"]['renombre'] = "com_sucursal";

            $extra_join["tg_empleado_sucursal"]['key'] = "com_sucursal_id";
            $extra_join["tg_empleado_sucursal"]['enlace'] = "com_sucursal";
            $extra_join["tg_empleado_sucursal"]['key_enlace'] = "id";
            $extra_join["tg_empleado_sucursal"]['renombre'] = "tg_empleado_sucursal";

            $cliente = (new com_cliente($this->link))->filtro_and(extra_join: $extra_join,
                filtro: array("tg_empleado_sucursal.em_empleado_id" => $anticipo['em_empleado_id']),limit: 1);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al obtener cliente', data: $cliente);
                print_r($error);
                die('Error');
            }

            $registro = [
                $anticipo['em_empleado_nss'],
                $anticipo['em_empleado_codigo'],
                $anticipo['em_empleado_nombre_completo'],
                $anticipo['em_registro_patronal_descripcion'],
                $anticipo['em_anticipo_descripcion'],
                $anticipo['em_anticipo_monto'],
                0,
                $anticipo['em_anticipo_abonos'],
                $anticipo['em_anticipo_saldo'],
                $anticipo['adm_usuario_nombre'].' '.$anticipo['adm_usuario_ap'],
                $anticipo['em_anticipo_fecha_alta'],
                $anticipo['em_anticipo_comentarios'],
                ($cliente->n_registros <= 0)? "SIN REGISTRO RELACIONADO":$cliente->registros[0]['com_cliente_descripcion'],
            ];
            $registros[] = $registro;
        }

        return $registros;
    }

    private function maqueta_salida(string $categoria, string $categoria_value, string $periodo, int $total_registros,
                                    array $registros): array
    {
        $tabla['detalles'] = [
            ["titulo" => $categoria, 'valor' => $categoria_value],
            ["titulo" => 'PERIODO:', 'valor' => $periodo],
            ["titulo" => '# REGISTROS:', 'valor' => $total_registros]
        ];

        $tabla['headers'] = ['NSS', 'ID', 'NOMBRE', 'REGISTRO PATRONAL', 'CONCEPTO', 'IMPORTE', 'DESC. NOMINAL', 'PAGOS',
            'SALDO', 'EJECUTIVO IMSS', 'FECHA/HORA CAPTURA', 'COMENTARIOS', 'CLIENTE'];
        $tabla['data'] = $registros;
        $tabla['startRow'] = 4;
        $tabla['startColumn'] = "A";

        return array($tabla);
    }

    public function exportar_anticipos(bool $header, bool $ws = false): array|stdClass
    {
        $filtros = $this->get_filtros();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'No se pudo generar los filtros', data: $filtros, header: $header, ws: $ws);
        }

        $anticipos = (new em_anticipo($this->link))->filtro_and(extra_join: $filtros['extra_join'],
            filtro: $filtros['filtro'], filtro_rango: $filtros['filtro_rango']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'No se pudo obtener los anticipos', data: $anticipos, header: $header, ws: $ws);
        }

        $data = array();

        $registros = $this->fill_data(anticipos: $anticipos->registros);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al maquetar datos', data: $registros);
            print_r($error);
            die('Error');
        }

        switch ($filtros['categoria']) {
            case "em_empleado":
                $categoria = "EMPLEADO";
                $categoria_value = $anticipos->registros[0]["em_empleado_nombre_completo"];
                break;
            case "org_empresa":
                $categoria = "EMPRESA";
                $categoria_value = $anticipos->registros[0]["org_sucursal_org_empresa_id"];
                break;
            case "adm_usuario":
                $categoria = "USUARIO";
                $categoria_value = $anticipos->registros[0]["adm_usuario_nombre"];
                break;
            default:
                $categoria = "GENERALES";
                $categoria_value = "SALIDA GENERAL";
        }

        $periodo = $filtros['fecha_inicio']."  -  ".$filtros['fecha_final'];

        $data["REPORTE GENERAL"] = $this->maqueta_salida(categoria: $categoria,
            categoria_value: $categoria_value, periodo: $periodo, total_registros: $anticipos->n_registros,
            registros: $registros);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al maquetar salida de datos', data: $data);
            print_r($error);
            die('Error');
        }

        $name = "REPORTE DE ANTICIPOS_$categoria_value";

        $resultado = (new exportador())->exportar_template(header: $header, path_base: $this->path_base, name: $name,
            data: $data, styles: Reporte_Template::REPORTE_GENERAL);
        if (errores::$error) {
            $error = $this->errores->error('Error al generar xls', $resultado);
            if (!$header) {
                return $error;
            }
            print_r($error);
            die('Error');
        }

        print_r($data);
        exit();
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
