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

class controlador_em_anticipo extends \tglobally\tg_empleado\controllers\controlador_em_anticipo {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct($link, $paths_conf);
        $this->seccion_titulo = "Anticipos";
        $this->titulo_accion = "Listado de Anticipos";

        $acciones = $this->define_acciones_menu(acciones: array("Importar Registros" => $this->link_em_anticipo_importar_anticipos));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al integrar acciones para el menu', data: $acciones);
            print_r($error);
            die('Error');
        }

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

}
