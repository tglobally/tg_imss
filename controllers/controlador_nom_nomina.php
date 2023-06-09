<?php

namespace tglobally\tg_imss\controllers;

use base\orm\modelo;
use DateTime;
use gamboamartin\cat_sat\models\cat_sat_isn;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\errores\errores;

use gamboamartin\facturacion\models\fc_cfdi_sellado;
use gamboamartin\nomina\models\em_empleado;
use gamboamartin\nomina\models\nom_par_deduccion;
use gamboamartin\nomina\models\nom_par_otro_pago;
use gamboamartin\nomina\models\nom_par_percepcion;
use gamboamartin\plugins\exportador;
use gamboamartin\system\datatables;
use gamboamartin\system\system;
use html\nom_nomina_html;
use PDO;
use stdClass;
use tglobally\template_tg\html;
use tglobally\tg_nomina\controllers\Reporte_Template;
use tglobally\tg_nomina\models\nom_nomina;
use tglobally\tg_nomina\models\tg_manifiesto;

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
            datatables: $datatables, link: $this->link, rows_lista: $this->rows_lista, seccion: $this->seccion,
            not_actions: $this->not_actions);
        if (errores::$error) {
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
        $this->datatables[0]['columnDefs'][0]->rendered = array('nom_nomina_id');

        $this->datatables[0]['columnDefs'][1] = new stdClass();
        $this->datatables[0]['columnDefs'][1]->targets = 1;
        $this->datatables[0]['columnDefs'][1]->data = null;
        $this->datatables[0]['columnDefs'][1]->type = 'text';
        $this->datatables[0]['columnDefs'][1]->rendered = array('em_empleado_rfc');

        $this->datatables[0]['columnDefs'][2] = new stdClass();
        $this->datatables[0]['columnDefs'][2]->targets = 2;
        $this->datatables[0]['columnDefs'][2]->data = null;
        $this->datatables[0]['columnDefs'][2]->type = 'text';
        $this->datatables[0]['columnDefs'][2]->rendered = array('em_empleado_nombre');

        $this->datatables[0]['columnDefs'][3] = new stdClass();
        $this->datatables[0]['columnDefs'][3]->targets = 3;
        $this->datatables[0]['columnDefs'][3]->data = null;
        $this->datatables[0]['columnDefs'][3]->type = 'text';
        $this->datatables[0]['columnDefs'][3]->rendered = array('em_empleado_ap');

        $this->datatables[0]['columnDefs'][4] = new stdClass();
        $this->datatables[0]['columnDefs'][4]->targets = 4;
        $this->datatables[0]['columnDefs'][4]->data = null;
        $this->datatables[0]['columnDefs'][4]->type = 'text';
        $this->datatables[0]['columnDefs'][4]->rendered = array('em_empleado_am');

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
        $datos = $_POST;
        $filtro = array();
        $extra_join = array();
        $categoria = "";
        $fecha_inicio = date('d/m/Y', strtotime("01-01-2000"));
        $fecha_final = date('d/m/Y', strtotime("01-01-2000"));

        if (isset($datos['categorias']) && isset($datos['categoria_id']) && $datos['categorias'] !== "" && $datos['categoria_id'] !== "") {
            $filtro[$datos['categorias'] . ".id"] = $datos['categoria_id'];
            $categoria = $datos['categorias'];

            if ($datos['categorias'] === "com_cliente") {
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
            }
        }

        if (isset($datos['registro_patronal']) && $datos['registro_patronal'] !== "") {
            $filtro["em_registro_patronal.id"] = $datos['registro_patronal'];
        }

        if (isset($datos['fecha_inicio']) && $datos['fecha_inicio'] !== "") {
            $fecha_inicio = date('d/m/Y', strtotime($datos['fecha_inicio']));
        }

        if (isset($datos['fecha_final']) && $datos['fecha_final'] !== "") {
            $fecha_final = date('d/m/Y', strtotime($datos['fecha_final']));
        }

        $nominas = (new nom_nomina($this->link))->filtro_and(extra_join: $extra_join, filtro: $filtro);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'Error al obtener nominas', data: $nominas, header: $header, ws: $ws);
        }

        $registros = $this->fill_data(nominas: $nominas->registros, fecha_inicio: $fecha_inicio, fecha_fin: $fecha_final);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al maquetar datos', data: $registros);
            print_r($error);
            die('Error');
        }


        $categoria_value = "";

        if ($categoria !== "") {
            switch ($categoria) {
                case "em_empleado":
                    $categoria = "EMPLEADO";
                    $categoria_value = $nominas->registros[0]["em_empleado_nombre_completo"];
                    break;
                case "org_empresa":
                    $categoria = "EMPRESA";
                    $categoria_value = $nominas->registros[0]["org_empresa_razon_social"];
                    break;
                case "com_cliente":
                    $categoria = "CLIENTE";
                    $categoria_value = $nominas->registros[0]["com_cliente_razon_social"];
                    break;
            }
        }

        $periodo = "$fecha_inicio - $fecha_final";

        $data["REPORTE NOMINAS"] = $this->maqueta_salida(categoria: $categoria, categoria_value: $categoria_value, periodo: $periodo, remunerados: 0,
            total_registros: 1, registros: $registros);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al maquetar salida de datos', data: $data);
            print_r($error);
            die('Error');
        }

        $name = "REPORTE DE NOMINAS_$categoria";

        $resultado = (new exportador())->exportar_template(header: $header, path_base: $this->path_base, name: $name,
            data: $data, styles: Reporte_Template::REPORTE_NOMINA);
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


        header('Location:' . $this->link_lista);
        exit;
    }

    private function maqueta_salida(string $categoria, string $categoria_value, string $periodo, int $remunerados,
                                    int    $total_registros, array $registros): array
    {
        $tabla['detalles'] = [
            ["titulo" => $categoria, 'valor' => $categoria_value],
            ["titulo" => 'PERIODO:', 'valor' => $periodo],
            ["titulo" => '# REMUNERADOS:', 'valor' => $remunerados],
            ["titulo" => '# REGISTROS:', 'valor' => $total_registros]
        ];

        $tabla['headers'] = ['FOLIO NÓMINA', 'ID REM', 'NOMBRE', 'RFC', 'NSS', 'REGISTRO PATRONAL', 'UBICACIÓN RP', 'EMPRESA',
            'UBICACIÓN TRABAJADOR', 'MES', 'PERIODO DE PAGO', 'FOLIO FISCAL IMSS', 'ESTATUS', 'SD', 'FI', 'SDI', 'SUELDO',
            'SUBSIDIO', 'PRIMA DOMINICAL', 'VACACIONES', 'SEPTIMO DÍA', 'COMPENSACIÓN', 'DESPENSA', 'OTROS INGRESOS',
            'DEVOLUCIÓN INFONAVIT', 'GRAVADO', 'EXENTO', 'GRAVADO', 'EXENTO', 'GRAVADO', 'EXENTO', 'GRAVADO', 'EXENTO',
            'GRAVADO', 'EXENTO', 'GRAVADO', 'EXENTO', 'TOTAL PERCEPCIONES', 'BASE GRAVABLE', 'RETENCION ISR',
            'RETENCION IMSS', 'INFONAVIT', 'FONACOT', 'PENSION ALIMENTICIA', 'OTROS DESCUENTOS', 'DESCUENTO COMEDOR ',
            'TOTAL DEDUCCIONES', 'NETO IMSS', 'NETO HABERES', 'BASE ISN', 'TASA ISN', 'IMPORTE ISN', 'CLIENTE'];
        $tabla['data'] = $registros;
        $tabla['startRow'] = 5;
        $tabla['startColumn'] = "A";

        $tabla2['headers'] = ['PRIMA VACACIONAL (15 UMAS)', 'GRATIFICACION ( 30 UMAS )', 'AGUINALDO ( 15 UMAS )',
            'DIA FESTIVO', 'DESCANSO LABORADO', 'HORAS EXTRAS ( 5 UMAS POR SEMANA)'];
        $tabla2['mergeheaders'] = array('PRIMA VACACIONAL (15 UMAS)' => 2, 'GRATIFICACION ( 30 UMAS )' => 2,
            'AGUINALDO ( 15 UMAS )' => 2, 'DIA FESTIVO' => 2, 'DESCANSO LABORADO' => 2,
            'HORAS EXTRAS ( 5 UMAS POR SEMANA)' => 2);
        $tabla2['data'] = array();
        $tabla2['startRow'] = 4;
        $tabla2['startColumn'] = "Z";


        return array($tabla2, $tabla);
    }


    private function fill_data(array $nominas, string $fecha_inicio, string $fecha_fin): array
    {
        $meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE',
            'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');

        $registros = array();
        $total_deducciones = 0;
        $total_percepciones = 0;
        $total_otros_ingresos = 0;
        $total_otros_descuentos = 0;

        foreach ($nominas as $nomina) {
            $org_sucursal_estado = (new dp_calle_pertenece($this->link))->registro(registro_id: $nomina['org_sucursal_dp_calle_pertenece_id']);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener el estado', data: $org_sucursal_estado);
            }

            $em_empleado_estado = (new dp_calle_pertenece($this->link))->registro(registro_id: $nomina['em_empleado_dp_calle_pertenece_id']);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener el estado', data: $em_empleado_estado);
            }

            $timbrado = (new fc_cfdi_sellado($this->link))->filtro_and(filtro: array("fc_factura_id" => $nomina['fc_factura_id']), limit: 1);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener cfdi sellado', data: $timbrado);
            }

            $cliente_nomina = (new com_sucursal($this->link))->registro(registro_id: $nomina['fc_factura_com_sucursal_id']);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener cliente de la nomina', data: $cliente_nomina);
            }

            $campos['subsidio'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Subsidio');
            $campos['prima_dominical'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Prima Dominical');
            $campos['vacaciones'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Vacaciones');
            $campos['septimo_dia'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Septimo Dia');
            $campos['compensacion'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Compensacion');
            $campos['despensa'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Despensa');
            $campos['otros_ingresos'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Otros Ingresos');
            $campos['devolucion_infonavit'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Devolucion Infonavit');
            $campos['prima_vacacional'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Prima Vacacional');
            $campos['gratificacion'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Gratificacion');
            $campos['aguinaldo'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Aguinaldo');
            $campos['dia_festivo'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Dia Festivo Laborado');
            $campos['dia_descanso'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_percepcion.descripcion" => 'Dia de Descanso');
            $campos['horas_extras'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "cat_sat_tipo_percepcion_nom.descripcion" => 'Horas extras');

            $percepciones = $this->get_totales(entidad: new nom_par_percepcion($this->link), campos: $campos);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener totales de percepciones', data: $percepciones);
            }

            $campos_deduccion['infonavit'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_deduccion.descripcion" => 'INFONAVIT');

            $campos_deduccion['isr'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_deduccion.descripcion" => 'ISR');

            $campos_deduccion['imss'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_deduccion.descripcion" => 'IMSS');

            $campos_deduccion['fonacot'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_deduccion.descripcion" => 'FONACOT');

            $campos_deduccion['pension_alimenticia'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_deduccion.descripcion" => 'PENSION ALIMENTICIA');

            $campos_deduccion['otros_descuentos'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_deduccion.descripcion" => 'Otros Descuentos');

            $campos_deduccion['descuento_comedor'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_deduccion.descripcion" => 'DESCUENTO COMEDOR');

            $deducciones = $this->get_totales(entidad: new nom_par_deduccion($this->link), campos: $campos_deduccion);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener totales de deducciones', data: $deducciones);
            }


            $campos_otro_pago['subsidios'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_otro_pago.es_subsidio" => 'activo');
            $campos_otro_pago['otros_ingresos'] = array("nom_nomina_id" => $nomina['nom_nomina_id'],
                "nom_otro_pago.es_subsidio" => 'inactivo');
            $otros_pagos = $this->get_totales(entidad: new nom_par_otro_pago($this->link), campos: $campos_otro_pago);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener totales de otros pagos', data: $otros_pagos);
            }


            $periodo = "PERIODO";

            $sueldo = 1;

            $percepciones['total'] += $sueldo + $otros_pagos['subsidios']['total'];

            $base_gravable = $sueldo + $percepciones['vacaciones']['total'] + $percepciones['septimo_dia']['total'] +
                $percepciones['compensacion']['total'] + $percepciones['despensa']['total'] +
                $otros_pagos['otros_ingresos']['total'] + $percepciones['prima_vacacional']['gravado'] +
                $percepciones['gratificacion']['gravado'] + $percepciones['aguinaldo']['gravado'] +
                $percepciones['dia_festivo']['gravado'] + $percepciones['dia_descanso']['gravado'] +
                $percepciones['horas_extras']['gravado'];

            $neto_imss = $percepciones['total'] - $deducciones['total'];

            $base_isn = $percepciones['total'] - $otros_pagos['subsidios']['total'];

            $cat_sat_isn = (new cat_sat_isn($this->link))->registro(registro_id: $nomina['em_registro_patronal_cat_sat_isn_id'],
                columnas: array("cat_sat_isn_porcentaje"));
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener cat_sat_isn', data: $cat_sat_isn);
            }

            $tasa_isn = $cat_sat_isn['cat_sat_isn_porcentaje'] / 100;
            $importe_isn = $base_isn * $tasa_isn;
            $cliente = $cliente_nomina['com_sucursal_descripcion'];

            $uuid = "";

            if ($timbrado->n_registros > 0) {
                $uuid = $timbrado->registros[0]['fc_cfdi_sellado_uuid'];
            }

            $fi = (new em_empleado($this->link))->obten_factor(em_empleado_id: $nomina['em_empleado_id'],
                fecha_inicio_rel: $nomina['em_empleado_fecha_inicio_rel_laboral']);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener factor de ingracion', data: $fi);
            }



            $registro = [
                $nomina['fc_factura_folio'],
                $nomina['em_empleado_id'],
                $nomina['em_empleado_ap'] . ' ' . $nomina['em_empleado_am'] . ' ' . $nomina['em_empleado_nombre'],
                $nomina['em_empleado_rfc'],
                $nomina['em_empleado_nss'],
                $nomina['em_registro_patronal_descripcion'],
                $org_sucursal_estado['dp_estado_descripcion'],
                "empresa",
                $em_empleado_estado['dp_estado_descripcion'],
                $meses[1],
                $periodo,
                $uuid,
                (!empty($uuid)) ? 'TIMBRADO' : '',
                $nomina['em_empleado_salario_diario'],
                $fi,
                $nomina['em_empleado_salario_diario_integrado'],
                $sueldo,
                $percepciones['subsidio']['total'],
                $percepciones['prima_dominical']['total'],
                $percepciones['vacaciones']['total'],
                $percepciones['septimo_dia']['total'],
                $percepciones['compensacion']['total'],
                $percepciones['despensa']['total'],
                $percepciones['otros_ingresos']['total'],
                $percepciones['devolucion_infonavit']['total'],
                $percepciones['prima_vacacional']['gravado'],
                $percepciones['prima_vacacional']['exento'],
                $percepciones['gratificacion']['gravado'],
                $percepciones['gratificacion']['exento'],
                $percepciones['aguinaldo']['gravado'],
                $percepciones['aguinaldo']['exento'],
                $percepciones['dia_festivo']['gravado'],
                $percepciones['dia_festivo']['exento'],
                $percepciones['dia_descanso']['gravado'],
                $percepciones['dia_descanso']['exento'],
                $percepciones['horas_extras']['gravado'],
                $percepciones['horas_extras']['exento'],
                $percepciones['total'],
                $base_gravable,
                $deducciones['isr']['total'],
                $deducciones['imss']['total'],
                $deducciones['infonavit']['total'],
                $deducciones['fonacot']['total'],
                $deducciones['pension_alimenticia']['total'],
                $deducciones['otros_descuentos']['total'],
                $deducciones['descuento_comedor']['total'],
                $deducciones['total'],
                $neto_imss,
                "POR REVISAR",
                $base_isn,
                $tasa_isn,
                $importe_isn,
                $cliente
            ];
            $registros[] = $registro;

            $total_deducciones += $deducciones['total'];
            $total_percepciones += $percepciones['total'];
            $total_otros_ingresos += $percepciones['otros_ingresos']['total'];
            $total_otros_descuentos += $deducciones['otros_descuentos']['total'];


        }


        $totales = array_fill(0, '53', '');
        $totales[23] = $total_otros_ingresos;
        $totales[37] = $total_percepciones;
        $totales[44] = $total_otros_descuentos;
        $totales[46] = $total_deducciones;

        $registros[] = $totales;

        return $registros;
    }

    private function get_totales(modelo $entidad, array $campos): array
    {
        $salida = array();
        $salida['total'] = 0;

        foreach ($campos as $key => $data) {
            $gravado = $entidad->suma(campos: array("gravado" => "$entidad->tabla.importe_gravado"), filtro: $data);
            if (errores::$error) {
                return $this->errores->error(mensaje: "Error al obtener $entidad->tabla de la nomina - $key", data: $gravado);
            }

            $exento = (new $entidad($this->link))->suma(campos: array("exento" => "$entidad->tabla.importe_exento"), filtro: $data);
            if (errores::$error) {
                return $this->errores->error(mensaje: "Error al obtener $entidad->tabla de la nomina - $key", data: $exento);
            }

            $salida[$key] = array("gravado" => $gravado["gravado"],
                "exento" => $exento["exento"],
                "total" => $gravado["gravado"] + $exento["exento"]);
            $salida['total'] += $salida[$key]["total"];
        }

        return $salida;
    }
}
