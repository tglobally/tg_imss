<?php

namespace tglobally\tg_imss\controllers;

use base\orm\modelo;
use DateTime;
use gamboamartin\cat_sat\models\cat_sat_isn;
use gamboamartin\comercial\models\com_sucursal;
use gamboamartin\direccion_postal\models\dp_calle_pertenece;
use gamboamartin\errores\errores;

use gamboamartin\facturacion\models\fc_cfdi_sellado;
use gamboamartin\nomina\models\nom_clasificacion;
use gamboamartin\nomina\models\nom_clasificacion_nomina;
use gamboamartin\nomina\models\nom_par_deduccion;
use gamboamartin\nomina\models\nom_par_otro_pago;
use gamboamartin\nomina\models\nom_par_percepcion;
use gamboamartin\plugins\exportador;
use gamboamartin\system\datatables;
use gamboamartin\system\system;
use html\nom_nomina_html;
use PDO;
use stdClass;
use tglobally\tg_nomina\controllers\Reporte_Template;
use tglobally\tg_nomina\models\nom_nomina;

class controlador_nom_nomina extends \tglobally\tg_nomina\controllers\controlador_nom_nomina
{
    public string $link_nom_nomina_exportar_nominas = '';
    public array $nom_clasificacion = array();


    public function __construct(PDO $link, stdClass $paths_conf = new stdClass())
    {
        parent::__construct(link: $link, paths_conf: $paths_conf);
        $this->seccion_titulo = "Nominas";
        $this->titulo_accion = "Listado de Nominas";
        $this->lista_get_data = true;

        $init_links = $this->init_links();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al inicializar links', data: $init_links);
            print_r($error);
            die('Error');
        }
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

        $nom_clasificacion = (new nom_clasificacion($this->link))->filtro_and();
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al obtener registros de clasificacion de nominas', data: $nom_clasificacion);
            print_r($error);
            die('Error');
        }

        $this->nom_clasificacion = $nom_clasificacion->registros;
        $this->inputs = $inputs;

        return (array)$r_alta;
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

    public function exportar_nominas(bool $header, bool $ws = false): array|stdClass
    {
        $filtros = $this->get_filtros();
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'No se pudo generar los filtros', data: $filtros, header: $header, ws: $ws);
        }

        $clasificaciones = (new nom_clasificacion($this->link))->filtro_and(in: $filtros['in']);
        if (errores::$error) {
            return $this->retorno_error(mensaje: 'No se pudo obtener las clasificaciones', data: $clasificaciones,
                header: $header, ws: $ws);
        }

        $data = array();
        $categoria_value = "";

        foreach ($clasificaciones->registros as $clasificacion){

            $clasificacion_nominas = (new nom_clasificacion_nomina($this->link))->filtro_and(columnas: array('nom_nomina_id'),
                filtro: array("nom_clasificacion.id" => $clasificacion['nom_clasificacion_id']));
            if (errores::$error) {
                return $this->retorno_error(mensaje: 'No se pudo obtener las nominas relacionadas a la clasificacion',
                    data: $clasificacion_nominas, header: $header, ws: $ws);
            }

            if ($clasificacion_nominas->n_registros > 0){
                $in = array();
                $in['llave'] = "nom_nomina.id";
                $in['values'] = array_column($clasificacion_nominas->registros, 'nom_nomina_id');

                $nominas = (new nom_nomina($this->link))->filtro_and(columnas: $filtros['columnas'],
                    extra_join: $filtros['extra_join'], filtro: $filtros['filtro'],
                    filtro_rango: $filtros['filtro_rango'], in: $in);
                if (errores::$error) {
                    return $this->retorno_error(mensaje: 'No se pudo obtener las nominas', data: $nominas, header: $header, ws: $ws);
                }

                if ($nominas->n_registros <= 0){
                    $data[$clasificacion['nom_clasificacion_descripcion']] = $this->maqueta_salida(categoria: "GENERALES",
                        categoria_value: "SALIDA GENERAL", periodo: "", remunerados: 0, total_registros: 0,
                        registros: array());
                    if (errores::$error) {
                        $error = $this->errores->error(mensaje: 'Error al maquetar salida de datos', data: $data);
                        print_r($error);
                        die('Error');
                    }
                    continue;
                }

                $registros = $this->fill_data(nominas: $nominas->registros);
                if (errores::$error) {
                    $error = $this->errores->error(mensaje: 'Error al maquetar datos', data: $registros);
                    print_r($error);
                    die('Error');
                }

                switch ($filtros['categoria']) {
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
                    default:
                        $categoria = "GENERALES";
                        $categoria_value = "SALIDA GENERAL";
                }

                $periodo = $filtros['fecha_inicio']."  -  ".$filtros['fecha_final'];

                $data[$clasificacion['nom_clasificacion_descripcion']] = $this->maqueta_salida(categoria: $categoria,
                    categoria_value: $categoria_value, periodo: $periodo, remunerados: 0, total_registros: $nominas->n_registros,
                    registros: $registros);
                if (errores::$error) {
                    $error = $this->errores->error(mensaje: 'Error al maquetar salida de datos', data: $data);
                    print_r($error);
                    die('Error');
                }
            } else {
                $categoria = "GENERALES";
                $categoria_value = "SALIDA GENERAL";

                $data[$clasificacion['nom_clasificacion_descripcion']] = $this->maqueta_salida(categoria: $categoria,
                    categoria_value: $categoria_value, periodo: "", remunerados: 0, total_registros: 0,
                    registros: array());
                if (errores::$error) {
                    $error = $this->errores->error(mensaje: 'Error al maquetar salida de datos', data: $data);
                    print_r($error);
                    die('Error');
                }
            }
        }


        $name = "REPORTE DE NOMINAS_$categoria_value";

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


    private function fill_data(array $nominas): array
    {
        $meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE',
            'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');

        $registros = array();
        $total_deducciones = 0;
        $total_percepciones = 0;
        $total_otros_ingresos = 0;
        $total_otros_descuentos = 0;


        foreach ($nominas as $nomina) {
            $org_sucursal_estado = "No se encontro el registro relacionado";
            $em_empleado_estado = "No se encontro el registro relacionado";
            $cliente_nomina = "No se encontro el registro relacionado";

            if (!empty($nomina['org_sucursal_dp_calle_pertenece_id'])){
                /*return $this->errores->error(mensaje: 'No existe org_sucursal_dp_calle_pertenece_id para la nomina: '. $nomina['nom_nomina_id'],
                    data: $nomina);*/
                $org_sucursal_estado = (new dp_calle_pertenece($this->link))->registro(registro_id: $nomina['org_sucursal_dp_calle_pertenece_id'],
                    columnas: array('dp_estado_descripcion'));
                if (errores::$error) {
                    return $this->errores->error(mensaje: 'Error al obtener el estado', data: $org_sucursal_estado);
                }
                $org_sucursal_estado = $org_sucursal_estado['dp_estado_descripcion'];
            }

            if (!empty($nomina['em_empleado_dp_calle_pertenece_id'])){
                $em_empleado_estado = (new dp_calle_pertenece($this->link))->registro(registro_id: $nomina['em_empleado_dp_calle_pertenece_id'],
                    columnas: array('dp_estado_descripcion'));
                if (errores::$error) {
                    return $this->errores->error(mensaje: 'Error al obtener el estado', data: $em_empleado_estado);
                }
                $em_empleado_estado = $em_empleado_estado['dp_estado_descripcion'];
            }

            $timbrado = (new fc_cfdi_sellado($this->link))->filtro_and(columnas: array('fc_cfdi_sellado_uuid'),
                filtro: array("fc_factura_id" => $nomina['fc_factura_id']), limit: 1);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener cfdi sellado', data: $timbrado);
            }

            if (!empty($nomina['fc_factura_com_sucursal_id'])){
                $cliente_nomina = (new com_sucursal($this->link))->registro(registro_id: $nomina['fc_factura_com_sucursal_id'],
                    columnas: array('com_sucursal_descripcion'));
                if (errores::$error) {
                    return $this->errores->error(mensaje: 'Error al obtener cliente de la nomina', data: $cliente_nomina);
                }

                $cliente_nomina = $cliente_nomina['com_sucursal_descripcion'];
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

            $fecha_inicio = "";
            $fecha_final = "";
            $periodo = "";
            $sueldo = $nomina['em_empleado_salario_diario'];

            if (!empty($nomina['nom_periodo_fecha_inicial_pago'])){
                $fecha_inicio = DateTime::createFromFormat('d/m/Y', date('d/m/Y',
                    strtotime($nomina['nom_periodo_fecha_inicial_pago'])));
            }

            if (!empty($nomina['nom_periodo_fecha_final_pago'])){
                $fecha_final = DateTime::createFromFormat('d/m/Y', date('d/m/Y',
                    strtotime($nomina['fecha_final_pago'])));

                $periodo = $fecha_inicio->format('d/m/Y') . " - " . $fecha_final->format('d/m/Y');

                if (($fecha_inicio->diff($fecha_final))->days > 0){
                    $sueldo = ($fecha_inicio->diff($fecha_final))->days * $nomina['em_empleado_salario_diario'];
                }
            }

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


            $uuid = "";

            if ($timbrado->n_registros > 0) {
                $uuid = $timbrado->registros[0]['fc_cfdi_sellado_uuid'];
            }
/*
            $fi = (new em_empleado($this->link))->obten_factor(em_empleado_id: $nomina['em_empleado_id'],
                fecha_inicio_rel: $nomina['em_empleado_fecha_inicio_rel_laboral']);
            if (errores::$error) {
                return $this->errores->error(mensaje: 'Error al obtener factor de ingracion', data: $fi);
            }*/

            $fi = 1.00;

            $registro = [
                $nomina['fc_factura_folio'],
                $nomina['em_empleado_id'],
                $nomina['em_empleado_ap'] . ' ' . $nomina['em_empleado_am'] . ' ' . $nomina['em_empleado_nombre'],
                $nomina['em_empleado_rfc'],
                $nomina['em_empleado_nss'],
                $nomina['em_registro_patronal_descripcion'],
                $org_sucursal_estado,
                $nomina['org_empresa_razon_social'],
                $em_empleado_estado,
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
                0.00,
                $base_isn,
                $tasa_isn,
                $importe_isn,
                $cliente_nomina
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
