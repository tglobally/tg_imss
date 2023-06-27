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

class controlador_em_empleado extends \tglobally\tg_empleado\controllers\controlador_em_empleado {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct($link, $paths_conf);
        $this->seccion_titulo = "Remunerados";
        $this->titulo_accion = "Listado de Remunerados";
    }

    protected function init_datatable(): stdClass
    {
        $columns["em_empleado_id"]["titulo"] = "Id";
        $columns["em_empleado_nombre"]["titulo"] = "Nombre";
        $columns["em_empleado_nombre"]["campos"] = array("em_empleado_ap","em_empleado_am");
        $columns["em_empleado_rfc"]["titulo"] = "Rfc";
        $columns["em_empleado_nss"]["titulo"] = "NSS";
        $columns["org_puesto_descripcion"]["titulo"] = "Puesto";
        $columns["em_empleado_n_cuentas_bancarias"]["titulo"] = "Cuentas Bancarias";

        $filtro = array("em_empleado.id","em_empleado.nombre","em_empleado.ap","em_empleado.am","em_empleado.rfc",
            "em_empleado_nombre_completo","em_empleado_nombre_completo_inv", "em_empleado.nss","org_puesto.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;
        $datatables->menu_active = true;

        return $datatables;
    }

    public function modifica(bool $header, bool $ws = false, array $keys_selects =  array()): array|stdClass
    {
        $r_modifica = parent::modifica($header, $ws, $keys_selects);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $this->titulo_accion = "Modifica Remunerado";

        return $r_modifica;
    }

}
