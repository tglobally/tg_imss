<?php

namespace tglobally\tg_imss\controllers;

use gamboamartin\errores\errores;

use gamboamartin\system\datatables;
use gamboamartin\system\system;
use html\nom_nomina_html;
use PDO;
use stdClass;
use tglobally\template_tg\html;

class controlador_nom_nomina extends \tglobally\tg_nomina\controllers\controlador_nom_nomina
{
    public string $link_nom_nomina_exportar_nominas = '';

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct(link: $link, paths_conf: $paths_conf);
        $this->seccion_titulo = "Nominas";
        $this->titulo_accion = "Listado de Nominas";
        $this->lista_get_data = true;

        $datatables = $this->init_datatable();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar datatable', data: $datatables);
            print_r($error);
            die('Error');
        }

        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
            print_r($error);
            die('Error');
        }

        $acciones = $this->define_acciones_menu(acciones: array("Exportar Nominas" => $this->link_nom_nomina_exportar_nominas));
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al integrar acciones para el menu', data: $acciones);
            print_r($error);
            die('Error');
        }

        $data_for_datable = (new datatables())->datatable_base_init(
            datatables: $datatables,link: $this->link,rows_lista: $this->rows_lista,seccion: $this->seccion,
            not_actions: $this->not_actions);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al maquetar datos para tables ', data: $data_for_datable);
            print_r($error);
            die('Error');
        }

        $this->datatables[0]['columns'] = array();
        $this->datatables[0]['columns'][0] = new stdClass();
        $this->datatables[0]['columns'][0]->title = "Id";
        $this->datatables[0]['columns'][0]->data = "nom_nomina_id";

        $this->datatables[0]['columns'][1] = new stdClass();
        $this->datatables[0]['columns'][1]->title = "RFC";
        $this->datatables[0]['columns'][1]->data = "em_empleado_rfc";

        $this->datatables[0]['columns'][2] = new stdClass();
        $this->datatables[0]['columns'][2]->title = "Nombre";
        $this->datatables[0]['columns'][2]->data = "em_empleado_nombre";

        $this->datatables[0]['columns'][3] = new stdClass();
        $this->datatables[0]['columns'][3]->title = "AP";
        $this->datatables[0]['columns'][3]->data = "em_empleado_ap";

        $this->datatables[0]['columns'][4] = new stdClass();
        $this->datatables[0]['columns'][4]->title = "AM";
        $this->datatables[0]['columns'][4]->data = "em_empleado_am";

        $this->datatables[0]['columnDefs'] = array();
        $this->datatables[0]['columnDefs'][0] = new stdClass();
        $this->datatables[0]['columnDefs'][0]->targets = 0;
        $this->datatables[0]['columnDefs'][0]->data = null;
        $this->datatables[0]['columnDefs'][0]->type = 'text';
        $this->datatables[0]['columnDefs'][0]->rendered = Array ( 'nom_nomina_id' ) ;

        $this->datatables[0]['columnDefs'][1] = new stdClass();
        $this->datatables[0]['columnDefs'][1]->targets = 1;
        $this->datatables[0]['columnDefs'][1]->data = null;
        $this->datatables[0]['columnDefs'][1]->type = 'text';
        $this->datatables[0]['columnDefs'][1]->rendered = Array ( 'em_empleado_rfc' ) ;

        $this->datatables[0]['columnDefs'][2] = new stdClass();
        $this->datatables[0]['columnDefs'][2]->targets = 2;
        $this->datatables[0]['columnDefs'][2]->data = null;
        $this->datatables[0]['columnDefs'][2]->type = 'text';
        $this->datatables[0]['columnDefs'][2]->rendered = Array ( 'em_empleado_nombre' ) ;

        $this->datatables[0]['columnDefs'][3] = new stdClass();
        $this->datatables[0]['columnDefs'][3]->targets = 3;
        $this->datatables[0]['columnDefs'][3]->data = null;
        $this->datatables[0]['columnDefs'][3]->type = 'text';
        $this->datatables[0]['columnDefs'][3]->rendered = Array ( 'em_empleado_ap' ) ;

        $this->datatables[0]['columnDefs'][4] = new stdClass();
        $this->datatables[0]['columnDefs'][4]->targets = 4;
        $this->datatables[0]['columnDefs'][4]->data = null;
        $this->datatables[0]['columnDefs'][4]->type = 'text';
        $this->datatables[0]['columnDefs'][4]->rendered = Array ( 'em_empleado_am' ) ;

        $this->datatables[0]['filtro'] = array();
        $this->datatables[0]['filtro'] = $datatables->filtro;
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

    protected function init_datatable(): stdClass
    {
        $columns = array();
        $columns['nom_nomina_id']['titulo'] = 'Id';
        $columns['em_empleado_rfc']['titulo'] = 'RFC';


        $filtro = array("nom_nomina.id", "em_empleado.rfc", "em_empleado.nombres", "em_empleado.ap", "em_empleado.am",
            "em_empleado.nombre_completo", "nom_nomina.fecha_pago", "org_empresa.descripcion");

        $datatables = new stdClass();
        $datatables->columns = $columns;
        $datatables->filtro = $filtro;

        return $datatables;
    }

    private function init_links(): array|string
    {
        $links = $this->obj_link->genera_links(controler: $this);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al generar links', data: $links);
            print_r($error);
            exit;
        }

        $link = $this->obj_link->get_link(seccion: "nom_nomina", accion: "exportar_nominas");
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al recuperar link exportar_nominas', data: $link);
            print_r($error);
            exit;
        }
        $this->link_nom_nomina_exportar_nominas = $link;

        return $link;
    }

    public function exportar_nominas(bool $header, bool $ws = false): array|stdClass
    {

        print_r($_POST);exit();


        header('Location:' . $this->link_lista);
        exit;
    }
}
